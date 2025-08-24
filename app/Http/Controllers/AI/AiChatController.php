<?php

namespace App\Http\Controllers\AI;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Expense;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\AiConversation;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class AiChatController extends Controller
{
    public function chat(Request $request)
    {
        try {
            // 1) Validate
            $data = $request->validate([
                'message' => ['required', 'string', 'max:4000'],
                'from'    => ['nullable', 'string'],
                'range'   => ['nullable', 'string'], // e.g. 'this_week','last_30_days'
                'session_id' => ['nullable', 'string'], // For conversation continuity
            ]);

            // 2) Feature flag + provider settings
            $enabled = (Setting::where('key', 'ai.enabled')->value('value') ?? '0') === '1';
            $provider = Setting::where('key', 'ai.provider')->value('value') ?? 'openai';
            
            if (!$enabled) {
                return response()->json([
                    'error' => 'AI Business Consultant imezimwa. Tafadhali wezesha kwenye AI Settings.'
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            // Get API key for selected provider
            $apiKey = Setting::where('key', $provider . '.api_key')->value('value');
            if (!$apiKey) {
                return response()->json([
                    'error' => ucfirst($provider) . ' API key haipo. Tafadhali weka API key kwenye AI Settings.'
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            // 3) Check if it's a simple greeting
            $simpleGreetings = ['hello', 'hi', 'habari', 'mambo', 'jambo', 'salamu', 'salama', 'good morning', 'good afternoon', 'good evening'];
            $isSimpleGreeting = in_array(strtolower(trim($data['message'])), $simpleGreetings);
            
            // 4) Fetch business data (only if not a simple greeting)
            if (!$isSimpleGreeting) {
                [$products, $sales, $expenses, $categories, $suppliers] = $this->loadBusinessData($data['range'] ?? 'this_week');
            } else {
                [$products, $sales, $expenses, $categories, $suppliers] = [[], [], [], [], []];
            }

            // 5) Get conversation history for context
            $conversationHistory = $this->getConversationHistory($data['session_id'] ?? null);

            // 6) Build messages
            $system = $this->systemPrompt();
            $user   = $this->userMessage($data['message'], $products, $sales, $expenses, $categories, $suppliers, $conversationHistory);

            // 7) Call AI Provider
            $reply = $this->callAIProvider($provider, $apiKey, $system, $user);

            // 8) Save conversation
            $this->saveConversation($data, $reply, $provider, $products, $sales, $expenses, $categories, $suppliers);

            // 9) Log + return
            Log::info('AI_CHAT_SUCCESS', [
                'provider' => $provider,
                'from' => $data['from'] ?? 'admin', 
                'q' => $data['message'], 
                'range' => $data['range'] ?? null,
                'is_simple_greeting' => $isSimpleGreeting,
                'session_id' => $data['session_id'] ?? null
            ]);
            
            return response()->json(['reply' => $reply]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('AI_CHAT_VALIDATION_ERROR', ['errors' => $e->errors()]);
            return response()->json([
                'error' => 'Tafadhali weka swali sahihi (max 4000 characters).'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
            
        } catch (\Exception $e) {
            Log::error('AI_CHAT_GENERAL_ERROR', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Samahani, kuna tatizo la kiufundi. Tafadhali jaribu tena baadae.'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function getConversationHistory($sessionId = null)
    {
        if (!$sessionId) {
            return [];
        }

        // Get recent conversations for context (last 5 exchanges)
        return AiConversation::where('session_id', $sessionId)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->reverse()
            ->map(function ($conversation) {
                return [
                    'role' => 'user',
                    'content' => $conversation->user_message
                ];
            })
            ->toArray();
    }

    private function saveConversation($data, $reply, $provider, $products, $sales, $expenses, $categories, $suppliers)
    {
        try {
            AiConversation::create([
                'user_id' => auth()->id() ?? 1, // Default to admin if not authenticated
                'session_id' => $data['session_id'] ?? null,
                'user_message' => $data['message'],
                'ai_response' => $reply,
                'provider' => $provider,
                'model' => Setting::where('key', $provider . '.model')->value('value'),
                'business_data' => [
                    'products_count' => $products->count(),
                    'sales_count' => $sales->count(),
                    'expenses_count' => $expenses->count(),
                    'categories_count' => $categories->count(),
                    'suppliers_count' => $suppliers->count(),
                ],
                'range' => $data['range'] ?? 'this_week',
                'tokens_used' => null, // Could be extracted from API response
                'cost' => null, // Could be calculated based on tokens
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to save AI conversation', ['error' => $e->getMessage()]);
        }
    }

    private function loadBusinessData(string $range): array
    {
        // For now, load all data without date filtering to avoid empty results
        $products = Product::with('category', 'supplier')
            ->select('id', 'name', 'product_code as sku', 'purchase_price as cost', 'selling_price as price', 'stock_quantity as stock', 'category_id', 'supplier_id')
            ->get();

        $sales = Sale::with('product', 'customer')
            ->select('id', 'product_id', 'quantity_sold as quantity', 'total_price as total', 'sale_time as created_at', 'customer_phone')
            ->get();

        $expenses = Expense::select('id', 'description as name', 'amount', 'category', 'expense_date as created_at')
            ->get();

        $categories = Category::select('id', 'name', 'color')->get();
        $suppliers = Supplier::select('id', 'name', 'email', 'phone')->get();

        return [$products, $sales, $expenses, $categories, $suppliers];
    }

    private function systemPrompt(): string
    {
        // Get customizable system prompt from settings
        $customPrompt = Setting::where('key', 'ai.system_prompt')->value('value');
        $language = Setting::where('key', 'ai.language')->value('value') ?? 'swahili';
        $responseStyle = Setting::where('key', 'ai.response_style')->value('value') ?? 'professional';
        
        if ($customPrompt) {
            return $customPrompt;
        }
        
        // Default prompt with language and style customization
        $languageRule = match($language) {
            'english' => 'Respond in clear, professional English.',
            'both' => 'Respond in clear, professional **Kiswahili** (you may mix light English where helpful).',
            default => 'Respond in clear, professional **Kiswahili** (you may mix light English where helpful).'
        };
        
        $styleRule = match($responseStyle) {
            'casual' => 'Use a friendly, conversational tone while maintaining professionalism. Be warm and approachable.',
            'detailed' => 'Provide comprehensive, detailed analysis with clear explanations and examples.',
            'concise' => 'Keep responses concise and to the point, but still friendly and helpful.',
            default => 'Use a warm, professional tone that feels like talking to a trusted business advisor.'
        };
        
        return <<<PROMPT
You are a friendly and experienced business consultant with deep expertise in retail, finance, and operational excellence. You have a warm, approachable personality and speak like a trusted friend who happens to be a business expert. You're passionate about helping businesses grow and succeed.

Your communication style:
- {$languageRule}
- {$styleRule}
- Be conversational and natural, not robotic or overly formal
- Show genuine interest in the business owner's success
- Use encouraging and positive language
- Ask follow-up questions when appropriate
- Share insights as if you're having a friendly business conversation
- Be empathetic and understanding of business challenges

Your expertise:
- You have 20+ years of experience in retail and business strategy
- You're data-driven but explain things in simple, practical terms
- You focus on actionable advice that can be implemented immediately
- You identify opportunities for growth and cost optimization
- You help prioritize actions based on impact and feasibility

Response guidelines:
- For greetings: Be warm and welcoming, ask about their business day, suggest areas they might want to explore
- For business questions: Provide clear, actionable insights with specific recommendations
- Use natural language transitions and conversational flow
- Include relevant examples and analogies when helpful
- Structure responses clearly but conversationally
- Use formatting (headers, bold, lists) to make information easy to scan
- Always end with an encouraging note or next step suggestion

Remember: You're not just analyzing data - you're having a conversation with someone who trusts you to help their business succeed. Be the friendly, knowledgeable business advisor they can rely on.
PROMPT;
    }

    private function userMessage(string $message, $products, $sales, $expenses, $categories, $suppliers, array $conversationHistory = []): string
    {
        // Check if it's a simple greeting
        $simpleGreetings = ['hello', 'hi', 'habari', 'mambo', 'jambo', 'salamu', 'salama', 'good morning', 'good afternoon', 'good evening'];
        $isSimpleGreeting = in_array(strtolower(trim($message)), $simpleGreetings);
        
        if ($isSimpleGreeting) {
            return <<<USER
USER_QUESTION: {$message}

INSTRUCTIONS:
- This is a friendly greeting, respond warmly and naturally in Kiswahili
- Ask how their business day is going
- Show genuine interest in their business
- Suggest some areas they might want to explore or discuss
- Keep it conversational and welcoming
- Mention that you're here to help with any business questions they have
USER;
        }
        
        // Check if it's a follow-up or clarification question
        $followUpKeywords = ['hivyo', 'sawa', 'asante', 'thank you', 'nzuri', 'good', 'okay', 'alright', 'continue', 'more', 'zaidi'];
        $isFollowUp = false;
        foreach ($followUpKeywords as $keyword) {
            if (stripos($message, $keyword) !== false) {
                $isFollowUp = true;
                break;
            }
        }
        
        if ($isFollowUp) {
            return <<<USER
USER_QUESTION: {$message}

INSTRUCTIONS:
- This appears to be a follow-up or acknowledgment message
- Respond naturally and conversationally
- Ask if they have any other questions or need clarification
- Offer to help with specific business areas they might want to explore
- Keep the conversation flowing naturally
USER;
        }
        
        // Build conversation context
        $conversationContext = '';
        if (!empty($conversationHistory)) {
            $conversationContext = "\n\nCONVERSATION_HISTORY:\n";
            foreach ($conversationHistory as $exchange) {
                $conversationContext .= "- User: {$exchange['content']}\n";
            }
        }
        
        return <<<USER
USER_QUESTION: {$message}{$conversationContext}

BUSINESS_DATA (JSON):
- products: {$products->toJson()}
- sales: {$sales->toJson()}
- expenses: {$expenses->toJson()}
- categories: {$categories->toJson()}
- suppliers: {$suppliers->toJson()}

INSTRUCTIONS:
- Analyze the business data to provide helpful insights
- Focus on practical, actionable advice
- Identify opportunities for improvement and growth
- Be conversational and encouraging in your analysis
- Use the data to support your recommendations
- Structure your response clearly but naturally
- End with a positive note or next step suggestion
- Ask follow-up questions to keep the conversation going
USER;
    }

    private function callAIProvider(string $provider, string $apiKey, string $system, string $user): string
    {
        return match($provider) {
            'openai' => $this->callOpenAI($apiKey, $system, $user),
            'deepseek' => $this->callDeepSeek($apiKey, $system, $user),
            'gemini' => $this->callGemini($apiKey, $system, $user),
            default => 'Samahani, AI provider haijulikani.'
        };
    }

    private function callOpenAI(string $apiKey, string $system, string $user): string
    {
        $model = Setting::where('key', 'openai.model')->value('value') ?? 'gpt-4o-mini';
        $temperature = Setting::where('key', 'openai.temperature')->value('value') ?? '0.2';
        $maxTokens = Setting::where('key', 'openai.max_tokens')->value('value') ?? '2000';
        
        $client = new \GuzzleHttp\Client([
            'base_uri' => 'https://api.openai.com/v1/',
            'timeout' => 30,
        ]);

        $payload = [
            'model' => $model,
            'messages' => [
                ['role' => 'system', 'content' => $system],
                ['role' => 'user', 'content' => $user],
            ],
            'temperature' => (float) $temperature,
            'max_tokens' => (int) $maxTokens,
        ];

        try {
            $res = $client->post('chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type'  => 'application/json'
                ],
                'json' => $payload,
            ]);

            $body = json_decode((string) $res->getBody(), true);
            return $body['choices'][0]['message']['content'] ?? 'Samahani, sijapata majibu kwa sasa.';
        } catch (\Exception $e) {
            Log::error('OpenAI API Error', ['error' => $e->getMessage()]);
            return 'Samahani, kuna tatizo na OpenAI service. Jaribu tena baadae.';
        }
    }

    private function callDeepSeek(string $apiKey, string $system, string $user): string
    {
        $model = Setting::where('key', 'deepseek.model')->value('value') ?? 'deepseek-chat';
        $temperature = Setting::where('key', 'deepseek.temperature')->value('value') ?? '0.2';
        $maxTokens = Setting::where('key', 'deepseek.max_tokens')->value('value') ?? '2000';
        
        $client = new \GuzzleHttp\Client([
            'base_uri' => 'https://api.deepseek.com/v1/',
            'timeout' => 30,
        ]);

        $payload = [
            'model' => $model,
            'messages' => [
                ['role' => 'system', 'content' => $system],
                ['role' => 'user', 'content' => $user],
            ],
            'temperature' => (float) $temperature,
            'max_tokens' => (int) $maxTokens,
        ];

        try {
            $res = $client->post('chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type'  => 'application/json'
                ],
                'json' => $payload,
            ]);

            $body = json_decode((string) $res->getBody(), true);
            return $body['choices'][0]['message']['content'] ?? 'Samahani, sijapata majibu kwa sasa.';
        } catch (\Exception $e) {
            Log::error('DeepSeek API Error', ['error' => $e->getMessage()]);
            return 'Samahani, kuna tatizo na DeepSeek service. Jaribu tena baadae.';
        }
    }

    private function callGemini(string $apiKey, string $system, string $user): string
    {
        $model = Setting::where('key', 'gemini.model')->value('value') ?? 'gemini-1.5-flash';
        $temperature = Setting::where('key', 'gemini.temperature')->value('value') ?? '0.2';
        $maxTokens = Setting::where('key', 'gemini.max_tokens')->value('value') ?? '2000';
        
        $client = new \GuzzleHttp\Client([
            'base_uri' => 'https://generativelanguage.googleapis.com/v1beta/models/',
            'timeout' => 30,
        ]);

        $payload = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $system . "\n\n" . $user]
                    ]
                ]
            ],
            'generationConfig' => [
                'temperature' => (float) $temperature,
                'maxOutputTokens' => (int) $maxTokens,
            ]
        ];

        try {
            $res = $client->post($model . ':generateContent?key=' . $apiKey, [
                'headers' => [
                    'Content-Type' => 'application/json'
                ],
                'json' => $payload,
            ]);

            $body = json_decode((string) $res->getBody(), true);
            return $body['candidates'][0]['content']['parts'][0]['text'] ?? 'Samahani, sijapata majibu kwa sasa.';
        } catch (\Exception $e) {
            Log::error('Gemini API Error', ['error' => $e->getMessage()]);
            return 'Samahani, kuna tatizo na Gemini service. Jaribu tena baadae.';
        }
    }
}

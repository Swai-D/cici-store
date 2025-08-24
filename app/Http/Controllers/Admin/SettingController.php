<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    public function editAI()
    {
        return view('admin.ai', [
            'enabled' => (Setting::where('key', 'ai.enabled')->value('value') ?? '0') === '1',
            'provider' => Setting::where('key', 'ai.provider')->value('value') ?? 'openai',
            'maskedKey' => $this->maskedKey(),
            'openaiModel' => Setting::where('key', 'openai.model')->value('value') ?? 'gpt-4o-mini',
            'openaiTemperature' => Setting::where('key', 'openai.temperature')->value('value') ?? '0.2',
            'openaiMaxTokens' => Setting::where('key', 'openai.max_tokens')->value('value') ?? '2000',
            'deepseekModel' => Setting::where('key', 'deepseek.model')->value('value') ?? 'deepseek-chat',
            'deepseekTemperature' => Setting::where('key', 'deepseek.temperature')->value('value') ?? '0.2',
            'deepseekMaxTokens' => Setting::where('key', 'deepseek.max_tokens')->value('value') ?? '2000',
            'geminiModel' => Setting::where('key', 'gemini.model')->value('value') ?? 'gemini-1.5-flash',
            'geminiTemperature' => Setting::where('key', 'gemini.temperature')->value('value') ?? '0.2',
            'geminiMaxTokens' => Setting::where('key', 'gemini.max_tokens')->value('value') ?? '2000',
            'systemPrompt' => Setting::where('key', 'ai.system_prompt')->value('value') ?? '',
            'language' => Setting::where('key', 'ai.language')->value('value') ?? 'swahili',
            'responseStyle' => Setting::where('key', 'ai.response_style')->value('value') ?? 'professional',
            'includeCharts' => (Setting::where('key', 'ai.include_charts')->value('value') ?? '1') === '1',
            'autoAnalyze' => (Setting::where('key', 'ai.auto_analyze')->value('value') ?? '1') === '1',
        ]);
    }

    public function updateAI(Request $request)
    {
        $data = $request->validate([
            'enabled' => ['required', 'boolean'],
            'provider' => ['required', 'string', 'in:openai,deepseek,gemini'],
            'openai_key' => ['nullable', 'string'],
            'deepseek_key' => ['nullable', 'string'],
            'gemini_key' => ['nullable', 'string'],
            'openai_model' => ['required', 'string'],
            'openai_temperature' => ['required', 'numeric', 'between:0,2'],
            'openai_max_tokens' => ['required', 'integer', 'min:100', 'max:4000'],
            'deepseek_model' => ['required', 'string'],
            'deepseek_temperature' => ['required', 'numeric', 'between:0,2'],
            'deepseek_max_tokens' => ['required', 'integer', 'min:100', 'max:4000'],
            'gemini_model' => ['required', 'string'],
            'gemini_temperature' => ['required', 'numeric', 'between:0,2'],
            'gemini_max_tokens' => ['required', 'integer', 'min:100', 'max:4000'],
            'system_prompt' => ['required', 'string', 'max:2000'],
            'language' => ['required', 'string', 'in:swahili,english,both'],
            'response_style' => ['required', 'string', 'in:professional,casual,detailed,concise'],
            'include_charts' => ['boolean'],
            'auto_analyze' => ['boolean'],
        ]);

        // Basic AI Settings
        Setting::updateOrCreate(['key' => 'ai.enabled'], ['value' => $data['enabled'] ? '1' : '0']);
        Setting::updateOrCreate(['key' => 'ai.provider'], ['value' => $data['provider']]);
        
        // API Keys
        if (!empty($data['openai_key'])) {
            Setting::updateOrCreate(['key' => 'openai.api_key'], ['value' => $data['openai_key']]);
        }
        if (!empty($data['deepseek_key'])) {
            Setting::updateOrCreate(['key' => 'deepseek.api_key'], ['value' => $data['deepseek_key']]);
        }
        if (!empty($data['gemini_key'])) {
            Setting::updateOrCreate(['key' => 'gemini.api_key'], ['value' => $data['gemini_key']]);
        }
        
        // OpenAI Settings
        Setting::updateOrCreate(['key' => 'openai.model'], ['value' => $data['openai_model']]);
        Setting::updateOrCreate(['key' => 'openai.temperature'], ['value' => $data['openai_temperature']]);
        Setting::updateOrCreate(['key' => 'openai.max_tokens'], ['value' => $data['openai_max_tokens']]);
        
        // DeepSeek Settings
        Setting::updateOrCreate(['key' => 'deepseek.model'], ['value' => $data['deepseek_model']]);
        Setting::updateOrCreate(['key' => 'deepseek.temperature'], ['value' => $data['deepseek_temperature']]);
        Setting::updateOrCreate(['key' => 'deepseek.max_tokens'], ['value' => $data['deepseek_max_tokens']]);
        
        // Gemini Settings
        Setting::updateOrCreate(['key' => 'gemini.model'], ['value' => $data['gemini_model']]);
        Setting::updateOrCreate(['key' => 'gemini.temperature'], ['value' => $data['gemini_temperature']]);
        Setting::updateOrCreate(['key' => 'gemini.max_tokens'], ['value' => $data['gemini_max_tokens']]);
        
        // AI Customization
        Setting::updateOrCreate(['key' => 'ai.system_prompt'], ['value' => $data['system_prompt']]);
        Setting::updateOrCreate(['key' => 'ai.language'], ['value' => $data['language']]);
        Setting::updateOrCreate(['key' => 'ai.response_style'], ['value' => $data['response_style']]);
        Setting::updateOrCreate(['key' => 'ai.include_charts'], ['value' => $data['include_charts'] ? '1' : '0']);
        Setting::updateOrCreate(['key' => 'ai.auto_analyze'], ['value' => $data['auto_analyze'] ? '1' : '0']);

        return back()->with('status', 'AI settings updated successfully!');
    }

    private function maskedKey(): ?string
    {
        $provider = Setting::where('key', 'ai.provider')->value('value') ?? 'openai';
        $key = Setting::where('key', $provider . '.api_key')->value('value');
        if (!$key) return null;
        return substr($key, 0, 4) . '••••' . substr($key, -4);
    }

    public function validateApiKey(Request $request)
    {
        $data = $request->validate([
            'provider' => ['required', 'string', 'in:openai,deepseek,gemini'],
            'api_key' => ['required', 'string'],
        ]);

        $provider = $data['provider'];
        $apiKey = $data['api_key'];

        try {
            $isValid = $this->testApiKey($provider, $apiKey);
            
            if ($isValid) {
                // Save the API key if validation passes
                Setting::updateOrCreate(
                    ['key' => $provider . '.api_key'], 
                    ['value' => $apiKey]
                );
                
                return response()->json([
                    'success' => true,
                    'message' => ucfirst($provider) . ' API key validated and saved successfully!'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => ucfirst($provider) . ' API key is invalid. Please check and try again.'
                ], 422);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error validating API key: ' . $e->getMessage()
            ], 500);
        }
    }

    private function testApiKey(string $provider, string $apiKey): bool
    {
        $client = new \GuzzleHttp\Client(['timeout' => 10]);

        try {
            switch ($provider) {
                case 'openai':
                    $response = $client->get('https://api.openai.com/v1/models', [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $apiKey,
                            'Content-Type' => 'application/json'
                        ]
                    ]);
                    return $response->getStatusCode() === 200;

                case 'deepseek':
                    $response = $client->get('https://api.deepseek.com/v1/models', [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $apiKey,
                            'Content-Type' => 'application/json'
                        ]
                    ]);
                    return $response->getStatusCode() === 200;

                case 'gemini':
                    // Test with a simple generation request
                    $payload = [
                        'contents' => [
                            [
                                'parts' => [
                                    ['text' => 'Hello, test message']
                                ]
                            ]
                        ],
                        'generationConfig' => [
                            'temperature' => 0.1,
                            'maxOutputTokens' => 10,
                        ]
                    ];
                    
                    $response = $client->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=' . $apiKey, [
                        'headers' => [
                            'Content-Type' => 'application/json'
                        ],
                        'json' => $payload
                    ]);
                    return $response->getStatusCode() === 200;

                default:
                    return false;
            }
        } catch (\Exception $e) {
            \Log::error('API Key Validation Error', [
                'provider' => $provider,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}

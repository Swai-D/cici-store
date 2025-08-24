<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('AI Business Consultant Settings') }}
            </h2>
            <div class="flex items-center space-x-4">
                <a href="{{ route('ai.chat') }}" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    AI Chat
                </a>
                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Dashboard
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        AI Configuration
                    </h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                        Configure the AI Business Consultant settings and OpenAI API integration.
                    </p>
                </div>

                <div class="p-6">
                    @if (session('status'))
                        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('status') }}</span>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.ai.update') }}" class="space-y-6">
                        @csrf
                        
                        <!-- Enable AI -->
                        <div class="flex items-center space-x-3">
                            <input type="checkbox" 
                                   id="enabled" 
                                   name="enabled" 
                                   value="1" 
                                   {{ $enabled ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="enabled" class="text-sm font-medium text-gray-700">
                                Enable AI Business Consultant
                            </label>
                        </div>

                        <!-- AI Provider Selection -->
                        <div>
                            <label for="provider" class="block text-sm font-medium text-gray-700 mb-2">
                                AI Provider
                            </label>
                            <select id="provider" name="provider" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select AI Provider</option>
                                <option value="openai" {{ $provider === 'openai' ? 'selected' : '' }}>OpenAI (GPT-4, GPT-3.5)</option>
                                <option value="deepseek" {{ $provider === 'deepseek' ? 'selected' : '' }}>DeepSeek (DeepSeek Chat)</option>
                                <option value="gemini" {{ $provider === 'gemini' ? 'selected' : '' }}>Google Gemini (Gemini Pro)</option>
                            </select>
                            <p class="text-xs text-gray-500 mt-1">
                                Choose your preferred AI provider to configure settings
                            </p>
                        </div>

                        <!-- Provider Info Cards -->
                        <div id="provider-info" class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6" style="display: none;">
                            <!-- OpenAI Info -->
                            <div id="openai-info" class="provider-info bg-blue-50 border border-blue-200 rounded-lg p-4" style="display: none;">
                                <div class="flex items-center mb-2">
                                    <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                    </svg>
                                    <h5 class="text-sm font-medium text-blue-800">OpenAI</h5>
                                </div>
                                <p class="text-xs text-blue-700 mb-2">Best for: Complex analysis, creative content</p>
                                <ul class="text-xs text-blue-600 space-y-1">
                                    <li>• GPT-4o (Most capable)</li>
                                    <li>• GPT-4o Mini (Fast & cheap)</li>
                                    <li>• Excellent Kiswahili support</li>
                                </ul>
                            </div>

                            <!-- DeepSeek Info -->
                            <div id="deepseek-info" class="provider-info bg-green-50 border border-green-200 rounded-lg p-4" style="display: none;">
                                <div class="flex items-center mb-2">
                                    <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                    <h5 class="text-sm font-medium text-green-800">DeepSeek</h5>
                                </div>
                                <p class="text-xs text-green-700 mb-2">Best for: Technical analysis, coding</p>
                                <ul class="text-xs text-green-600 space-y-1">
                                    <li>• DeepSeek Chat (Recommended)</li>
                                    <li>• DeepSeek Coder (Code focused)</li>
                                    <li>• Cost-effective alternative</li>
                                </ul>
                            </div>

                            <!-- Gemini Info -->
                            <div id="gemini-info" class="provider-info bg-purple-50 border border-purple-200 rounded-lg p-4" style="display: none;">
                                <div class="flex items-center mb-2">
                                    <svg class="w-5 h-5 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"></path>
                                    </svg>
                                    <h5 class="text-sm font-medium text-purple-800">Google Gemini</h5>
                                </div>
                                <p class="text-xs text-purple-700 mb-2">Best for: Multimodal analysis, Google integration</p>
                                <ul class="text-xs text-purple-600 space-y-1">
                                    <li>• Gemini 1.5 Pro (Best quality)</li>
                                    <li>• Gemini 1.5 Flash (Fast)</li>
                                    <li>• Free tier available</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Dynamic API Key Section -->
                        <div id="api-key-section" class="bg-gray-50 p-4 rounded-lg" style="display: none;">
                            <h4 class="text-sm font-medium text-gray-700 mb-3">API Key</h4>
                            
                                                         <!-- OpenAI API Key -->
                             <div id="openai-key-field" class="api-key-field" style="display: none;">
                                 <label for="openai_key" class="block text-sm font-medium text-gray-700 mb-2">
                                     OpenAI API Key
                                 </label>
                                 <div class="flex space-x-2">
                                     <input type="password" 
                                            id="openai_key" 
                                            name="openai_key" 
                                            placeholder="sk-..." 
                                            class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                     <button type="button" 
                                             onclick="validateApiKey('openai')"
                                             class="px-3 py-2 bg-green-600 text-white text-sm rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                                         Validate
                                     </button>
                                 </div>
                                 <p class="text-xs text-gray-500 mt-1">
                                     Get your API key from <a href="https://platform.openai.com/api-keys" target="_blank" class="text-blue-600 hover:underline">OpenAI Platform</a>
                                 </p>
                                 <div id="openai-validation-result" class="mt-2"></div>
                             </div>

                                                         <!-- DeepSeek API Key -->
                             <div id="deepseek-key-field" class="api-key-field" style="display: none;">
                                 <label for="deepseek_key" class="block text-sm font-medium text-gray-700 mb-2">
                                     DeepSeek API Key
                                 </label>
                                 <div class="flex space-x-2">
                                     <input type="password" 
                                            id="deepseek_key" 
                                            name="deepseek_key" 
                                            placeholder="sk-..." 
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                     <button type="button" 
                                             onclick="validateApiKey('deepseek')"
                                             class="px-3 py-2 bg-green-600 text-white text-sm rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                                         Validate
                                     </button>
                                 </div>
                                 <p class="text-xs text-gray-500 mt-1">
                                     Get your API key from <a href="https://platform.deepseek.com/api-keys" target="_blank" class="text-blue-600 hover:underline">DeepSeek Platform</a>
                                 </p>
                                 <div id="deepseek-validation-result" class="mt-2"></div>
                             </div>

                                                         <!-- Gemini API Key -->
                             <div id="gemini-key-field" class="api-key-field" style="display: none;">
                                 <label for="gemini_key" class="block text-sm font-medium text-gray-700 mb-2">
                                     Google Gemini API Key
                                 </label>
                                 <div class="flex space-x-2">
                                     <input type="password" 
                                            id="gemini_key" 
                                            name="gemini_key" 
                                            placeholder="AIza..." 
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                     <button type="button" 
                                             onclick="validateApiKey('gemini')"
                                             class="px-3 py-2 bg-green-600 text-white text-sm rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                                         Validate
                                     </button>
                                 </div>
                                 <p class="text-xs text-gray-500 mt-1">
                                     Get your API key from <a href="https://makersuite.google.com/app/apikey" target="_blank" class="text-blue-600 hover:underline">Google AI Studio</a>
                                 </p>
                                 <div id="gemini-validation-result" class="mt-2"></div>
                             </div>
                            
                            <p class="text-xs text-gray-500 mt-3">
                                Leave empty to keep current key unchanged
                            </p>
                        </div>

                        <!-- Dynamic Model Configuration -->
                        <div id="model-config-section" class="bg-white border border-gray-200 rounded-lg p-6" style="display: none;">
                            <h4 class="text-lg font-medium text-gray-900 mb-4">Model Configuration</h4>
                            
                            <!-- OpenAI Configuration -->
                            <div id="openai-config" class="model-config p-4 bg-blue-50 rounded-lg" style="display: none;">
                                <h5 class="text-sm font-medium text-blue-800 mb-3 flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                    </svg>
                                    OpenAI Settings
                                </h5>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label for="openai_model" class="block text-sm font-medium text-gray-700 mb-1">Model</label>
                                        <select id="openai_model" name="openai_model" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                                            <option value="gpt-4o-mini" {{ $openaiModel === 'gpt-4o-mini' ? 'selected' : '' }}>GPT-4o Mini (Fast & Cheap)</option>
                                            <option value="gpt-4o" {{ $openaiModel === 'gpt-4o' ? 'selected' : '' }}>GPT-4o (Best Quality)</option>
                                            <option value="gpt-3.5-turbo" {{ $openaiModel === 'gpt-3.5-turbo' ? 'selected' : '' }}>GPT-3.5 Turbo (Balanced)</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="openai_temperature" class="block text-sm font-medium text-gray-700 mb-1">Temperature</label>
                                        <input type="number" id="openai_temperature" name="openai_temperature" 
                                               value="{{ $openaiTemperature }}" step="0.1" min="0" max="2"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                                        <p class="text-xs text-gray-500 mt-1">0 = Focused, 2 = Creative</p>
                                    </div>
                                    <div>
                                        <label for="openai_max_tokens" class="block text-sm font-medium text-gray-700 mb-1">Max Tokens</label>
                                        <input type="number" id="openai_max_tokens" name="openai_max_tokens" 
                                               value="{{ $openaiMaxTokens }}" min="100" max="4000"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                                    </div>
                                </div>
                            </div>

                            <!-- DeepSeek Configuration -->
                            <div id="deepseek-config" class="model-config p-4 bg-green-50 rounded-lg" style="display: none;">
                                <h5 class="text-sm font-medium text-green-800 mb-3 flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                    DeepSeek Settings
                                </h5>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label for="deepseek_model" class="block text-sm font-medium text-gray-700 mb-1">Model</label>
                                        <select id="deepseek_model" name="deepseek_model" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                                            <option value="deepseek-chat" {{ $deepseekModel === 'deepseek-chat' ? 'selected' : '' }}>DeepSeek Chat (Recommended)</option>
                                            <option value="deepseek-coder" {{ $deepseekModel === 'deepseek-coder' ? 'selected' : '' }}>DeepSeek Coder (Code Focused)</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="deepseek_temperature" class="block text-sm font-medium text-gray-700 mb-1">Temperature</label>
                                        <input type="number" id="deepseek_temperature" name="deepseek_temperature" 
                                               value="{{ $deepseekTemperature }}" step="0.1" min="0" max="2"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                                        <p class="text-xs text-gray-500 mt-1">0 = Focused, 2 = Creative</p>
                                    </div>
                                    <div>
                                        <label for="deepseek_max_tokens" class="block text-sm font-medium text-gray-700 mb-1">Max Tokens</label>
                                        <input type="number" id="deepseek_max_tokens" name="deepseek_max_tokens" 
                                               value="{{ $deepseekMaxTokens }}" min="100" max="4000"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                                    </div>
                                </div>
                            </div>

                            <!-- Gemini Configuration -->
                            <div id="gemini-config" class="model-config p-4 bg-purple-50 rounded-lg" style="display: none;">
                                <h5 class="text-sm font-medium text-purple-800 mb-3 flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"></path>
                                    </svg>
                                    Google Gemini Settings
                                </h5>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label for="gemini_model" class="block text-sm font-medium text-gray-700 mb-1">Model</label>
                                        <select id="gemini_model" name="gemini_model" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                                            <option value="gemini-1.5-flash" {{ $geminiModel === 'gemini-1.5-flash' ? 'selected' : '' }}>Gemini 1.5 Flash (Fast)</option>
                                            <option value="gemini-1.5-pro" {{ $geminiModel === 'gemini-1.5-pro' ? 'selected' : '' }}>Gemini 1.5 Pro (Best Quality)</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="gemini_temperature" class="block text-sm font-medium text-gray-700 mb-1">Temperature</label>
                                        <input type="number" id="gemini_temperature" name="gemini_temperature" 
                                               value="{{ $geminiTemperature }}" step="0.1" min="0" max="2"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                                        <p class="text-xs text-gray-500 mt-1">0 = Focused, 2 = Creative</p>
                                    </div>
                                    <div>
                                        <label for="gemini_max_tokens" class="block text-sm font-medium text-gray-700 mb-1">Max Tokens</label>
                                        <input type="number" id="gemini_max_tokens" name="gemini_max_tokens" 
                                               value="{{ $geminiMaxTokens }}" min="100" max="4000"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- AI Customization -->
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h4 class="text-lg font-medium text-gray-900 mb-4">AI Customization</h4>
                            
                            <!-- System Prompt -->
                            <div class="mb-6">
                                <label for="system_prompt" class="block text-sm font-medium text-gray-700 mb-2">
                                    System Prompt (AI Personality)
                                </label>
                                <textarea id="system_prompt" name="system_prompt" rows="6"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                          placeholder="Define the AI's personality and behavior...">{{ $systemPrompt }}</textarea>
                                <p class="text-xs text-gray-500 mt-1">
                                    This defines how the AI will behave and respond to questions
                                </p>
                            </div>

                            <!-- Language & Style -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="language" class="block text-sm font-medium text-gray-700 mb-2">Response Language</label>
                                    <select id="language" name="language" 
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                        <option value="swahili" {{ $language === 'swahili' ? 'selected' : '' }}>Kiswahili (Primary)</option>
                                        <option value="english" {{ $language === 'english' ? 'selected' : '' }}>English</option>
                                        <option value="both" {{ $language === 'both' ? 'selected' : '' }}>Both (Swahili + English)</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="response_style" class="block text-sm font-medium text-gray-700 mb-2">Response Style</label>
                                    <select id="response_style" name="response_style" 
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                        <option value="professional" {{ $responseStyle === 'professional' ? 'selected' : '' }}>Professional</option>
                                        <option value="casual" {{ $responseStyle === 'casual' ? 'selected' : '' }}>Casual</option>
                                        <option value="detailed" {{ $responseStyle === 'detailed' ? 'selected' : '' }}>Detailed</option>
                                        <option value="concise" {{ $responseStyle === 'concise' ? 'selected' : '' }}>Concise</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Advanced Features -->
                            <div class="mt-6 space-y-3">
                                <div class="flex items-center space-x-3">
                                    <input type="checkbox" id="include_charts" name="include_charts" value="1" 
                                           {{ $includeCharts ? 'checked' : '' }}
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <label for="include_charts" class="text-sm font-medium text-gray-700">
                                        Include Charts & Visualizations
                                    </label>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <input type="checkbox" id="auto_analyze" name="auto_analyze" value="1" 
                                           {{ $autoAnalyze ? 'checked' : '' }}
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <label for="auto_analyze" class="text-sm font-medium text-gray-700">
                                        Auto-analyze Business Data
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Info Box -->
                        <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                            <h3 class="text-sm font-medium text-blue-800 mb-2">About AI Business Consultant</h3>
                            <ul class="text-sm text-blue-700 space-y-1">
                                <li>• Analyzes your business data (products, sales, expenses)</li>
                                <li>• Provides actionable recommendations in Kiswahili</li>
                                <li>• Identifies profitable products and cost optimization opportunities</li>
                                <li>• Generates weekly action plans for business growth</li>
                                <li>• Supports multiple AI providers (OpenAI, DeepSeek, Gemini)</li>
                                <li>• Customizable prompts and response styles</li>
                            </ul>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-end space-x-3">
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Save Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const providerSelect = document.getElementById('provider');
            const enabledCheckbox = document.getElementById('enabled');
            
            // Provider info cards
            const providerInfoSection = document.getElementById('provider-info');
            const providerInfos = {
                'openai': document.getElementById('openai-info'),
                'deepseek': document.getElementById('deepseek-info'),
                'gemini': document.getElementById('gemini-info')
            };
            
            // API Key sections
            const apiKeySection = document.getElementById('api-key-section');
            const apiKeyFields = {
                'openai': document.getElementById('openai-key-field'),
                'deepseek': document.getElementById('deepseek-key-field'),
                'gemini': document.getElementById('gemini-key-field')
            };
            
            // Model configuration sections
            const modelConfigSection = document.getElementById('model-config-section');
            const modelConfigs = {
                'openai': document.getElementById('openai-config'),
                'deepseek': document.getElementById('deepseek-config'),
                'gemini': document.getElementById('gemini-config')
            };

            // Update UI based on provider selection
            function updateProviderUI() {
                const selectedProvider = providerSelect.value;
                const isEnabled = enabledCheckbox.checked;
                
                // Show/hide provider info cards
                if (isEnabled && selectedProvider) {
                    providerInfoSection.style.display = 'block';
                    
                    // Show only the selected provider's info card
                    Object.keys(providerInfos).forEach(provider => {
                        const info = providerInfos[provider];
                        if (info) {
                            if (provider === selectedProvider) {
                                info.style.display = 'block';
                            } else {
                                info.style.display = 'none';
                            }
                        }
                    });
                } else {
                    providerInfoSection.style.display = 'none';
                }
                
                // Show/hide API key section
                if (isEnabled && selectedProvider) {
                    apiKeySection.style.display = 'block';
                    
                    // Show only the selected provider's API key field
                    Object.keys(apiKeyFields).forEach(provider => {
                        const field = apiKeyFields[provider];
                        if (field) {
                            if (provider === selectedProvider) {
                                field.style.display = 'block';
                                const input = field.querySelector('input');
                                if (input) input.required = true;
                            } else {
                                field.style.display = 'none';
                                const input = field.querySelector('input');
                                if (input) input.required = false;
                            }
                        }
                    });
                } else {
                    apiKeySection.style.display = 'none';
                }
                
                // Show/hide model configuration section
                if (isEnabled && selectedProvider) {
                    modelConfigSection.style.display = 'block';
                    
                    // Show only the selected provider's model config
                    Object.keys(modelConfigs).forEach(provider => {
                        const config = modelConfigs[provider];
                        if (config) {
                            if (provider === selectedProvider) {
                                config.style.display = 'block';
                            } else {
                                config.style.display = 'none';
                            }
                        }
                    });
                } else {
                    modelConfigSection.style.display = 'none';
                }
            }

            // Initialize UI
            updateProviderUI();

            // Update on provider change
            providerSelect.addEventListener('change', updateProviderUI);
            
            // Update on enable/disable change
            enabledCheckbox.addEventListener('change', updateProviderUI);

            // Add form validation
            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                const selectedProvider = providerSelect.value;
                const isEnabled = enabledCheckbox.checked;
                
                if (isEnabled && selectedProvider) {
                    const apiKeyField = apiKeyFields[selectedProvider];
                    if (apiKeyField) {
                        const input = apiKeyField.querySelector('input');
                        if (input && !input.value.trim()) {
                            e.preventDefault();
                            alert('Please enter the API key for ' + selectedProvider.toUpperCase());
                            input.focus();
                            return;
                        }
                    }
                }
            });

            // Add smooth transitions
            function addTransition(element) {
                if (element) {
                    element.style.transition = 'all 0.3s ease-in-out';
                }
            }

                         // Add transitions to all dynamic elements
             [providerInfoSection, apiKeySection, modelConfigSection].forEach(addTransition);
             Object.values(providerInfos).forEach(addTransition);
             Object.values(apiKeyFields).forEach(addTransition);
             Object.values(modelConfigs).forEach(addTransition);
         });

         // API Key Validation Function
         async function validateApiKey(provider) {
             const input = document.getElementById(provider + '_key');
             const resultDiv = document.getElementById(provider + '-validation-result');
             const button = input.nextElementSibling;
             
             if (!input.value.trim()) {
                 showValidationResult(resultDiv, 'Tafadhali weka API key kwanza.', 'error');
                 return;
             }
             
             // Show loading state
             button.disabled = true;
             button.textContent = 'Validating...';
             showValidationResult(resultDiv, 'Inahakiki API key...', 'loading');
             
             try {
                 const response = await fetch('/admin/ai/validate-key', {
                     method: 'POST',
                     headers: {
                         'Content-Type': 'application/json',
                         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                     },
                     body: JSON.stringify({
                         provider: provider,
                         api_key: input.value.trim()
                     })
                 });
                 
                 const data = await response.json();
                 
                 if (data.success) {
                     showValidationResult(resultDiv, data.message, 'success');
                     // Clear the input after successful validation
                     input.value = '';
                 } else {
                     showValidationResult(resultDiv, data.message, 'error');
                 }
             } catch (error) {
                 showValidationResult(resultDiv, 'Hitilafu ya mtandao. Jaribu tena.', 'error');
             } finally {
                 // Reset button state
                 button.disabled = false;
                 button.textContent = 'Validate';
             }
         }
         
         function showValidationResult(element, message, type) {
             const colors = {
                 success: 'bg-green-100 border-green-400 text-green-700',
                 error: 'bg-red-100 border-red-400 text-red-700',
                 loading: 'bg-blue-100 border-blue-400 text-blue-700'
             };
             
             element.innerHTML = `
                 <div class="px-3 py-2 rounded-md border text-sm ${colors[type]}">
                     ${type === 'loading' ? '⏳ ' : type === 'success' ? '✅ ' : '❌ '}
                     ${message}
                 </div>
             `;
         }
     </script>
</x-app-layout>

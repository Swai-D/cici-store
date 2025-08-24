<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ __('AI Business Consultant') }}
                    </h2>
                    <p class="text-sm text-gray-600">Your intelligent business advisor</p>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                @can('manage_ai')
                    <a href="{{ route('admin.ai.edit') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        AI Settings
                    </a>
                @endcan
                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Back to Dashboard
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Section -->
            <div class="mb-8">
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-6">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-blue-900 mb-2">Karibu kwenye AI Business Consultant! 👋</h3>
                            <div class="text-blue-800 space-y-2">
                                <p class="text-sm">I'm your intelligent business advisor, ready to help you analyze your business performance and provide actionable insights in Kiswahili.</p>
                                <div class="flex flex-wrap gap-2 mt-3">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Product Analysis
                                    </span>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Sales Insights
                                    </span>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Cost Optimization
                                    </span>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Growth Strategy
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- AI Chat Widget -->
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                <!-- Main Chat Area -->
                <div class="lg:col-span-3">
                    <x-ai-chat-widget />
                </div>
                
                <!-- Quick Actions Sidebar -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 sticky top-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                        
                        <div class="space-y-3">
                            <button onclick="askQuestion('Bidhaa gani zina faida kubwa?')" 
                                    class="w-full text-left p-3 rounded-lg border border-gray-200 hover:border-blue-300 hover:bg-blue-50 transition-colors group">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Most Profitable Products</p>
                                        <p class="text-xs text-gray-500">Analyze product performance</p>
                                    </div>
                                </div>
                            </button>
                            
                            <button onclick="askQuestion('Gharama zipi zinaweza kupunguzwa?')" 
                                    class="w-full text-left p-3 rounded-lg border border-gray-200 hover:border-green-300 hover:bg-green-50 transition-colors group">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center group-hover:bg-green-200 transition-colors">
                                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Cost Optimization</p>
                                        <p class="text-xs text-gray-500">Reduce unnecessary expenses</p>
                                    </div>
                                </div>
                            </button>
                            
                            <button onclick="askQuestion('Mpango wa wiki ijayo uwe vipi?')" 
                                    class="w-full text-left p-3 rounded-lg border border-gray-200 hover:border-purple-300 hover:bg-purple-50 transition-colors group">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center group-hover:bg-purple-200 transition-colors">
                                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Weekly Plan</p>
                                        <p class="text-xs text-gray-500">Get actionable strategies</p>
                                    </div>
                                </div>
                            </button>
                            
                            <button onclick="askQuestion('Mauzo ya wiki hii yalikuwa vipi?')" 
                                    class="w-full text-left p-3 rounded-lg border border-gray-200 hover:border-orange-300 hover:bg-orange-50 transition-colors group">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center group-hover:bg-orange-200 transition-colors">
                                        <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Sales Analysis</p>
                                        <p class="text-xs text-gray-500">Review performance trends</p>
                                    </div>
                                </div>
                            </button>

                            <button onclick="askQuestion('Bidhaa zipi zinazosimama na zinahitaji promotion?')" 
                                    class="w-full text-left p-3 rounded-lg border border-gray-200 hover:border-red-300 hover:bg-red-50 transition-colors group">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center group-hover:bg-red-200 transition-colors">
                                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Slow Moving Items</p>
                                        <p class="text-xs text-gray-500">Identify stagnant products</p>
                                    </div>
                                </div>
                            </button>

                            <button onclick="askQuestion('Ninawezaje kuongeza mauzo na faida?')" 
                                    class="w-full text-left p-3 rounded-lg border border-gray-200 hover:border-indigo-300 hover:bg-indigo-50 transition-colors group">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center group-hover:bg-indigo-200 transition-colors">
                                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Growth Strategy</p>
                                        <p class="text-xs text-gray-500">Increase sales & profits</p>
                                    </div>
                                </div>
                            </button>
                        </div>
                        
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <h4 class="text-sm font-medium text-gray-900 mb-3">💡 Quick Tips</h4>
                            <div class="space-y-2">
                                <div class="text-xs text-gray-600 p-2 bg-gray-50 rounded cursor-pointer hover:bg-gray-100 transition-colors" 
                                     onclick="askQuestion('Inventory management na stock control vipi?')">
                                    📦 Ask about inventory management
                                </div>
                                <div class="text-xs text-gray-600 p-2 bg-gray-50 rounded cursor-pointer hover:bg-gray-100 transition-colors" 
                                     onclick="askQuestion('Bei za bidhaa zangu zinawezaje kuwa competitive?')">
                                    💰 Get pricing recommendations
                                </div>
                                <div class="text-xs text-gray-600 p-2 bg-gray-50 rounded cursor-pointer hover:bg-gray-100 transition-colors" 
                                     onclick="askQuestion('Customer behavior na preferences vipi?')">
                                    👥 Analyze customer behavior
                                </div>
                                <div class="text-xs text-gray-600 p-2 bg-gray-50 rounded cursor-pointer hover:bg-gray-100 transition-colors" 
                                     onclick="askQuestion('Supplier relationships na negotiations vipi?')">
                                    🤝 Supplier optimization
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 p-3 bg-blue-50 rounded-lg border border-blue-200">
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-xs text-blue-800 font-medium">Pro Tip</p>
                            </div>
                            <p class="text-xs text-blue-700 mt-1">
                                Bofya quick action yoyote kwa haraka kupata insights za biashara yako!
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Global function to ask questions from Quick Actions
        function askQuestion(question) {
            // Add visual feedback to the clicked button
            const clickedButton = event.target.closest('button');
            if (clickedButton) {
                // Add loading state
                const originalContent = clickedButton.innerHTML;
                clickedButton.innerHTML = `
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-600"></div>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Sending...</p>
                            <p class="text-xs text-gray-500">Processing your question</p>
                        </div>
                    </div>
                `;
                clickedButton.disabled = true;
                
                // Restore original content after 3 seconds
                setTimeout(() => {
                    clickedButton.innerHTML = originalContent;
                    clickedButton.disabled = false;
                }, 3000);
            }

            // Method 1: Try to use the global aiChatWidget reference
            if (window.aiChatWidget && window.aiChatWidget.sendQuestion) {
                try {
                    window.aiChatWidget.sendQuestion(question);
                    showSuccessMessage('Swali lilitumwa! AI inachakata...');
                    return;
                } catch (error) {
                    console.error('Error sending question:', error);
                    showErrorMessage('Hitilafu ya kutuma swali. Jaribu tena.');
                }
            }
            
            // Method 2: Find the Alpine.js component and call sendQuestion
            const chatWidget = document.querySelector('.ai-chat-widget');
            if (chatWidget && chatWidget.__x && chatWidget.__x.$data && chatWidget.__x.$data.sendQuestion) {
                try {
                    chatWidget.__x.$data.sendQuestion(question);
                    showSuccessMessage('Swali lilitumwa! AI inachakata...');
                    return;
                } catch (error) {
                    console.error('Error sending question:', error);
                    showErrorMessage('Hitilafu ya kutuma swali. Jaribu tena.');
                }
            }
            
            // Method 3: Direct DOM manipulation as fallback
            const input = document.querySelector('input[placeholder*="Uliza"]');
            const sendButton = document.querySelector('button svg[stroke-linecap="round"]');
            
            if (input && sendButton) {
                try {
                    input.value = question;
                    input.dispatchEvent(new Event('input', { bubbles: true }));
                    sendButton.closest('button').click();
                    showSuccessMessage('Swali lilitumwa! AI inachakata...');
                    return;
                } catch (error) {
                    console.error('Error with direct DOM manipulation:', error);
                    showErrorMessage('Hitilafu ya kutuma swali. Jaribu tena.');
                }
            }
            
            // Method 4: Show error message
            console.error('AI chat widget not found or not ready');
            showErrorMessage('Tafadhali subiri kidogo, AI chat widget haijafunguliwa bado. Jaribu tena.');
        }

        // Add click event listeners as a backup
        document.addEventListener('DOMContentLoaded', function() {
            // Add click handlers to all quick action buttons
            const quickActionButtons = document.querySelectorAll('button[onclick*="askQuestion"]');
            
            quickActionButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Extract the question from the onclick attribute
                    const onclick = this.getAttribute('onclick');
                    const match = onclick.match(/askQuestion\('([^']+)'\)/);
                    
                    if (match) {
                        const question = match[1];
                        
                        // Try to send the question
                        setTimeout(() => {
                            askQuestion(question);
                        }, 100);
                    }
                });
            });

            // Add click handlers to quick tips
            const quickTips = document.querySelectorAll('.text-xs.text-gray-600.p-2.bg-gray-50.rounded.cursor-pointer');
            quickTips.forEach(tip => {
                tip.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Add visual feedback
                    const originalContent = this.innerHTML;
                    this.innerHTML = '⏳ Sending...';
                    this.style.backgroundColor = '#dbeafe';
                    
                    // Extract the question from the onclick attribute
                    const onclick = this.getAttribute('onclick');
                    const match = onclick.match(/askQuestion\('([^']+)'\)/);
                    
                    if (match) {
                        const question = match[1];
                        
                        setTimeout(() => {
                            askQuestion(question);
                            
                            // Restore original content after 2 seconds
                            setTimeout(() => {
                                this.innerHTML = originalContent;
                                this.style.backgroundColor = '#f9fafb';
                            }, 2000);
                        }, 100);
                    }
                });
            });
        });

        // Wait for Alpine.js to be ready and then set up the global function
        document.addEventListener('alpine:init', () => {
            // Override the global askQuestion function to use the Alpine component
            window.askQuestion = function(question) {
                // Try to use the global aiChatWidget reference first
                if (window.aiChatWidget && window.aiChatWidget.sendQuestion) {
                    try {
                        window.aiChatWidget.sendQuestion(question);
                        showSuccessMessage('Swali lilitumwa! AI inachakata...');
                        return;
                    } catch (error) {
                        console.error('Error sending question:', error);
                        showErrorMessage('Hitilafu ya kutuma swali. Jaribu tena.');
                    }
                }
                
                // Fallback to finding the component
                const chatWidget = document.querySelector('.ai-chat-widget');
                if (chatWidget && chatWidget.__x && chatWidget.__x.$data && chatWidget.__x.$data.sendQuestion) {
                    try {
                        chatWidget.__x.$data.sendQuestion(question);
                        showSuccessMessage('Swali lilitumwa! AI inachakata...');
                        return;
                    } catch (error) {
                        console.error('Error sending question:', error);
                        showErrorMessage('Hitilafu ya kutuma swali. Jaribu tena.');
                    }
                }
                
                // Final fallback
                console.error('AI chat widget not ready');
                showErrorMessage('Tafadhali subiri kidogo, AI chat widget haijafunguliwa bado.');
            };
        });

        // Add success notification function
        function showSuccessMessage(message) {
            // Create a temporary success notification
            const notification = document.createElement('div');
            notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50 transition-all duration-300 transform translate-x-full';
            notification.innerHTML = `
                <div class="flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>${message}</span>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            // Animate in
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);
            
            // Remove after 3 seconds
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 300);
            }, 3000);
        }

        // Add error notification function
        function showErrorMessage(message) {
            // Create a temporary error notification
            const notification = document.createElement('div');
            notification.className = 'fixed top-4 right-4 bg-red-500 text-white px-4 py-2 rounded-lg shadow-lg z-50 transition-all duration-300 transform translate-x-full';
            notification.innerHTML = `
                <div class="flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    <span>${message}</span>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            // Animate in
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);
            
            // Remove after 4 seconds
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 300);
            }, 4000);
        }
    </script>
</x-app-layout>

@props(['class' => ''])

<div class="ai-chat-widget {{ $class }}" x-data="aiChatWidget">
    <div class="bg-white rounded-xl shadow-xl border border-gray-200 overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 via-blue-700 to-indigo-700 text-white p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold">AI Business Consultant</h2>
                        <p class="text-blue-100 text-sm">Your friendly business advisor</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <select 
                        x-model="range" 
                        class="bg-white/20 text-white text-sm rounded-lg px-3 py-2 border border-white/30 backdrop-blur-sm focus:outline-none focus:ring-2 focus:ring-white/50"
                    >
                        <option value="this_week">This Week</option>
                        <option value="last_30_days">Last 30 Days</option>
                        <option value="last_90_days">Last 90 Days</option>
                        <option value="this_month">This Month</option>
                    </select>
                    <button 
                        @click="clearChat()"
                        class="text-blue-100 hover:text-white text-sm px-3 py-2 rounded-lg hover:bg-white/10 transition-colors"
                        title="Clear conversation"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Messages -->
        <div class="h-96 overflow-y-auto p-6 space-y-4 bg-gradient-to-b from-gray-50 to-white">
            <template x-if="messages.length === 0">
                <div class="text-center text-gray-500 py-12">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Karibu! 👋</h3>
                    <p class="text-sm text-gray-600 mb-4">I'm here to help you grow your business!</p>
                    <div class="space-y-2">
                        <p class="text-xs text-gray-500">Try asking me about:</p>
                        <div class="flex flex-wrap gap-2 justify-center">
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs rounded-full">Bidhaa gani zina faida kubwa?</span>
                            <span class="px-3 py-1 bg-green-100 text-green-700 text-xs rounded-full">Gharama zipunguzweje?</span>
                            <span class="px-3 py-1 bg-purple-100 text-purple-700 text-xs rounded-full">Mpango wa wiki ijayo?</span>
                        </div>
                    </div>
                </div>
            </template>
            
            <template x-for="(message, index) in messages" :key="index">
                <div :class="message.role === 'user' ? 'flex justify-end' : 'flex justify-start'">
                    <div :class="message.role === 'user' 
                        ? 'bg-gradient-to-r from-blue-600 to-blue-700 text-white' 
                        : 'bg-white text-gray-800 border border-gray-200 shadow-sm'"
                        class="max-w-xs lg:max-w-md px-4 py-3 rounded-2xl leading-relaxed">
                        <div class="text-sm whitespace-pre-wrap max-w-none space-y-2" x-html="formatMessage(message.content)"></div>
                    </div>
                </div>
            </template>
            
            <template x-if="loading">
                <div class="flex justify-start">
                    <div class="bg-white text-gray-800 border border-gray-200 shadow-sm px-4 py-3 rounded-2xl">
                        <div class="flex items-center space-x-3">
                            <div class="flex space-x-1">
                                <div class="w-2 h-2 bg-blue-600 rounded-full animate-bounce"></div>
                                <div class="w-2 h-2 bg-blue-600 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                                <div class="w-2 h-2 bg-blue-600 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                            </div>
                            <span class="text-sm text-gray-600">AI inachakata...</span>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Input -->
        <div class="p-6 border-t border-gray-200 bg-white">
            <div class="flex space-x-3">
                <div class="flex-1 relative">
                    <input
                        x-model="input"
                        @keydown.enter="send()"
                        placeholder="Uliza: bidhaa gani tuongeze wiki hii? Au gharama zipunguzweje?"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                        :disabled="loading"
                    />
                    <div class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
                <button 
                    @click="send()"
                    :disabled="loading || !input.trim()"
                    class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 disabled:from-gray-400 disabled:to-gray-500 text-white px-6 py-3 rounded-xl font-medium transition-all duration-200 transform hover:scale-105 disabled:transform-none disabled:cursor-not-allowed shadow-lg hover:shadow-xl"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                </button>
            </div>
            <div class="mt-3 flex items-center justify-between text-xs text-gray-500">
                <span>Press Enter to send</span>
                <span x-text="input.length + '/4000'"></span>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('aiChatWidget', () => ({
        messages: [],
        input: '',
        loading: false,
        range: 'this_week',
        sessionId: null,

        init() {
            // Generate or retrieve session ID for conversation continuity
            this.sessionId = this.getSessionId();
            
            // Load any existing messages from session storage
            this.loadMessagesFromStorage();
            
            // Expose the send function globally for Quick Actions
            window.aiChatWidget = this;
        },

        getSessionId() {
            let sessionId = localStorage.getItem('ai_chat_session_id');
            if (!sessionId) {
                sessionId = 'session_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
                localStorage.setItem('ai_chat_session_id', sessionId);
            }
            return sessionId;
        },

        loadMessagesFromStorage() {
            const storedMessages = localStorage.getItem('ai_chat_messages_' + this.sessionId);
            if (storedMessages) {
                try {
                    this.messages = JSON.parse(storedMessages);
                } catch (e) {
                    console.error('Failed to load messages from storage:', e);
                }
            }
        },

        saveMessagesToStorage() {
            try {
                localStorage.setItem('ai_chat_messages_' + this.sessionId, JSON.stringify(this.messages));
            } catch (e) {
                console.error('Failed to save messages to storage:', e);
            }
        },

        async send() {
            if (!this.input.trim() || this.loading) return;
            
            const question = this.input.trim();
            this.messages.push({ role: 'user', content: question });
            this.input = '';
            this.loading = true;
            
            // Save messages to storage
            this.saveMessagesToStorage();
            
            try {
                const response = await fetch('/ai-chat/send', {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ 
                        message: question, 
                        range: this.range,
                        from: 'admin',
                        session_id: this.sessionId
                    })
                });
                
                const data = await response.json();
                
                if (data.error) {
                    this.messages.push({ 
                        role: 'assistant', 
                        content: `⚠️ ${data.error}` 
                    });
                } else {
                    this.messages.push({ 
                        role: 'assistant', 
                        content: data.reply || 'Samahani, sijapata majibu kwa sasa.' 
                    });
                }
            } catch (e) {
                console.error('AI Chat Error:', e);
                this.messages.push({ 
                    role: 'assistant', 
                    content: '⚠️ Hitilafu ya mtandao/AI. Jaribu tena.' 
                });
            } finally {
                this.loading = false;
                // Save messages to storage
                this.saveMessagesToStorage();
                // Scroll to bottom after response
                this.$nextTick(() => {
                    const container = this.$el.querySelector('.overflow-y-auto');
                    if (container) {
                        container.scrollTop = container.scrollHeight;
                    }
                });
            }
        },

        // Method to send a question from Quick Actions
        sendQuestion(question) {
            if (this.loading) return;
            
            this.input = question;
            this.send();
            
            // Show success notification
            this.showNotification('Swali lilitumwa! AI inachakata...', 'success');
        },

        // Add notification method
        showNotification(message, type = 'info') {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 px-4 py-2 rounded-lg shadow-lg z-50 transition-all duration-300 transform translate-x-full`;
            
            const colors = {
                success: 'bg-green-500 text-white',
                error: 'bg-red-500 text-white',
                info: 'bg-blue-500 text-white',
                warning: 'bg-yellow-500 text-white'
            };
            
            notification.className += ` ${colors[type] || colors.info}`;
            
            notification.innerHTML = `
                <div class="flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span class="text-sm font-medium">${message}</span>
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
        },

        clearChat() {
            this.messages = [];
            // Clear from storage
            localStorage.removeItem('ai_chat_messages_' + this.sessionId);
            // Generate new session ID
            this.sessionId = this.getSessionId();
        },

        formatMessage(content) {
            // Convert markdown-style formatting to HTML with better styling
            return content
                .replace(/### (.*)/g, '<h3 class="text-lg font-bold text-gray-900 mt-4 mb-3 border-b border-gray-200 pb-2">$1</h3>')
                .replace(/#### (.*)/g, '<h4 class="text-md font-semibold text-gray-800 mt-3 mb-2">$1</h4>')
                .replace(/\*\*(.*?)\*\*/g, '<strong class="font-semibold text-gray-900">$1</strong>')
                .replace(/\*(.*?)\*/g, '<em class="italic text-gray-700">$1</em>')
                .replace(/^- (.*)/gm, '<li class="ml-4 mb-2 flex items-start"><span class="text-blue-600 mr-2">•</span><span>$1</span></li>')
                .replace(/(\d+\.) (.*)/g, '<li class="ml-4 mb-2 flex items-start"><span class="text-blue-600 mr-2 font-medium">$1</span><span>$2</span></li>')
                .replace(/(<li.*<\/li>)/s, '<ul class="list-none space-y-2 my-3">$1</ul>')
                .replace(/\n\n/g, '<br><br>')
                .replace(/\n/g, '<br>');
        }
    }));
});
</script>

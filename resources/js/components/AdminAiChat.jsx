import React, { useState, useRef, useEffect } from 'react';

export default function AdminAiChat() {
    const [messages, setMessages] = useState([]);
    const [input, setInput] = useState('');
    const [loading, setLoading] = useState(false);
    const [range, setRange] = useState('this_week');
    const messagesEndRef = useRef(null);

    const scrollToBottom = () => {
        messagesEndRef.current?.scrollIntoView({ behavior: "smooth" });
    };

    useEffect(() => {
        scrollToBottom();
    }, [messages]);

    const send = async () => {
        if (!input.trim() || loading) return;
        
        const question = input.trim();
        setMessages(m => [...m, { role: 'user', content: question }]);
        setInput('');
        setLoading(true);
        
        try {
            const res = await fetch('/ai-chat/send', {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ 
                    message: question, 
                    range: range,
                    from: 'admin'
                })
            });
            
            const data = await res.json();
            
            if (data.error) {
                setMessages(m => [...m, { 
                    role: 'assistant', 
                    content: `⚠️ ${data.error}` 
                }]);
            } else {
                setMessages(m => [...m, { 
                    role: 'assistant', 
                    content: data.reply || 'Samahani, sijapata majibu kwa sasa.' 
                }]);
            }
        } catch (e) {
            console.error('AI Chat Error:', e);
            setMessages(m => [...m, { 
                role: 'assistant', 
                content: '⚠️ Hitilafu ya mtandao. Hakikisha una internet connection na jaribu tena.' 
            }]);
        } finally {
            setLoading(false);
        }
    };

    const handleKeyDown = (e) => {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            send();
        }
    };

    const clearChat = () => {
        setMessages([]);
    };

    return (
        <div className="max-w-4xl mx-auto bg-white rounded-lg shadow-lg border">
            {/* Header */}
            <div className="bg-gradient-to-r from-blue-600 to-blue-700 text-white p-4 rounded-t-lg">
                <div className="flex items-center justify-between">
                    <div>
                        <h2 className="text-xl font-bold">AI Business Consultant</h2>
                        <p className="text-blue-100 text-sm">Ask about your business performance</p>
                    </div>
                    <div className="flex items-center space-x-3">
                        <select 
                            value={range} 
                            onChange={(e) => setRange(e.target.value)}
                            className="bg-blue-500 text-white text-sm rounded px-2 py-1 border border-blue-400"
                        >
                            <option value="this_week">This Week</option>
                            <option value="last_30_days">Last 30 Days</option>
                            <option value="last_90_days">Last 90 Days</option>
                            <option value="this_month">This Month</option>
                        </select>
                        <button 
                            onClick={clearChat}
                            className="text-blue-100 hover:text-white text-sm"
                        >
                            Clear
                        </button>
                    </div>
                </div>
            </div>

            {/* Messages */}
            <div className="h-96 overflow-y-auto p-4 space-y-4 bg-gray-50">
                {messages.length === 0 && (
                    <div className="text-center text-gray-500 py-8">
                        <div className="text-4xl mb-2">🤖</div>
                        <p className="text-sm">Ask me about your business performance!</p>
                        <p className="text-xs text-gray-400 mt-1">
                            Try: "Which products are most profitable?" or "How can I reduce expenses?"
                        </p>
                    </div>
                )}
                
                {messages.map((message, index) => (
                    <div key={index} className={`flex ${message.role === 'user' ? 'justify-end' : 'justify-start'}`}>
                        <div className={`max-w-xs lg:max-w-md px-4 py-2 rounded-lg ${
                            message.role === 'user' 
                                ? 'bg-blue-600 text-white' 
                                : 'bg-white text-gray-800 border shadow-sm'
                        }`}>
                            <div className="text-sm whitespace-pre-wrap">{message.content}</div>
                        </div>
                    </div>
                ))}
                
                {loading && (
                    <div className="flex justify-start">
                        <div className="bg-white text-gray-800 border shadow-sm px-4 py-2 rounded-lg">
                            <div className="flex items-center space-x-2">
                                <div className="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-600"></div>
                                <span className="text-sm">AI inachakata...</span>
                            </div>
                        </div>
                    </div>
                )}
                
                <div ref={messagesEndRef} />
            </div>

            {/* Input */}
            <div className="p-4 border-t bg-white rounded-b-lg">
                <div className="flex space-x-2">
                    <input
                        value={input}
                        onChange={(e) => setInput(e.target.value)}
                        onKeyDown={handleKeyDown}
                        placeholder="Uliza: bidhaa gani tuongeze wiki hii? Au gharama zipunguzweje?"
                        className="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        disabled={loading}
                    />
                    <button 
                        onClick={send}
                        disabled={loading || !input.trim()}
                        className="bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 text-white px-4 py-2 rounded-md font-medium transition-colors"
                    >
                        Tuma
                    </button>
                </div>
            </div>
        </div>
    );
}

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Record New Sale') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('web.sales.store') }}" method="POST">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Product Selection -->
                            <div>
                                <label for="product_search" class="block text-sm font-medium text-gray-700">Product *</label>
                                <div class="relative">
                                    <input type="text" id="product_search" 
                                           placeholder="Search for a product..."
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                           autocomplete="off">
                                    <input type="hidden" name="product_id" id="product_id" required>
                                    <div id="product_suggestions" class="absolute z-50 w-full bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-y-auto hidden">
                                    </div>
                                </div>
                                @error('product_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Quantity -->
                            <div>
                                <label for="quantity_sold" class="block text-sm font-medium text-gray-700">Quantity *</label>
                                <input type="number" name="quantity_sold" id="quantity_sold" value="{{ old('quantity_sold', 1) }}" min="1" required
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                @error('quantity_sold')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Sale Price -->
                            <div>
                                <label for="sale_price" class="block text-sm font-medium text-gray-700">Sale Price (Tsh) *</label>
                                <input type="number" name="sale_price" id="sale_price" value="{{ old('sale_price') }}" step="0.01" min="0" required
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                @error('sale_price')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Payment Method -->
                            <div>
                                <label for="payment_method" class="block text-sm font-medium text-gray-700">Payment Method *</label>
                                <select name="payment_method" id="payment_method" required
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">Select Payment Method</option>
                                    <option value="Cash" {{ old('payment_method') == 'Cash' ? 'selected' : '' }}>Cash</option>
                                    <option value="M-Pesa" {{ old('payment_method') == 'M-Pesa' ? 'selected' : '' }}>M-Pesa</option>
                                    <option value="TigoPesa" {{ old('payment_method') == 'TigoPesa' ? 'selected' : '' }}>TigoPesa</option>
                                    <option value="Bank" {{ old('payment_method') == 'Bank' ? 'selected' : '' }}>Bank</option>
                                </select>
                                @error('payment_method')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Customer Phone -->
                            <div>
                                <label for="customer_phone" class="block text-sm font-medium text-gray-700">Customer Phone</label>
                                <input type="text" name="customer_phone" id="customer_phone" value="{{ old('customer_phone') }}" 
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                       placeholder="+255 744 123 456">
                                @error('customer_phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Total Price (Read-only) -->
                            <div>
                                <label for="total_price" class="block text-sm font-medium text-gray-700">Total Price (Tsh)</label>
                                <input type="text" id="total_price" readonly
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-50 sm:text-sm"
                                       value="0">
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="mt-6 flex justify-end space-x-3">
                            <a href="{{ route('web.sales.index') }}" 
                               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('Cancel') }}
                            </a>
                            <button type="submit" 
                                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Record Sale
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Product search functionality
        const productSearch = document.getElementById('product_search');
        const productId = document.getElementById('product_id');
        const suggestions = document.getElementById('product_suggestions');
        let searchTimeout;

        productSearch.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value.trim();
            
            if (query.length < 2) {
                suggestions.classList.add('hidden');
                return;
            }

            searchTimeout = setTimeout(() => {
                searchProducts(query);
            }, 300);
        });

        // Hide suggestions when clicking outside
        document.addEventListener('click', function(e) {
            if (!productSearch.contains(e.target) && !suggestions.contains(e.target)) {
                suggestions.classList.add('hidden');
            }
        });

        function searchProducts(query) {
            fetch(`/api/products/search?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    displaySuggestions(data);
                })
                .catch(error => {
                    console.error('Error searching products:', error);
                });
        }

        function displaySuggestions(products) {
            suggestions.innerHTML = '';
            
            if (products.length === 0) {
                suggestions.innerHTML = '<div class="p-3 text-gray-500">No products found</div>';
                suggestions.classList.remove('hidden');
                return;
            }

            products.forEach(product => {
                const div = document.createElement('div');
                div.className = 'p-3 hover:bg-gray-100 cursor-pointer border-b border-gray-200 last:border-b-0';
                div.innerHTML = `
                    <div class="font-medium text-gray-900">${product.text}</div>
                    <div class="text-sm text-gray-500">Price: Tsh ${product.price.toLocaleString()}</div>
                `;
                
                div.addEventListener('click', () => {
                    selectProduct(product);
                });
                
                suggestions.appendChild(div);
            });
            
            suggestions.classList.remove('hidden');
        }

        function selectProduct(product) {
            productSearch.value = product.text;
            productId.value = product.id;
            document.getElementById('sale_price').value = product.price;
            suggestions.classList.add('hidden');
            updateTotalPrice();
        }

        // Auto-calculate total price
        document.getElementById('quantity_sold').addEventListener('input', function() {
            updateTotalPrice();
        });

        document.getElementById('sale_price').addEventListener('input', function() {
            updateTotalPrice();
        });

        function updateTotalPrice() {
            const quantity = parseFloat(document.getElementById('quantity_sold').value) || 0;
            const price = parseFloat(document.getElementById('sale_price').value) || 0;
            const total = quantity * price;
            document.getElementById('total_price').value = total.toLocaleString();
        }

        // Keyboard navigation for suggestions
        productSearch.addEventListener('keydown', function(e) {
            const visibleSuggestions = suggestions.querySelectorAll('div');
            const currentIndex = Array.from(visibleSuggestions).findIndex(div => div.classList.contains('bg-blue-100'));
            
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                const nextIndex = currentIndex < visibleSuggestions.length - 1 ? currentIndex + 1 : 0;
                highlightSuggestion(visibleSuggestions, nextIndex);
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                const prevIndex = currentIndex > 0 ? currentIndex - 1 : visibleSuggestions.length - 1;
                highlightSuggestion(visibleSuggestions, prevIndex);
            } else if (e.key === 'Enter') {
                e.preventDefault();
                if (currentIndex >= 0 && visibleSuggestions[currentIndex]) {
                    visibleSuggestions[currentIndex].click();
                }
            } else if (e.key === 'Escape') {
                suggestions.classList.add('hidden');
            }
        });

        function highlightSuggestion(suggestions, index) {
            suggestions.forEach((div, i) => {
                if (i === index) {
                    div.classList.add('bg-blue-100');
                } else {
                    div.classList.remove('bg-blue-100');
                }
            });
        }
    </script>
</x-app-layout> 
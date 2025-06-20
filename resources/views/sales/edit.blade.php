<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Sale') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('sales.update', $sale) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Product Selection -->
                            <div>
                                <label for="product_id" class="block text-sm font-medium text-gray-700">Product *</label>
                                <select name="product_id" id="product_id" required
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">Select Product</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" 
                                                data-price="{{ $product->selling_price }}"
                                                data-stock="{{ $product->stock_quantity }}"
                                                {{ old('product_id', $sale->product_id) == $product->id ? 'selected' : '' }}>
                                            {{ $product->name }} - {{ $product->product_code }} (Stock: {{ $product->stock_quantity }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('product_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Quantity -->
                            <div>
                                <label for="quantity_sold" class="block text-sm font-medium text-gray-700">Quantity *</label>
                                <input type="number" name="quantity_sold" id="quantity_sold" value="{{ old('quantity_sold', $sale->quantity_sold) }}" min="1" required
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                @error('quantity_sold')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Sale Price -->
                            <div>
                                <label for="sale_price" class="block text-sm font-medium text-gray-700">Sale Price (Tsh) *</label>
                                <input type="number" name="sale_price" id="sale_price" value="{{ old('sale_price', $sale->sale_price) }}" step="0.01" min="0" required
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
                                    <option value="Cash" {{ old('payment_method', $sale->payment_method) == 'Cash' ? 'selected' : '' }}>Cash</option>
                                    <option value="M-Pesa" {{ old('payment_method', $sale->payment_method) == 'M-Pesa' ? 'selected' : '' }}>M-Pesa</option>
                                    <option value="TigoPesa" {{ old('payment_method', $sale->payment_method) == 'TigoPesa' ? 'selected' : '' }}>TigoPesa</option>
                                    <option value="Bank" {{ old('payment_method', $sale->payment_method) == 'Bank' ? 'selected' : '' }}>Bank</option>
                                </select>
                                @error('payment_method')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Customer Phone -->
                            <div>
                                <label for="customer_phone" class="block text-sm font-medium text-gray-700">Customer Phone</label>
                                <input type="text" name="customer_phone" id="customer_phone" value="{{ old('customer_phone', $sale->customer_phone) }}" 
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
                                       value="{{ number_format($sale->quantity_sold * $sale->sale_price) }}">
                            </div>
                        </div>

                        <!-- Current Sale Info -->
                        <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                            <h4 class="text-sm font-medium text-blue-900 mb-2">Current Sale Information</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                <div>
                                    <span class="text-blue-700">Transaction Code:</span>
                                    <span class="font-medium">{{ $sale->transaction_code }}</span>
                                </div>
                                <div>
                                    <span class="text-blue-700">Sale Date:</span>
                                    <span class="font-medium">{{ $sale->sale_time->format('M d, Y H:i') }}</span>
                                </div>
                                <div>
                                    <span class="text-blue-700">Current Total:</span>
                                    <span class="font-medium">Tsh {{ number_format($sale->total_price) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="mt-6 flex justify-end space-x-3">
                            <a href="{{ route('sales.show', $sale) }}" 
                               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Update Sale
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-calculate total price
        document.getElementById('product_id').addEventListener('change', function() {
            updateTotalPrice();
        });

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

        // Auto-fill sale price when product is selected
        document.getElementById('product_id').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption && selectedOption.dataset.price) {
                document.getElementById('sale_price').value = selectedOption.dataset.price;
                updateTotalPrice();
            }
        });

        // Initialize total price on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateTotalPrice();
        });
    </script>
</x-app-layout> 
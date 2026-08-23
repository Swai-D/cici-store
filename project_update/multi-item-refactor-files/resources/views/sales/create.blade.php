<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Record New Sale') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if ($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('web.sales.store') }}" method="POST" id="sale-form">
                @csrf
                <div id="items-container"></div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Product picker + cart -->
                    <div class="lg:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <label for="product_search" class="block text-sm font-medium text-gray-700">Ongeza Bidhaa</label>
                            <div class="relative">
                                <input type="text" id="product_search"
                                       placeholder="Tafuta bidhaa (jina au product code)..."
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                       autocomplete="off">
                                <div id="product_suggestions" class="absolute z-50 w-full bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-y-auto hidden"></div>
                            </div>

                            <table class="min-w-full divide-y divide-gray-200 mt-6" id="cart-table">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Bidhaa</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase w-24">Qty</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase w-32">Bei</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase w-32">Jumla</th>
                                        <th class="px-4 py-2 w-10"></th>
                                    </tr>
                                </thead>
                                <tbody id="cart-body" class="bg-white divide-y divide-gray-200">
                                    <tr id="cart-empty-row">
                                        <td colspan="5" class="px-4 py-6 text-center text-sm text-gray-400">Bado hujaongeza bidhaa yoyote.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Order summary -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg h-fit">
                        <div class="p-6 space-y-4">
                            <div>
                                <label for="payment_method" class="block text-sm font-medium text-gray-700">Payment Method *</label>
                                <select name="payment_method" id="payment_method" required
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">Chagua...</option>
                                    <option value="Cash" {{ old('payment_method') == 'Cash' ? 'selected' : '' }}>Cash</option>
                                    <option value="M-Pesa" {{ old('payment_method') == 'M-Pesa' ? 'selected' : '' }}>M-Pesa</option>
                                    <option value="TigoPesa" {{ old('payment_method') == 'TigoPesa' ? 'selected' : '' }}>TigoPesa</option>
                                    <option value="Airtel Money" {{ old('payment_method') == 'Airtel Money' ? 'selected' : '' }}>Airtel Money</option>
                                    <option value="Bank" {{ old('payment_method') == 'Bank' ? 'selected' : '' }}>Bank</option>
                                    <option value="Credit Card" {{ old('payment_method') == 'Credit Card' ? 'selected' : '' }}>Credit Card</option>
                                </select>
                                @error('payment_method')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="customer_phone" class="block text-sm font-medium text-gray-700">Namba ya Mteja</label>
                                <input type="text" name="customer_phone" id="customer_phone" value="{{ old('customer_phone') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                       placeholder="+255 744 123 456">
                            </div>

                            <div>
                                <label for="discount_amount" class="block text-sm font-medium text-gray-700">Punguzo (Tsh)</label>
                                <input type="number" name="discount_amount" id="discount_amount" value="{{ old('discount_amount', 0) }}" min="0" step="0.01"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>

                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700">Maelezo (si lazima)</label>
                                <textarea name="notes" id="notes" rows="2"
                                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ old('notes') }}</textarea>
                            </div>

                            <div class="border-t pt-4 space-y-1 text-sm">
                                <div class="flex justify-between"><span>Subtotal</span><span id="summary-subtotal">Tsh 0</span></div>
                                <div class="flex justify-between"><span>Punguzo</span><span id="summary-discount">Tsh 0</span></div>
                                <div class="flex justify-between font-bold text-base border-t pt-2"><span>Jumla</span><span id="summary-total">Tsh 0</span></div>
                            </div>

                            <button type="submit" id="submit-btn" disabled
                                    class="w-full bg-green-500 hover:bg-green-700 disabled:opacity-50 disabled:cursor-not-allowed text-white font-bold py-2 px-4 rounded">
                                Hifadhi Mauzo
                            </button>
                            <a href="{{ route('web.sales.index') }}"
                               class="block text-center text-sm text-gray-500 hover:text-gray-700">Ghairi</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        let cart = []; // { product_id, name, code, price, stock, quantity }

        const productSearch = document.getElementById('product_search');
        const suggestions = document.getElementById('product_suggestions');
        let searchTimeout;

        productSearch.addEventListener('input', function () {
            clearTimeout(searchTimeout);
            const query = this.value.trim();
            if (query.length < 2) { suggestions.classList.add('hidden'); return; }
            searchTimeout = setTimeout(() => searchProducts(query), 300);
        });

        document.addEventListener('click', function (e) {
            if (!productSearch.contains(e.target) && !suggestions.contains(e.target)) {
                suggestions.classList.add('hidden');
            }
        });

        function searchProducts(query) {
            fetch(`/api/products/search?q=${encodeURIComponent(query)}`)
                .then(r => r.json())
                .then(displaySuggestions)
                .catch(err => console.error('Error searching products:', err));
        }

        function displaySuggestions(products) {
            suggestions.innerHTML = '';
            if (products.length === 0) {
                suggestions.innerHTML = '<div class="p-3 text-gray-500">Hakuna bidhaa iliyopatikana</div>';
                suggestions.classList.remove('hidden');
                return;
            }
            products.forEach(product => {
                const div = document.createElement('div');
                div.className = 'p-3 hover:bg-gray-100 cursor-pointer border-b border-gray-200 last:border-b-0';
                div.innerHTML = `<div class="font-medium text-gray-900">${product.text}</div>
                    <div class="text-sm text-gray-500">Bei: Tsh ${Number(product.price).toLocaleString()}</div>`;
                div.addEventListener('click', () => addToCart(product));
                suggestions.appendChild(div);
            });
            suggestions.classList.remove('hidden');
        }

        function addToCart(product) {
            const existing = cart.find(i => i.product_id === product.id);
            if (existing) {
                if (existing.quantity < product.stock) {
                    existing.quantity += 1;
                } else {
                    alert('Stock haitoshi kwa bidhaa hii.');
                }
            } else {
                cart.push({
                    product_id: product.id,
                    name: product.text,
                    unit: product.unit,
                    price: parseFloat(product.price),
                    stock: product.stock,
                    quantity: 1,
                });
            }
            productSearch.value = '';
            suggestions.classList.add('hidden');
            renderCart();
        }

        function removeFromCart(index) {
            cart.splice(index, 1);
            renderCart();
        }

        function updateQuantity(index, value) {
            const qty = parseFloat(value) || 0;
            if (qty <= 0) { removeFromCart(index); return; }
            if (qty > cart[index].stock) {
                alert('Stock haitoshi. Iliyopo: ' + cart[index].stock);
                cart[index].quantity = cart[index].stock;
            } else {
                cart[index].quantity = qty;
            }
            renderCart();
        }

        function updatePrice(index, value) {
            cart[index].price = parseFloat(value) || 0;
            renderCart();
        }

        function renderCart() {
            const body = document.getElementById('cart-body');
            const emptyRow = document.getElementById('cart-empty-row');

            body.innerHTML = '';
            if (cart.length === 0) {
                body.appendChild(emptyRow);
            } else {
                cart.forEach((item, index) => {
                    const lineTotal = item.quantity * item.price;
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td class="px-4 py-2 text-sm text-gray-900">${item.name}</td>
                        <td class="px-4 py-2">
                            <div class="flex items-center gap-1">
                                <input type="number" min="0.01" step="0.01" max="${item.stock}" value="${item.quantity}"
                                       class="w-20 border-gray-300 rounded-md shadow-sm sm:text-sm"
                                       onchange="updateQuantity(${index}, this.value)">
                                <span class="text-xs text-gray-500">${item.unit || 'kipande'}</span>
                            </div>
                        </td>
                        <td class="px-4 py-2">
                            <input type="number" min="0" step="0.01" value="${item.price}"
                                   class="w-28 border-gray-300 rounded-md shadow-sm sm:text-sm"
                                   onchange="updatePrice(${index}, this.value)">
                        </td>
                        <td class="px-4 py-2 text-sm text-gray-900">Tsh ${lineTotal.toLocaleString()}</td>
                        <td class="px-4 py-2 text-right">
                            <button type="button" onclick="removeFromCart(${index})" class="text-red-500 hover:text-red-700 text-sm">&times;</button>
                        </td>
                    `;
                    body.appendChild(tr);
                });
            }

            updateSummary();
            syncHiddenInputs();
        }

        function updateSummary() {
            const subtotal = cart.reduce((sum, i) => sum + (i.quantity * i.price), 0);
            const discount = parseFloat(document.getElementById('discount_amount').value) || 0;
            const total = Math.max(subtotal - discount, 0);

            document.getElementById('summary-subtotal').textContent = 'Tsh ' + subtotal.toLocaleString();
            document.getElementById('summary-discount').textContent = 'Tsh ' + discount.toLocaleString();
            document.getElementById('summary-total').textContent = 'Tsh ' + total.toLocaleString();

            document.getElementById('submit-btn').disabled = cart.length === 0;
        }

        function syncHiddenInputs() {
            const container = document.getElementById('items-container');
            container.innerHTML = '';
            cart.forEach((item, index) => {
                container.innerHTML += `
                    <input type="hidden" name="items[${index}][product_id]" value="${item.product_id}">
                    <input type="hidden" name="items[${index}][quantity]" value="${item.quantity}">
                    <input type="hidden" name="items[${index}][unit_price]" value="${item.price}">
                `;
            });
        }

        document.getElementById('discount_amount').addEventListener('input', updateSummary);

        document.getElementById('sale-form').addEventListener('submit', function (e) {
            if (cart.length === 0) {
                e.preventDefault();
                alert('Ongeza angalau bidhaa moja kabla ya kuhifadhi mauzo.');
            }
        });
    </script>
</x-app-layout>

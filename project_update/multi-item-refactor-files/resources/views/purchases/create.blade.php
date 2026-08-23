<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Rekodi Stock-In (Manunuzi)') }}
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

            <form action="{{ route('web.purchases.store') }}" method="POST" id="purchase-form">
                @csrf
                <div id="items-container"></div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Product picker + cart -->
                    <div class="lg:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <label for="product_search" class="block text-sm font-medium text-gray-700">Ongeza Bidhaa Uliyoinunua</label>
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
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase w-32">Idadi</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase w-32">Bei ya Kununua</th>
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

                    <!-- Purchase summary -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg h-fit">
                        <div class="p-6 space-y-4">
                            <div>
                                <label for="supplier_id" class="block text-sm font-medium text-gray-700">Muuzaji (Supplier)</label>
                                <select name="supplier_id" id="supplier_id"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">-- Hakuna / Nyingine --</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="purchase_date" class="block text-sm font-medium text-gray-700">Tarehe ya Ununuzi *</label>
                                <input type="date" name="purchase_date" id="purchase_date" value="{{ old('purchase_date', now()->format('Y-m-d')) }}" required
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                @error('purchase_date')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700">Maelezo (si lazima)</label>
                                <textarea name="notes" id="notes" rows="2"
                                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ old('notes') }}</textarea>
                            </div>

                            <div class="border-t pt-4 space-y-1 text-sm">
                                <div class="flex justify-between font-bold text-base"><span>Jumla ya Gharama</span><span id="summary-total">Tsh 0</span></div>
                            </div>

                            <div class="bg-blue-50 border border-blue-200 text-blue-800 text-xs p-3 rounded">
                                Kuhifadhi hapa kutaongeza stock ya bidhaa husika moja kwa moja, na kubadilisha "purchase price" ya bidhaa kuwa bei mpya uliyonunua.
                            </div>

                            <button type="submit" id="submit-btn" disabled
                                    class="w-full bg-green-500 hover:bg-green-700 disabled:opacity-50 disabled:cursor-not-allowed text-white font-bold py-2 px-4 rounded">
                                Hifadhi Stock-In
                            </button>
                            <a href="{{ route('web.purchases.index') }}"
                               class="block text-center text-sm text-gray-500 hover:text-gray-700">Ghairi</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        let cart = []; // { product_id, name, unit, cost, quantity }

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
            fetch(`/api/purchases/products/search?q=${encodeURIComponent(query)}`)
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
                    <div class="text-sm text-gray-500">Bei ya mwisho: Tsh ${Number(product.cost).toLocaleString()}</div>`;
                div.addEventListener('click', () => addToCart(product));
                suggestions.appendChild(div);
            });
            suggestions.classList.remove('hidden');
        }

        function addToCart(product) {
            const existing = cart.find(i => i.product_id === product.id);
            if (existing) {
                existing.quantity += 1;
            } else {
                cart.push({
                    product_id: product.id,
                    name: product.text,
                    unit: product.unit,
                    cost: parseFloat(product.cost) || 0,
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
            cart[index].quantity = qty;
            renderCart();
        }

        function updateCost(index, value) {
            cart[index].cost = parseFloat(value) || 0;
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
                    const lineTotal = item.quantity * item.cost;
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td class="px-4 py-2 text-sm text-gray-900">${item.name}</td>
                        <td class="px-4 py-2">
                            <div class="flex items-center gap-1">
                                <input type="number" min="0.01" step="0.01" value="${item.quantity}"
                                       class="w-20 border-gray-300 rounded-md shadow-sm sm:text-sm"
                                       onchange="updateQuantity(${index}, this.value)">
                                <span class="text-xs text-gray-500">${item.unit || 'kipande'}</span>
                            </div>
                        </td>
                        <td class="px-4 py-2">
                            <input type="number" min="0" step="0.01" value="${item.cost}"
                                   class="w-28 border-gray-300 rounded-md shadow-sm sm:text-sm"
                                   onchange="updateCost(${index}, this.value)">
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
            const total = cart.reduce((sum, i) => sum + (i.quantity * i.cost), 0);
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
                    <input type="hidden" name="items[${index}][unit_cost]" value="${item.cost}">
                `;
            });
        }

        document.getElementById('purchase-form').addEventListener('submit', function (e) {
            if (cart.length === 0) {
                e.preventDefault();
                alert('Ongeza angalau bidhaa moja kabla ya kuhifadhi.');
            }
        });
    </script>
</x-app-layout>

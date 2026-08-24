<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New Product') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('web.products.store') }}" method="POST">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Product Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Product Name *</label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Category -->
                            <div>
                                <label for="category_id" class="block text-sm font-medium text-gray-700">Category</label>
                                <div class="mt-1 flex gap-2">
                                    <select name="category_id" id="category_id"
                                            class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        <option value="">-- Hakuna --</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="button" onclick="openQuickAdd('category')"
                                            class="whitespace-nowrap text-sm text-indigo-600 hover:text-indigo-900 px-2">
                                        + Mpya
                                    </button>
                                </div>
                                @error('category_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Supplier -->
                            <div>
                                <label for="supplier_id" class="block text-sm font-medium text-gray-700">Supplier</label>
                                <div class="mt-1 flex gap-2">
                                    <select name="supplier_id" id="supplier_id"
                                            class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        <option value="">-- Hakuna --</option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                                {{ $supplier->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="button" onclick="openQuickAdd('supplier')"
                                            class="whitespace-nowrap text-sm text-indigo-600 hover:text-indigo-900 px-2">
                                        + Mpya
                                    </button>
                                </div>
                                @error('supplier_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Arrival Date -->
                            <div>
                                <label for="arrival_date" class="block text-sm font-medium text-gray-700">Arrival Date (si lazima)</label>
                                <input type="date" name="arrival_date" id="arrival_date" value="{{ old('arrival_date') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <p class="mt-1 text-xs text-gray-400">Historia halisi ya stock-in sasa inafuatiliwa kwenye "Purchases" — hii ni tarehe ya taarifa tu, si lazima.</p>
                                @error('arrival_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Purchase Price -->
                            <div>
                                <label for="purchase_price" class="block text-sm font-medium text-gray-700">Purchase Price (Tsh) *</label>
                                <input type="number" name="purchase_price" id="purchase_price" value="{{ old('purchase_price') }}" step="0.01" min="0" required
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                @error('purchase_price')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Selling Price -->
                            <div>
                                <label for="selling_price" class="block text-sm font-medium text-gray-700">Selling Price (Tsh) *</label>
                                <input type="number" name="selling_price" id="selling_price" value="{{ old('selling_price') }}" step="0.01" min="0" required
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                @error('selling_price')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Discount Price -->
                            <div>
                                <label for="discount_price" class="block text-sm font-medium text-gray-700">Discount Price (Tsh)</label>
                                <input type="number" name="discount_price" id="discount_price" value="{{ old('discount_price') }}" step="0.01" min="0"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                @error('discount_price')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Stock Quantity -->
                            <div>
                                <label for="stock_quantity" class="block text-sm font-medium text-gray-700">Stock Quantity *</label>
                                <input type="number" name="stock_quantity" id="stock_quantity" value="{{ old('stock_quantity', 0) }}" min="0" step="0.01" required
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                @error('stock_quantity')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Unit of Measure -->
                            <div>
                                <label for="unit" class="block text-sm font-medium text-gray-700">Kipimo (Unit) *</label>
                                <select name="unit" id="unit" required
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    @foreach(['kipande' => 'Kipande (piece)', 'kg' => 'Kilo (kg)', 'lita' => 'Lita (litre)', 'dazani' => 'Dazani (dozen)', 'pakiti' => 'Pakiti (pack)', 'mita' => 'Mita (metre)', 'roli' => 'Roli (roll)'] as $value => $label)
                                        <option value="{{ $value }}" {{ old('unit', 'kipande') == $value ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('unit')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mt-6">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="3"
                                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Buttons -->
                        <div class="mt-6 flex justify-end space-x-3">
                            <a href="{{ route('web.products.index') }}"
                               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('Cancel') }}
                            </a>
                            <button type="submit" 
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Create Product
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Add Modal (Category / Supplier) -->
    <div id="quick-add-modal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-sm">
            <h3 id="quick-add-title" class="text-lg font-medium text-gray-900 mb-4">Ongeza Mpya</h3>
            <input type="text" id="quick-add-name" placeholder="Jina..."
                   class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm mb-2">
            <p id="quick-add-error" class="text-sm text-red-600 mb-2 hidden"></p>
            <div class="flex justify-end gap-2 mt-4">
                <button type="button" onclick="closeQuickAdd()" class="text-sm text-gray-600 hover:text-gray-800 px-3 py-2">Ghairi</button>
                <button type="button" onclick="submitQuickAdd()" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold py-2 px-4 rounded">Hifadhi</button>
            </div>
        </div>
    </div>

    <script>
        let quickAddType = null; // 'category' | 'supplier'

        function openQuickAdd(type) {
            quickAddType = type;
            document.getElementById('quick-add-title').textContent =
                type === 'category' ? 'Ongeza Category Mpya' : 'Ongeza Supplier Mpya';
            document.getElementById('quick-add-name').value = '';
            document.getElementById('quick-add-error').classList.add('hidden');
            document.getElementById('quick-add-modal').classList.remove('hidden');
            document.getElementById('quick-add-modal').classList.add('flex');
            document.getElementById('quick-add-name').focus();
        }

        function closeQuickAdd() {
            document.getElementById('quick-add-modal').classList.add('hidden');
            document.getElementById('quick-add-modal').classList.remove('flex');
        }

        function submitQuickAdd() {
            const name = document.getElementById('quick-add-name').value.trim();
            const errorEl = document.getElementById('quick-add-error');
            errorEl.classList.add('hidden');

            if (!name) {
                errorEl.textContent = 'Jina linahitajika.';
                errorEl.classList.remove('hidden');
                return;
            }

            const url = quickAddType === 'category'
                ? '{{ route('api.categories.quick-store') }}'
                : '{{ route('api.suppliers.quick-store') }}';

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ name }),
            })
                .then(async (r) => {
                    if (!r.ok) {
                        const data = await r.json().catch(() => ({}));
                        throw new Error(data.message || 'Imeshindikana kuhifadhi.');
                    }
                    return r.json();
                })
                .then((data) => {
                    const selectId = quickAddType === 'category' ? 'category_id' : 'supplier_id';
                    const select = document.getElementById(selectId);
                    const option = document.createElement('option');
                    option.value = data.id;
                    option.textContent = data.name;
                    option.selected = true;
                    select.appendChild(option);
                    closeQuickAdd();
                })
                .catch((err) => {
                    errorEl.textContent = err.message;
                    errorEl.classList.remove('hidden');
                });
        }
    </script>
</x-app-layout> 
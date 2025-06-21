<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Supplier Details') }}
            </h2>
            <a href="{{ route('suppliers.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Back to Suppliers
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-2">{{ $supplier->name }}</h3>
                    <p class="mb-2"><span class="font-semibold">Email:</span> {{ $supplier->email ?? '-' }}</p>
                    <p class="mb-2"><span class="font-semibold">Phone:</span> {{ $supplier->phone ?? '-' }}</p>
                    <p class="mb-2"><span class="font-semibold">Contact Person:</span> {{ $supplier->contact_person ?? '-' }}</p>
                    <p class="mb-2"><span class="font-semibold">Tax Number:</span> {{ $supplier->tax_number ?? '-' }}</p>
                    <p class="mb-2"><span class="font-semibold">Payment Terms:</span> {{ $supplier->payment_terms ?? '-' }}</p>
                    <p class="mb-2"><span class="font-semibold">Address:</span> {{ $supplier->address ?? '-' }}</p>
                    <p class="mb-4"><span class="font-semibold">Created:</span> {{ $supplier->created_at->format('M d, Y') }}</p>

                    <h4 class="font-semibold mt-6 mb-2">Products from this Supplier</h4>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-2">Product Name</th>
                                    <th class="px-4 py-2">Stock</th>
                                    <th class="px-4 py-2">Price</th>
                                    <th class="px-4 py-2">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($supplier->products as $product)
                                    <tr>
                                        <td class="px-4 py-2">{{ $product->name }}</td>
                                        <td class="px-4 py-2">{{ $product->stock_quantity }}</td>
                                        <td class="px-4 py-2">{{ number_format($product->selling_price, 2) }}</td>
                                        <td class="px-4 py-2">
                                            <a href="{{ route('products.show', $product) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-gray-500 py-4">No products from this supplier.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 
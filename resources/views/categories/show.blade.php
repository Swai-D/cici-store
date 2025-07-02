<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Category Details') }}
            </h2>
            <a href="{{ route('web.categories.edit', $category) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                {{ __('Edit Category') }}
            </a>
            <a href="{{ route('web.categories.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                {{ __('Back to Categories') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-2">{{ $category->name }}</h3>
                    <p class="mb-2"><span class="font-semibold">Description:</span> {{ $category->description ?? '-' }}</p>
                    <p class="mb-2"><span class="font-semibold">Color:</span> <span class="inline-block w-6 h-6 rounded-full border border-gray-300 align-middle" style="background-color: {{ $category->color }}"></span></p>
                    <p class="mb-4"><span class="font-semibold">Created:</span> {{ $category->created_at->format('M d, Y') }}</p>

                    <h4 class="font-semibold mt-6 mb-2">Products in this Category</h4>
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
                                @forelse($category->products as $product)
                                    <tr>
                                        <td class="px-4 py-2">{{ $product->name }}</td>
                                        <td class="px-4 py-2">{{ $product->stock_quantity }}</td>
                                        <td class="px-4 py-2">{{ number_format($product->selling_price, 2) }}</td>
                                        <td class="px-4 py-2">
                                            <a href="{{ route('web.products.show', $product) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-gray-500 py-4">No products in this category.</td>
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
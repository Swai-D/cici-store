<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Product Details') }} - {{ $product->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Product Information -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">{{ $product->name }}</h3>
                            <p class="text-gray-600 mt-1">Product Code: {{ $product->product_code }}</p>
                        </div>
                        <div class="flex space-x-3">
                            <a href="{{ route('web.products.edit', $product) }}"
                               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('Edit Product') }}
                            </a>
                            <form action="{{ route('web.products.destroy', $product) }}" method="POST" class="inline"
                                  onsubmit="return confirm('{{ __('Are you sure you want to delete this product?') }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Basic Information -->
                        <div>
                            <h4 class="text-lg font-semibold text-gray-800 mb-4">Basic Information</h4>
                            <div class="space-y-3">
                                <div>
                                    <span class="font-medium text-gray-700">Description:</span>
                                    <p class="text-gray-900 mt-1">{{ $product->description ?: 'No description available' }}</p>
                                </div>
                                
                                <div>
                                    <span class="font-medium text-gray-700">Category:</span>
                                    <p class="text-gray-900 mt-1">{{ $product->category->name }}</p>
                                </div>
                                
                                <div>
                                    <span class="font-medium text-gray-700">Supplier:</span>
                                    <p class="text-gray-900 mt-1">{{ $product->supplier->name }}</p>
                                </div>
                                
                                <div>
                                    <span class="font-medium text-gray-700">Arrival Date:</span>
                                    <p class="text-gray-900 mt-1">{{ $product->arrival_date->format('M d, Y') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Pricing Information -->
                        <div>
                            <h4 class="text-lg font-semibold text-gray-800 mb-4">Pricing & Stock</h4>
                            <div class="space-y-3">
                                <div>
                                    <span class="font-medium text-gray-700">Purchase Price:</span>
                                    <p class="text-gray-900 mt-1">Tsh {{ number_format($product->purchase_price, 2) }}</p>
                                </div>
                                
                                <div>
                                    <span class="font-medium text-gray-700">Selling Price:</span>
                                    <p class="text-gray-900 mt-1">Tsh {{ number_format($product->selling_price, 2) }}</p>
                                </div>
                                
                                @if($product->discount_price)
                                    <div>
                                        <span class="font-medium text-gray-700">Discount Price:</span>
                                        <p class="text-gray-900 mt-1">Tsh {{ number_format($product->discount_price, 2) }}</p>
                                    </div>
                                @endif
                                
                                <div>
                                    <span class="font-medium text-gray-700">Profit per Unit:</span>
                                    <p class="text-green-600 font-semibold mt-1">Tsh {{ number_format($product->profit, 2) }}</p>
                                </div>
                                
                                <div>
                                    <span class="font-medium text-gray-700">Stock Quantity:</span>
                                    <p class="text-gray-900 mt-1 {{ $product->isLowStock() ? 'text-red-600 font-semibold' : '' }}">
                                        {{ $product->stock_quantity }} units
                                        @if($product->isLowStock())
                                            <span class="text-sm text-red-500">(Low Stock)</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sales History -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Sales History</h3>
                    @if($product->sales->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity Sold</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sale Price</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Profit</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Method</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($product->sales->sortByDesc('sale_time') as $sale)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $sale->sale_time->format('M d, Y H:i') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $sale->quantity_sold }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                Tsh {{ number_format($sale->sale_price, 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                Tsh {{ number_format($sale->total_price, 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 font-semibold">
                                                Tsh {{ number_format($sale->profit, 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $sale->payment_method }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-50">
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">Total</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                            {{ $product->sales->sum('quantity_sold') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">-</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                            Tsh {{ number_format($product->sales->sum('total_price'), 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-green-600">
                                            Tsh {{ number_format($product->sales->sum('profit'), 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">-</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-500">No sales recorded for this product.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 
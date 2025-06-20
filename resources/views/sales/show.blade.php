<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Sale Details') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('sales.edit', $sale) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Edit Sale
                </a>
                <a href="{{ route('sales.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Back to Sales
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Sale Header -->
                    <div class="border-b border-gray-200 pb-4 mb-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900">{{ $sale->transaction_code }}</h3>
                                <p class="text-gray-600">Sale recorded on {{ $sale->sale_time->format('F j, Y \a\t g:i A') }}</p>
                            </div>
                            <div class="text-right">
                                <div class="text-3xl font-bold text-green-600">Tsh {{ number_format($sale->total_price) }}</div>
                                <div class="text-sm text-gray-500">Total Amount</div>
                            </div>
                        </div>
                    </div>

                    <!-- Sale Details Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Product Information -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="text-lg font-semibold text-gray-900 mb-3">Product Information</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Product Name:</span>
                                    <span class="font-medium">{{ $sale->product->name }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Product Code:</span>
                                    <span class="font-medium">{{ $sale->product->product_code }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Category:</span>
                                    <span class="font-medium">{{ $sale->product->category->name }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Supplier:</span>
                                    <span class="font-medium">{{ $sale->product->supplier->name }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Sale Information -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="text-lg font-semibold text-gray-900 mb-3">Sale Information</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Quantity Sold:</span>
                                    <span class="font-medium">{{ $sale->quantity_sold }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Sale Price:</span>
                                    <span class="font-medium">Tsh {{ number_format($sale->sale_price) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Total Price:</span>
                                    <span class="font-medium text-green-600">Tsh {{ number_format($sale->total_price) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Payment Method:</span>
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                        {{ $sale->payment_method == 'Cash' ? 'bg-green-100 text-green-800' : 
                                           ($sale->payment_method == 'M-Pesa' ? 'bg-blue-100 text-blue-800' : 
                                           ($sale->payment_method == 'TigoPesa' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800')) }}">
                                        {{ $sale->payment_method }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Customer Information -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="text-lg font-semibold text-gray-900 mb-3">Customer Information</h4>
                            <div class="space-y-2">
                                @if($sale->customer_phone)
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Phone Number:</span>
                                        <span class="font-medium">{{ $sale->customer_phone }}</span>
                                    </div>
                                @else
                                    <div class="text-gray-500 italic">No customer phone provided</div>
                                @endif
                            </div>
                        </div>

                        <!-- Financial Information -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="text-lg font-semibold text-gray-900 mb-3">Financial Information</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Purchase Price:</span>
                                    <span class="font-medium">Tsh {{ number_format($sale->product->purchase_price) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Profit per Unit:</span>
                                    <span class="font-medium text-green-600">Tsh {{ number_format($sale->sale_price - $sale->product->purchase_price) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Total Profit:</span>
                                    <span class="font-medium text-green-600">Tsh {{ number_format($sale->profit) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Profit Margin:</span>
                                    <span class="font-medium text-green-600">
                                        {{ number_format((($sale->sale_price - $sale->product->purchase_price) / $sale->sale_price) * 100, 1) }}%
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-8 flex justify-end space-x-3">
                        <form action="{{ route('sales.destroy', $sale) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this sale? This will restore the product stock.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                Delete Sale
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 
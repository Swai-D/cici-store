<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center print:hidden">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Sale Details / Invoice') }}
            </h2>
            <div class="flex space-x-2">
                <button onclick="window.print()" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('Chapisha Risiti') }}
                </button>
                <a href="{{ route('web.sales.edit', $sale) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('Edit') }}
                </a>
                <a href="{{ route('web.sales.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('Back to Sales') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12 print:py-0">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 print:max-w-full print:px-0">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg print:shadow-none" id="invoice">
                <div class="p-6 print:p-4">

                    <!-- Invoice Header -->
                    <div class="border-b border-gray-200 pb-4 mb-6 flex justify-between items-start">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">{{ config('app.name') }}</h3>
                            <p class="text-sm text-gray-500">Risiti / Invoice</p>
                        </div>
                        <div class="text-right">
                            <div class="font-mono font-semibold">{{ $sale->transaction_code }}</div>
                            <div class="text-sm text-gray-500">{{ $sale->sale_time->format('d M Y, H:i') }}</div>
                        </div>
                    </div>

                    <!-- Customer -->
                    <div class="mb-6 text-sm">
                        <span class="text-gray-600">Mteja:</span>
                        <span class="font-medium">{{ $sale->customer_phone ?? 'Walk-in customer' }}</span>
                    </div>

                    <!-- Line Items -->
                    <table class="min-w-full divide-y divide-gray-200 mb-6">
                        <thead>
                            <tr>
                                <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase">Bidhaa</th>
                                <th class="px-2 py-2 text-right text-xs font-medium text-gray-500 uppercase">Qty</th>
                                <th class="px-2 py-2 text-right text-xs font-medium text-gray-500 uppercase">Bei</th>
                                <th class="px-2 py-2 text-right text-xs font-medium text-gray-500 uppercase">Jumla</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($sale->items as $item)
                                <tr>
                                    <td class="px-2 py-2 text-sm text-gray-900">{{ $item->product->name ?? 'Bidhaa iliyofutwa' }}</td>
                                    <td class="px-2 py-2 text-sm text-gray-900 text-right">{{ $item->quantity }} {{ $item->product->unit ?? '' }}</td>
                                    <td class="px-2 py-2 text-sm text-gray-900 text-right">{{ number_format($item->unit_price) }}</td>
                                    <td class="px-2 py-2 text-sm text-gray-900 text-right">{{ number_format($item->line_total) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Totals -->
                    <div class="flex justify-end mb-8">
                        <div class="w-64 space-y-1 text-sm">
                            <div class="flex justify-between"><span class="text-gray-600">Subtotal</span><span>Tsh {{ number_format($sale->subtotal) }}</span></div>
                            <div class="flex justify-between"><span class="text-gray-600">Punguzo</span><span>Tsh {{ number_format($sale->discount_amount) }}</span></div>
                            <div class="flex justify-between font-bold text-base border-t pt-2"><span>Jumla</span><span>Tsh {{ number_format($sale->total_price) }}</span></div>
                            <div class="flex justify-between text-gray-600"><span>Malipo</span><span>{{ $sale->payment_method }}</span></div>
                        </div>
                    </div>

                    @if($sale->notes)
                        <div class="mb-6 text-sm text-gray-600 print:hidden">
                            <span class="font-medium">Maelezo:</span> {{ $sale->notes }}
                        </div>
                    @endif

                    <p class="text-center text-xs text-gray-400 mb-8">Asante kwa kufanya biashara nasi!</p>

                    <!-- Internal financial info (hidden on print, cashier shouldn't hand this to the customer) -->
                    <div class="bg-gray-50 p-4 rounded-lg print:hidden">
                        <h4 class="text-sm font-semibold text-gray-900 mb-3">Taarifa za Ndani (COGS/Profit)</h4>
                        <div class="grid grid-cols-3 gap-4 text-sm">
                            <div class="flex flex-col">
                                <span class="text-gray-600">COGS</span>
                                <span class="font-medium">Tsh {{ number_format($sale->cogs) }}</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-gray-600">Faida (Profit)</span>
                                <span class="font-medium text-green-600">Tsh {{ number_format($sale->profit) }}</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-gray-600">Profit Margin</span>
                                <span class="font-medium text-green-600">
                                    {{ $sale->total_price > 0 ? number_format(($sale->profit / $sale->total_price) * 100, 1) : 0 }}%
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-8 flex justify-end space-x-3 print:hidden">
                        <form action="{{ route('web.sales.destroy', $sale) }}" method="POST" class="inline" onsubmit="return confirm('Una uhakika unataka ku-void mauzo haya? Stock itarudishwa.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                Void Sale
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media print {
            body * { visibility: hidden; }
            #invoice, #invoice * { visibility: visible; }
            #invoice { position: absolute; left: 0; top: 0; width: 100%; }
        }
    </style>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Stock-In Details') }} — {{ $purchase->reference_no }}
            </h2>
            <a href="{{ route('web.purchases.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                {{ __('Back to Purchases') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    <div class="border-b border-gray-200 pb-4 mb-6 flex justify-between items-start">
                        <div>
                            <div class="font-mono font-semibold text-lg">{{ $purchase->reference_no }}</div>
                            <div class="text-sm text-gray-500">{{ $purchase->purchase_date->format('d M Y') }}</div>
                        </div>
                        <div class="text-right text-sm">
                            <div class="text-gray-600">Supplier</div>
                            <div class="font-medium">{{ $purchase->supplier->name ?? '—' }}</div>
                        </div>
                    </div>

                    <table class="min-w-full divide-y divide-gray-200 mb-6">
                        <thead>
                            <tr>
                                <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase">Bidhaa</th>
                                <th class="px-2 py-2 text-right text-xs font-medium text-gray-500 uppercase">Idadi</th>
                                <th class="px-2 py-2 text-right text-xs font-medium text-gray-500 uppercase">Bei/Kipimo</th>
                                <th class="px-2 py-2 text-right text-xs font-medium text-gray-500 uppercase">Jumla</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($purchase->items as $item)
                                <tr>
                                    <td class="px-2 py-2 text-sm text-gray-900">{{ $item->product->name ?? 'Bidhaa iliyofutwa' }}</td>
                                    <td class="px-2 py-2 text-sm text-gray-900 text-right">{{ $item->quantity }} {{ $item->product->unit ?? '' }}</td>
                                    <td class="px-2 py-2 text-sm text-gray-900 text-right">{{ number_format($item->unit_cost) }}</td>
                                    <td class="px-2 py-2 text-sm text-gray-900 text-right">{{ number_format($item->line_total) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="flex justify-end mb-8">
                        <div class="w-64 space-y-1 text-sm">
                            <div class="flex justify-between font-bold text-base border-t pt-2"><span>Jumla ya Gharama</span><span>Tsh {{ number_format($purchase->total_cost) }}</span></div>
                        </div>
                    </div>

                    @if($purchase->notes)
                        <div class="mb-6 text-sm text-gray-600">
                            <span class="font-medium">Maelezo:</span> {{ $purchase->notes }}
                        </div>
                    @endif

                    @can('delete_purchases')
                        <div class="flex justify-end">
                            <form action="{{ route('web.purchases.destroy', $purchase) }}" method="POST" onsubmit="return confirm('Una uhakika unataka ku-void hii stock-in? Stock itapunguzwa tena.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                    Void Stock-In
                                </button>
                            </form>
                        </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

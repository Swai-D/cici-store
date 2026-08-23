<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Sale') }} — {{ $sale->transaction_code }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded mb-6 text-sm">
                Kwa usalama wa stock, huwezi kubadilisha bidhaa/idadi ya order iliyoshahifadhiwa hapa.
                Ukihitaji kubadilisha bidhaa zilizouzwa, <strong>void</strong> mauzo haya (stock itarudi),
                kisha rekodi mauzo mapya.
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h4 class="text-sm font-semibold text-gray-900 mb-3">Bidhaa kwenye order hii</h4>
                    <table class="min-w-full divide-y divide-gray-200">
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
                                    <td class="px-2 py-2 text-sm">{{ $item->product->name ?? 'Bidhaa iliyofutwa' }}</td>
                                    <td class="px-2 py-2 text-sm text-right">{{ $item->quantity }} {{ $item->product->unit ?? '' }}</td>
                                    <td class="px-2 py-2 text-sm text-right">{{ number_format($item->unit_price) }}</td>
                                    <td class="px-2 py-2 text-sm text-right">{{ number_format($item->line_total) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('web.sales.update', $sale) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="space-y-4">
                            <div>
                                <label for="payment_method" class="block text-sm font-medium text-gray-700">Payment Method *</label>
                                <select name="payment_method" id="payment_method" required
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    @foreach(['Cash', 'M-Pesa', 'TigoPesa', 'Airtel Money', 'Bank', 'Credit Card'] as $method)
                                        <option value="{{ $method }}" {{ old('payment_method', $sale->payment_method) == $method ? 'selected' : '' }}>{{ $method }}</option>
                                    @endforeach
                                </select>
                                @error('payment_method')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="customer_phone" class="block text-sm font-medium text-gray-700">Customer Phone</label>
                                <input type="text" name="customer_phone" id="customer_phone" value="{{ old('customer_phone', $sale->customer_phone) }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                @error('customer_phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                                <textarea name="notes" id="notes" rows="3"
                                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ old('notes', $sale->notes) }}</textarea>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end space-x-3">
                            <a href="{{ route('web.sales.show', $sale) }}"
                               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('Cancel') }}
                            </a>
                            <button type="submit"
                                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

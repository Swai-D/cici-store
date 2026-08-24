<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profit & Loss Report') }} - {{ $startDate }} to {{ $endDate }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="print:hidden">
                @include('reports._tabs')
            </div>

            <!-- Date Range Filter -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 print:hidden">
                <div class="p-6">
                    <form method="GET" action="{{ route('web.reports.profit-loss') }}" class="flex items-end gap-4 flex-wrap">
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700">Kuanzia Tarehe</label>
                            <input type="date" name="start_date" id="start_date" value="{{ $startDate }}"
                                   class="mt-1 block border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700">Mpaka Tarehe</label>
                            <input type="date" name="end_date" id="end_date" value="{{ $endDate }}"
                                   class="mt-1 block border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                            Chuja (Filter)
                        </button>
                        <div class="flex gap-2 text-sm">
                            <a href="{{ route('web.reports.profit-loss', ['start_date' => now()->startOfMonth()->format('Y-m-d'), 'end_date' => now()->endOfMonth()->format('Y-m-d')]) }}" class="text-indigo-600 hover:text-indigo-900">Mwezi huu</a>
                            <a href="{{ route('web.reports.profit-loss', ['start_date' => now()->subMonth()->startOfMonth()->format('Y-m-d'), 'end_date' => now()->subMonth()->endOfMonth()->format('Y-m-d')]) }}" class="text-indigo-600 hover:text-indigo-900">Mwezi uliopita</a>
                            <a href="{{ route('web.reports.profit-loss', ['start_date' => now()->startOfYear()->format('Y-m-d'), 'end_date' => now()->endOfYear()->format('Y-m-d')]) }}" class="text-indigo-600 hover:text-indigo-900">Mwaka huu</a>
                        </div>
                        <button type="button" onclick="window.print()"
                                class="ml-auto flex items-center gap-2 bg-white hover:bg-gray-50 text-gray-700 font-bold py-2 px-4 rounded border border-gray-300">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4H7v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                            </svg>
                            Pakua PDF / Chapisha
                        </button>
                    </form>
                </div>
            </div>

            <div id="report-print-area">
                <div class="hidden print:block mb-6">
                    <h1 class="text-xl font-bold">{{ config('app.name') }} — Profit &amp; Loss Report</h1>
                    <p class="text-sm text-gray-600">{{ $startDate }} mpaka {{ $endDate }}</p>
                </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500">Total Sales</div>
                        <div class="text-2xl font-bold text-green-600">Tsh {{ number_format($totalSales, 2) }}</div>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500">Total Cost</div>
                        <div class="text-2xl font-bold text-red-600">Tsh {{ number_format($totalCost, 2) }}</div>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500">Gross Profit</div>
                        <div class="text-2xl font-bold text-purple-600">Tsh {{ number_format($grossProfit, 2) }}</div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500">Net Profit</div>
                        <div class="text-2xl font-bold {{ $netProfit >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            Tsh {{ number_format($netProfit, 2) }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profit & Loss Statement -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Profit & Loss Statement</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center py-2 border-b">
                            <span class="font-medium">Total Sales Revenue</span>
                            <span class="text-green-600 font-semibold">Tsh {{ number_format($totalSales, 2) }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center py-2 border-b">
                            <span class="font-medium">Cost of Goods Sold</span>
                            <span class="text-red-600 font-semibold">Tsh {{ number_format($totalCost, 2) }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center py-2 border-b border-gray-300">
                            <span class="font-semibold text-lg">Gross Profit</span>
                            <span class="text-purple-600 font-bold text-lg">Tsh {{ number_format($grossProfit, 2) }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center py-2 border-b">
                            <span class="font-medium">Operating Expenses</span>
                            <span class="text-red-600 font-semibold">Tsh {{ number_format($totalExpenses, 2) }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center py-2 border-t-2 border-gray-400">
                            <span class="font-bold text-xl">Net Profit</span>
                            <span class="{{ $netProfit >= 0 ? 'text-green-600' : 'text-red-600' }} font-bold text-xl">
                                Tsh {{ number_format($netProfit, 2) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Expenses Breakdown -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Expenses Breakdown</h3>
                    @if($expensesByCategory->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Percentage</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($expensesByCategory as $category => $amount)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $category }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                Tsh {{ number_format($amount, 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ number_format(($amount / $totalExpenses) * 100, 1) }}%
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-50">
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">Total</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                            Tsh {{ number_format($totalExpenses, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">100%</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-500">No expenses recorded for this period.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sales by Category -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Sales Performance by Category</h3>
                    @if($salesByCategory->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sales</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cost</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Profit</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Margin</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($salesByCategory as $category => $data)
                                        @php
                                            $margin = $data['sales'] > 0 ? (($data['profit'] / $data['sales']) * 100) : 0;
                                        @endphp
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $category }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                Tsh {{ number_format($data['sales'], 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                Tsh {{ number_format($data['cost'], 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                Tsh {{ number_format($data['profit'], 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ number_format($margin, 1) }}%
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-500">No sales recorded for this period.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Key Metrics -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Key Performance Metrics</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600">
                                {{ $totalSales > 0 ? number_format(($grossProfit / $totalSales) * 100, 1) : 0 }}%
                            </div>
                            <div class="text-sm text-gray-500">Gross Profit Margin</div>
                        </div>
                        
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600">
                                {{ $totalSales > 0 ? number_format(($netProfit / $totalSales) * 100, 1) : 0 }}%
                            </div>
                            <div class="text-sm text-gray-500">Net Profit Margin</div>
                        </div>
                        
                        <div class="text-center">
                            <div class="text-2xl font-bold text-purple-600">
                                {{ $totalExpenses > 0 ? number_format(($grossProfit / $totalExpenses), 2) : 0 }}
                            </div>
                            <div class="text-sm text-gray-500">Profit to Expense Ratio</div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>

    <style>
        @media print {
            body * { visibility: hidden; }
            #report-print-area, #report-print-area * { visibility: visible; }
            #report-print-area { position: absolute; left: 0; top: 0; width: 100%; }
        }
    </style>
</x-app-layout> 
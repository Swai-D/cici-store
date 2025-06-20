<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profit & Loss Report') }} - {{ $startDate }} to {{ $endDate }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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
</x-app-layout> 
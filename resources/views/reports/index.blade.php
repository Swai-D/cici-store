<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reports') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Daily Reports -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Daily Reports</dt>
                                    <dd class="text-lg font-medium text-gray-900">Sales by Day</dd>
                                </dl>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('web.reports.daily') }}" class="text-blue-600 hover:text-blue-900 font-medium">
                                View Daily Reports →
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Weekly Reports -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Weekly Reports</dt>
                                    <dd class="text-lg font-medium text-gray-900">Sales by Week</dd>
                                </dl>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('web.reports.weekly') }}" class="text-green-600 hover:text-green-900 font-medium">
                                View Weekly Reports →
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Monthly Reports -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Monthly Reports</dt>
                                    <dd class="text-lg font-medium text-gray-900">Sales by Month</dd>
                                </dl>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('web.reports.monthly') }}" class="text-purple-600 hover:text-purple-900 font-medium">
                                View Monthly Reports →
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Profit & Loss -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Profit & Loss</dt>
                                    <dd class="text-lg font-medium text-gray-900">Financial Summary</dd>
                                </dl>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('web.reports.profit-loss') }}" class="text-red-600 hover:text-red-900 font-medium">
                                View P&L Report →
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Statistics</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600">Today</div>
                            <div class="text-sm text-gray-500">Check today's sales and performance</div>
                            <a href="{{ route('web.reports.daily', ['date' => now()->format('Y-m-d')]) }}" class="mt-2 inline-block text-blue-600 hover:text-blue-900">
                                View Today's Report
                            </a>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600">This Week</div>
                            <div class="text-sm text-gray-500">Weekly performance overview</div>
                            <a href="{{ route('web.reports.weekly') }}" class="mt-2 inline-block text-green-600 hover:text-green-900">
                                View This Week's Report
                            </a>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-purple-600">This Month</div>
                            <div class="text-sm text-gray-500">Monthly financial summary</div>
                            <a href="{{ route('web.reports.monthly', ['month' => now()->format('Y-m')]) }}" class="mt-2 inline-block text-purple-600 hover:text-purple-900">
                                View This Month's Report
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 
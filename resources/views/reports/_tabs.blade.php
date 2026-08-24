@php
    $reportTabs = [
        'web.reports.daily' => 'Kila Siku',
        'web.reports.weekly' => 'Kila Wiki',
        'web.reports.monthly' => 'Kila Mwezi',
        'web.reports.profit-loss' => 'Profit & Loss',
    ];
@endphp

<div class="flex space-x-1 mb-6 border-b border-gray-200">
    @foreach($reportTabs as $route => $label)
        <a href="{{ route($route) }}"
           class="px-4 py-2 text-sm font-medium border-b-2 -mb-px {{ request()->routeIs($route) ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
            {{ $label }}
        </a>
    @endforeach
</div>

<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 sticky top-0 z-40 shadow-sm">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    @can('view_dashboard')
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                    @endcan

                    @can('view_products')
                        @php
                            $inProductsGroup = request()->routeIs('web.products.*') || request()->routeIs('web.categories.*') || request()->routeIs('web.suppliers.*');
                        @endphp
                        <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                            <button @click="open = ! open"
                                    class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 focus:outline-none transition duration-150 ease-in-out {{ $inProductsGroup ? 'border-indigo-400 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                {{ __('Products') }}
                                <svg class="ml-1 h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            <div x-show="open"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute z-50 mt-2 w-48 rounded-md shadow-lg origin-top-left"
                                 style="display: none;"
                                 @click="open = false">
                                <div class="rounded-md ring-1 ring-black ring-opacity-5 py-1 bg-white">
                                    <a href="{{ route('web.products.index') }}" class="block px-4 py-2 text-sm {{ request()->routeIs('web.products.*') ? 'text-gray-900 font-semibold' : 'text-gray-700' }} hover:bg-gray-100">
                                        {{ __('Bidhaa Zote') }}
                                    </a>
                                    @can('view_categories')
                                        <a href="{{ route('web.categories.index') }}" class="block px-4 py-2 text-sm {{ request()->routeIs('web.categories.*') ? 'text-gray-900 font-semibold' : 'text-gray-700' }} hover:bg-gray-100">
                                            {{ __('Categories') }}
                                        </a>
                                    @endcan
                                    @can('view_suppliers')
                                        <a href="{{ route('web.suppliers.index') }}" class="block px-4 py-2 text-sm {{ request()->routeIs('web.suppliers.*') ? 'text-gray-900 font-semibold' : 'text-gray-700' }} hover:bg-gray-100">
                                            {{ __('Suppliers') }}
                                        </a>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    @endcan

                    @can('view_sales')
                        <x-nav-link :href="route('web.sales.index')" :active="request()->routeIs('web.sales.*')">
                            {{ __('Sales') }}
                        </x-nav-link>
                    @endcan

                    @can('view_purchases')
                        <x-nav-link :href="route('web.purchases.index')" :active="request()->routeIs('web.purchases.*')">
                            {{ __('Purchases') }}
                        </x-nav-link>
                    @endcan

                    @can('view_expenses')
                        <x-nav-link :href="route('web.expenses.index')" :active="request()->routeIs('web.expenses.*')">
                            {{ __('Expenses') }}
                        </x-nav-link>
                    @endcan

                    @can('view_reports')
                        <x-nav-link :href="route('web.reports.daily')" :active="request()->routeIs('web.reports.*')">
                            {{ __('Reports') }}
                        </x-nav-link>
                    @endcan

                    @can('use_ai')
                        <x-nav-link :href="route('ai.chat')" :active="request()->routeIs('ai.*')">
                            {{ __('AI Consultant') }}
                        </x-nav-link>
                    @endcan

                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <!-- User Role Badge -->
                <div class="ml-3">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ auth()->user()->roles->first()?->name ?? 'User' }}
                    </span>
                </div>

                <!-- Settings Dropdown -->
                <div class="ml-3 relative">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->name }}</div>

                                <div class="ml-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            @role('Admin')
                                <x-dropdown-link :href="route('web.users.index')">
                                    {{ __('User Management') }}
                                </x-dropdown-link>
                            @endrole

                            @can('manage_ai')
                                <x-dropdown-link :href="route('admin.ai.edit')">
                                    {{ __('AI Settings') }}
                                </x-dropdown-link>
                            @endcan

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @can('view_dashboard')
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
            @endcan

            @can('view_products')
                <x-responsive-nav-link :href="route('web.products.index')" :active="request()->routeIs('web.products.*')">
                    {{ __('Products') }}
                </x-responsive-nav-link>
            @endcan

            @can('view_categories')
                <x-responsive-nav-link :href="route('web.categories.index')" :active="request()->routeIs('web.categories.*')">
                    {{ __('Categories') }}
                </x-responsive-nav-link>
            @endcan

            @can('view_suppliers')
                <x-responsive-nav-link :href="route('web.suppliers.index')" :active="request()->routeIs('web.suppliers.*')">
                    {{ __('Suppliers') }}
                </x-responsive-nav-link>
            @endcan

            @can('view_sales')
                <x-responsive-nav-link :href="route('web.sales.index')" :active="request()->routeIs('web.sales.*')">
                    {{ __('Sales') }}
                </x-responsive-nav-link>
            @endcan

            @can('view_purchases')
                <x-responsive-nav-link :href="route('web.purchases.index')" :active="request()->routeIs('web.purchases.*')">
                    {{ __('Purchases') }}
                </x-responsive-nav-link>
            @endcan

            @can('view_expenses')
                <x-responsive-nav-link :href="route('web.expenses.index')" :active="request()->routeIs('web.expenses.*')">
                    {{ __('Expenses') }}
                </x-responsive-nav-link>
            @endcan

            @can('view_reports')
                <x-responsive-nav-link :href="route('web.reports.daily')" :active="request()->routeIs('web.reports.*')">
                    {{ __('Reports') }}
                </x-responsive-nav-link>
            @endcan

            @can('use_ai')
                <x-responsive-nav-link :href="route('ai.chat')" :active="request()->routeIs('ai.*')">
                    {{ __('AI Consultant') }}
                </x-responsive-nav-link>
            @endcan

        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                <div class="mt-1">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ auth()->user()->roles->first()?->name ?? 'User' }}
                    </span>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                @role('Admin')
                    <x-responsive-nav-link :href="route('web.users.index')">
                        {{ __('User Management') }}
                    </x-responsive-nav-link>
                @endrole

                @can('manage_ai')
                    <x-responsive-nav-link :href="route('admin.ai.edit')">
                        {{ __('AI Settings') }}
                    </x-responsive-nav-link>
                @endcan

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav> 
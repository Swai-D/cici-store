<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
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
                        <x-nav-link :href="route('web.products.index')" :active="request()->routeIs('web.products.*')">
                            {{ __('Products') }}
                        </x-nav-link>
                    @endcan

                    @can('view_categories')
                        <x-nav-link :href="route('web.categories.index')" :active="request()->routeIs('web.categories.*')">
                            {{ __('Categories') }}
                        </x-nav-link>
                    @endcan

                    @can('view_suppliers')
                        <x-nav-link :href="route('web.suppliers.index')" :active="request()->routeIs('web.suppliers.*')">
                            {{ __('Suppliers') }}
                        </x-nav-link>
                    @endcan

                    @can('view_sales')
                        <x-nav-link :href="route('web.sales.index')" :active="request()->routeIs('web.sales.*')">
                            {{ __('Sales') }}
                        </x-nav-link>
                    @endcan

                    @can('view_expenses')
                        <x-nav-link :href="route('web.expenses.index')" :active="request()->routeIs('web.expenses.*')">
                            {{ __('Expenses') }}
                        </x-nav-link>
                    @endcan

                    @can('view_reports')
                        <x-nav-link :href="route('web.reports.index')" :active="request()->routeIs('web.reports.*')">
                            {{ __('Reports') }}
                        </x-nav-link>
                    @endcan

                    @role('Admin')
                        <x-nav-link :href="route('web.users.index')" :active="request()->routeIs('web.users.*')">
                            {{ __('User Management') }}
                        </x-nav-link>
                    @endrole
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

            @can('view_expenses')
                <x-responsive-nav-link :href="route('web.expenses.index')" :active="request()->routeIs('web.expenses.*')">
                    {{ __('Expenses') }}
                </x-responsive-nav-link>
            @endcan

            @can('view_reports')
                <x-responsive-nav-link :href="route('web.reports.index')" :active="request()->routeIs('web.reports.*')">
                    {{ __('Reports') }}
                </x-responsive-nav-link>
            @endcan

            @role('Admin')
                <x-responsive-nav-link :href="route('web.users.index')" :active="request()->routeIs('web.users.*')">
                    {{ __('User Management') }}
                </x-responsive-nav-link>
            @endrole
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
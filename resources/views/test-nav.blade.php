<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Navigation Test') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Navigation Test Page</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <h4 class="font-medium">User Information:</h4>
                            <p>Name: {{ auth()->user()->name }}</p>
                            <p>Email: {{ auth()->user()->email }}</p>
                            <p>Roles: {{ auth()->user()->roles->pluck('name')->implode(', ') ?: 'None' }}</p>
                            <p>Permissions: {{ auth()->user()->getAllPermissions()->pluck('name')->implode(', ') ?: 'None' }}</p>
                        </div>

                        <div>
                            <h4 class="font-medium">Permission Tests:</h4>
                            <p>Can view dashboard: {{ auth()->user()->can('view_dashboard') ? 'Yes' : 'No' }}</p>
                            <p>Can view products: {{ auth()->user()->can('view_products') ? 'Yes' : 'No' }}</p>
                            <p>Can view categories: {{ auth()->user()->can('view_categories') ? 'Yes' : 'No' }}</p>
                            <p>Can view suppliers: {{ auth()->user()->can('view_suppliers') ? 'Yes' : 'No' }}</p>
                            <p>Can view sales: {{ auth()->user()->can('view_sales') ? 'Yes' : 'No' }}</p>
                            <p>Can view expenses: {{ auth()->user()->can('view_expenses') ? 'Yes' : 'No' }}</p>
                            <p>Can view reports: {{ auth()->user()->can('view_reports') ? 'Yes' : 'No' }}</p>
                        </div>

                        <div>
                            <h4 class="font-medium">Test Links:</h4>
                            <div class="space-y-2">
                                <a href="{{ route('dashboard') }}" class="block text-blue-600 hover:text-blue-800">Dashboard</a>
                                <a href="{{ route('products.index') }}" class="block text-blue-600 hover:text-blue-800">Products</a>
                                <a href="{{ route('categories.index') }}" class="block text-blue-600 hover:text-blue-800">Categories</a>
                                <a href="{{ route('suppliers.index') }}" class="block text-blue-600 hover:text-blue-800">Suppliers</a>
                                <a href="{{ route('sales.index') }}" class="block text-blue-600 hover:text-blue-800">Sales</a>
                                <a href="{{ route('expenses.index') }}" class="block text-blue-600 hover:text-blue-800">Expenses</a>
                                <a href="{{ route('reports.index') }}" class="block text-blue-600 hover:text-blue-800">Reports</a>
                                @role('Admin')
                                    <a href="{{ route('users.index') }}" class="block text-blue-600 hover:text-blue-800">User Management</a>
                                @endrole
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 
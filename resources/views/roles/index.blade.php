<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Role Management') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('users.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('Back to User Management') }}
                </a>
                <a href="{{ route('roles.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('Create New Role') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if(session('success'))
                        <div class="mb-4 font-medium text-sm text-green-600">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="mb-4 font-medium text-sm text-red-600">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($roles as $role)
                            <div class="bg-gray-50 rounded-lg p-6 border">
                                <div class="flex justify-between items-start mb-4">
                                    <h3 class="text-lg font-semibold text-gray-900">{{ ucfirst($role->name) }}</h3>
                                    <div class="flex space-x-2">
                                        <a href="{{ route('roles.show', $role) }}" 
                                           class="text-blue-600 hover:text-blue-900 text-sm">
                                            {{ __('View') }}
                                        </a>
                                        <a href="{{ route('roles.edit', $role) }}" 
                                           class="text-indigo-600 hover:text-indigo-900 text-sm">
                                            {{ __('Edit') }}
                                        </a>
                                        @if($role->name !== 'Admin')
                                            <form action="{{ route('roles.destroy', $role) }}" method="POST" class="inline" 
                                                  onsubmit="return confirm('{{ __('Are you sure you want to delete this role?') }}')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 text-sm">
                                                    {{ __('Delete') }}
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">{{ __('Permissions') }}</h4>
                                    @if($role->permissions->count() > 0)
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($role->permissions->take(5) as $permission)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ $permission->name }}
                                                </span>
                                            @endforeach
                                            @if($role->permissions->count() > 5)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    +{{ $role->permissions->count() - 5 }} {{ __('more') }}
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        <p class="text-sm text-gray-500">{{ __('No permissions assigned') }}</p>
                                    @endif
                                </div>

                                <div class="text-sm text-gray-500">
                                    {{ __('Total Permissions') }}: {{ $role->permissions->count() }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 
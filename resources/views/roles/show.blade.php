<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Role Details') }}
            </h2>
            <a href="{{ route('web.roles.edit', $role) }}" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                {{ __('Edit Permissions') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if(session('success'))
                        <div class="mb-4 font-medium text-sm text-green-600">
                            {{ session('success') }}
                        </div>
                    @endif
                    <h3 class="text-lg font-semibold mb-4 text-gray-800">{{ ucfirst($role->name) }}</h3>
                    <div class="mb-4">
                        <h4 class="text-md font-medium mb-2 text-gray-800">{{ __('Assigned Permissions') }}</h4>
                        @if($role->permissions->count() > 0)
                            <div class="flex flex-wrap gap-2">
                                @foreach($role->permissions as $permission)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $permission->name }}
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-500">{{ __('No permissions assigned') }}</p>
                        @endif
                    </div>
                    <div class="mt-6">
                        <a href="{{ route('web.users.index') }}" class="text-gray-600 hover:text-gray-900">&larr; {{ __('Back to User Management') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 

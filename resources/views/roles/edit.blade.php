<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Role Permissions') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('web.roles.update', $role) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Role Name</label>
                            <div class="mt-1 text-gray-900 font-semibold">{{ ucfirst($role->name) }}</div>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Permissions</label>
                            
                            <!-- AI Business Consultant Permissions -->
                            <div class="mb-6">
                                <h4 class="text-sm font-semibold text-blue-800 mb-3 flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                    </svg>
                                    AI Business Consultant
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-2 pl-4">
                                    @foreach($allPermissions->whereIn('name', ['manage_ai', 'use_ai']) as $permission)
                                        <label class="inline-flex items-center p-2 bg-blue-50 rounded border border-blue-200">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500"
                                                {{ in_array($permission->id, $assignedPermissions) ? 'checked' : '' }}>
                                            <span class="ml-2 text-sm text-gray-700">
                                                @if($permission->name === 'manage_ai')
                                                    <span class="font-medium text-blue-800">Manage AI Settings</span>
                                                    <span class="text-xs text-gray-500 block">Configure AI and API keys</span>
                                                @elseif($permission->name === 'use_ai')
                                                    <span class="font-medium text-blue-800">Use AI Consultant</span>
                                                    <span class="text-xs text-gray-500 block">Chat with AI for business insights</span>
                                                @else
                                                    {{ $permission->name }}
                                                @endif
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Other Permissions -->
                            <div>
                                <h4 class="text-sm font-semibold text-gray-800 mb-3">Other Permissions</h4>
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                                    @foreach($allPermissions->whereNotIn('name', ['manage_ai', 'use_ai']) as $permission)
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                                {{ in_array($permission->id, $assignedPermissions) ? 'checked' : '' }}>
                                            <span class="ml-2 text-sm text-gray-700">{{ $permission->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                            
                            @error('permissions')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('web.users.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Back to User Management</a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Update Permissions</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 

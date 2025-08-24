<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Role Management') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('web.users.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('Back to User Management') }}
                </a>
                <a href="{{ route('ai.chat') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    {{ __('AI Chat') }}
                </a>
                <a href="{{ route('web.roles.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
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
                                        <a href="{{ route('web.roles.show', $role) }}" 
                                           class="text-blue-600 hover:text-blue-900 text-sm">
                                            {{ __('View') }}
                                        </a>
                                        <a href="{{ route('web.roles.edit', $role) }}" 
                                           class="text-indigo-600 hover:text-indigo-900 text-sm">
                                            {{ __('Edit') }}
                                        </a>
                                        @if($role->name !== 'Admin')
                                            <form action="{{ route('web.roles.destroy', $role) }}" method="POST" class="inline" 
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
                                        @php
                                            $aiPermissions = $role->permissions->whereIn('name', ['manage_ai', 'use_ai']);
                                            $otherPermissions = $role->permissions->whereNotIn('name', ['manage_ai', 'use_ai']);
                                        @endphp
                                        
                                        <!-- AI Permissions (always show if they exist) -->
                                        @if($aiPermissions->count() > 0)
                                            <div class="mb-2">
                                                <div class="flex flex-wrap gap-1">
                                                    @foreach($aiPermissions as $permission)
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">
                                                            @if($permission->name === 'manage_ai')
                                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                                </svg>
                                                                AI Settings
                                                            @elseif($permission->name === 'use_ai')
                                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                                                </svg>
                                                                AI Chat
                                                            @endif
                                                        </span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                        
                                        <!-- Other Permissions -->
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($otherPermissions->take(3) as $permission)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    {{ $permission->name }}
                                                </span>
                                            @endforeach
                                            @if($otherPermissions->count() > 3)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    +{{ $otherPermissions->count() - 3 }} {{ __('more') }}
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
                                
                                <!-- AI Status Indicator -->
                                @if($aiPermissions->count() > 0)
                                    <div class="mt-3 pt-3 border-t border-gray-200">
                                        <div class="flex items-center text-sm">
                                            <svg class="w-4 h-4 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                            </svg>
                                            <span class="text-blue-600 font-medium">AI Access Enabled</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 
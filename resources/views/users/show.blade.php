<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('User Details') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('users.edit', $user) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('Edit User') }}
                </a>
                <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('Are you sure you want to delete this user?') }}')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        {{ __('Delete User') }}
                    </button>
                </form>
                <a href="{{ route('users.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('Back to Users') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- User Information -->
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold mb-4 text-gray-800">{{ __('User Information') }}</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ __('Name') }}</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $user->name }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ __('Email') }}</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $user->email }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ __('Email Verified') }}</label>
                                    <p class="mt-1 text-sm text-gray-900">
                                        @if($user->email_verified_at)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                {{ __('Verified') }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                {{ __('Not Verified') }}
                                            </span>
                                        @endif
                                    </p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ __('Created At') }}</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $user->created_at->format('F j, Y \a\t g:i A') }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ __('Last Updated') }}</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $user->updated_at->format('F j, Y \a\t g:i A') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- User Roles -->
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold mb-4 text-gray-800">{{ __('User Roles') }}</h3>
                            
                            @if($user->roles->count() > 0)
                                <div class="space-y-2">
                                    @foreach($user->roles as $role)
                                        <div class="flex items-center justify-between p-3 bg-white rounded border">
                                            <span class="text-sm font-medium text-gray-900">{{ $role->name }}</span>
                                            <span class="text-xs text-gray-500">{{ $role->guard_name }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-sm text-gray-500">{{ __('No roles assigned') }}</p>
                            @endif

                            <div class="mt-4">
                                <h4 class="text-md font-medium mb-2 text-gray-800">{{ __('User Permissions') }}</h4>
                                @if($user->getAllPermissions()->count() > 0)
                                    <div class="grid grid-cols-2 gap-2">
                                        @foreach($user->getAllPermissions() as $permission)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $permission->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-sm text-gray-500">{{ __('No direct permissions assigned') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Activity Section -->
                    <div class="mt-8 bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800">{{ __('Recent Activity') }}</h3>
                        <p class="text-sm text-gray-500">{{ __('Activity tracking will be implemented in future updates.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

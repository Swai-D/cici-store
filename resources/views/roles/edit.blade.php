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
                    <form action="{{ route('roles.update', $role) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Role Name</label>
                            <div class="mt-1 text-gray-900 font-semibold">{{ ucfirst($role->name) }}</div>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Permissions</label>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                                @foreach($allPermissions as $permission)
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                            {{ in_array($permission->id, $assignedPermissions) ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm text-gray-700">{{ $permission->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('permissions')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('users.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Back to User Management</a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Update Permissions</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 

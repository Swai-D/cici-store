<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit User Role') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('users.update', $user) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Name</label>
                            <div class="mt-1 text-gray-900">{{ $user->name }}</div>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <div class="mt-1 text-gray-900">{{ $user->email }}</div>
                        </div>
                        <div class="mb-4">
                            <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                            <select name="role" id="role" required
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">Select Role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}" {{ $user->roles->first()?->name == $role->name ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('users.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Cancel</a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Update Role</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Supplier') }}
            </h2>
            <a href="{{ route('web.suppliers.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                {{ __('Back to Suppliers') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('web.suppliers.update', $supplier) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="name" :value="__('Supplier Name')" />
                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $supplier->name)" required autofocus />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="email" :value="__('Email (Optional)')" />
                                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $supplier->email)" />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="phone" :value="__('Phone (Optional)')" />
                                <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone', $supplier->phone)" />
                                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="contact_person" :value="__('Contact Person (Optional)')" />
                                <x-text-input id="contact_person" class="block mt-1 w-full" type="text" name="contact_person" :value="old('contact_person', $supplier->contact_person)" />
                                <x-input-error :messages="$errors->get('contact_person')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="tax_number" :value="__('Tax Number (Optional)')" />
                                <x-text-input id="tax_number" class="block mt-1 w-full" type="text" name="tax_number" :value="old('tax_number', $supplier->tax_number)" />
                                <x-input-error :messages="$errors->get('tax_number')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="payment_terms" :value="__('Payment Terms (Optional)')" />
                                <x-text-input id="payment_terms" class="block mt-1 w-full" type="text" name="payment_terms" :value="old('payment_terms', $supplier->payment_terms)" />
                                <x-input-error :messages="$errors->get('payment_terms')" class="mt-2" />
                            </div>
                        </div>
                        <div class="mt-6">
                            <x-input-label for="address" :value="__('Address (Optional)')" />
                            <textarea id="address" name="address" rows="3" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('address', $supplier->address) }}</textarea>
                            <x-input-error :messages="$errors->get('address')" class="mt-2" />
                        </div>
                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button class="ml-3">
                                {{ __('Update Supplier') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 
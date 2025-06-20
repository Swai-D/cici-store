<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Expense Details') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('expenses.edit', $expense) }}" 
                   class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('Edit') }}
                </a>
                <a href="{{ route('expenses.index') }}" 
                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('Back to List') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Expense Details -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">
                                {{ __('Expense Information') }}
                            </h3>
                            
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">
                                        {{ __('Description') }}
                                    </label>
                                    <p class="mt-1 text-sm text-gray-900 bg-gray-50 p-3 rounded-md">
                                        {{ $expense->description }}
                                    </p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">
                                        {{ __('Amount') }}
                                    </label>
                                    <p class="mt-1 text-sm text-gray-900 bg-gray-50 p-3 rounded-md font-semibold">
                                        TZS {{ number_format($expense->amount, 2) }}
                                    </p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">
                                        {{ __('Category') }}
                                    </label>
                                    <p class="mt-1 text-sm text-gray-900 bg-gray-50 p-3 rounded-md">
                                        {{ $expense->category }}
                                    </p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">
                                        {{ __('Payment Method') }}
                                    </label>
                                    <p class="mt-1 text-sm text-gray-900 bg-gray-50 p-3 rounded-md">
                                        {{ $expense->payment_method }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Details -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">
                                {{ __('Additional Information') }}
                            </h3>
                            
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">
                                        {{ __('Date') }}
                                    </label>
                                    <p class="mt-1 text-sm text-gray-900 bg-gray-50 p-3 rounded-md">
                                        {{ $expense->date ? $expense->date->format('F j, Y') : 'N/A' }}
                                    </p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">
                                        {{ __('Receipt Number') }}
                                    </label>
                                    <p class="mt-1 text-sm text-gray-900 bg-gray-50 p-3 rounded-md">
                                        {{ $expense->receipt_number ?? 'N/A' }}
                                    </p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">
                                        {{ __('Notes') }}
                                    </label>
                                    <p class="mt-1 text-sm text-gray-900 bg-gray-50 p-3 rounded-md">
                                        {{ $expense->notes ?? 'No notes available' }}
                                    </p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">
                                        {{ __('Created At') }}
                                    </label>
                                    <p class="mt-1 text-sm text-gray-900 bg-gray-50 p-3 rounded-md">
                                        {{ $expense->created_at->format('F j, Y \a\t g:i A') }}
                                    </p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">
                                        {{ __('Last Updated') }}
                                    </label>
                                    <p class="mt-1 text-sm text-gray-900 bg-gray-50 p-3 rounded-md">
                                        {{ $expense->updated_at->format('F j, Y \a\t g:i A') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-8 flex justify-between items-center pt-6 border-t">
                        <div class="flex space-x-2">
                            <a href="{{ route('expenses.edit', $expense) }}" 
                               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('Edit Expense') }}
                            </a>
                            <a href="{{ route('expenses.index') }}" 
                               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('Back to Expenses') }}
                            </a>
                        </div>
                        
                        <form action="{{ route('expenses.destroy', $expense) }}" method="POST" 
                              onsubmit="return confirm('{{ __('Are you sure you want to delete this expense?') }}')"
                              class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('Delete Expense') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<div class="mx-auto my-8 p-4 bg-gray-50 rounded overflow-x-auto">  
    <h1 class="text-xl font-bold text-center mb-6">Loan Management</h1>  
  
    <!-- Display a flash message if one exists -->  
    @if(session()->has('message'))  
        <div class="mb-6 p-3 border border-green-300 bg-green-100 text-green-700 rounded">  
            {{ session('message') }}  
        </div>  
    @endif  
  
    <!-- Form for creating / editing a loan -->  
    <form wire:submit.prevent="{{ $updateMode ? 'update' : 'store' }}" class="bg-white p-4 rounded shadow mb-6">  
        <div class="mb-4">  
            <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">  
                Loan Amount:  
            </label>  
            <input  
                type="text"  
                wire:model="amount"  
                id="amount"  
                class="w-full border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring-2 focus:ring-blue-500"  
            >  
            @error('amount')  
                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>  
            @enderror  
        </div>  
  
        <div class="mb-4">  
            <label for="interest_rate" class="block text-sm font-medium text-gray-700 mb-1">  
                Interest Rate (%):  
            </label>  
            <input  
                type="text"  
                wire:model="interest_rate"  
                id="interest_rate"  
                class="w-full border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring-2 focus:ring-blue-500"  
            >  
            @error('interest_rate')  
                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>  
            @enderror  
        </div>  
  
        <div class="mb-4">  
            <label for="term" class="block text-sm font-medium text-gray-700 mb-1">  
                Term (months):  
            </label>  
            <input  
                type="text"  
                wire:model="term"  
                id="term"  
                class="w-full border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring-2 focus:ring-blue-500"  
            >  
            @error('term')  
                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>  
            @enderror  
        </div>  
  
        <div class="mb-4">  
            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">  
                Start Date:  
            </label>  
            <input  
                type="date"  
                wire:model="start_date"  
                id="start_date"  
                class="w-full border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring-2 focus:ring-blue-500"  
            >  
            @error('start_date')  
                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>  
            @enderror  
        </div>  
  
        <div class="flex items-center space-x-2 mt-4">  
            {{-- Button "Create" --}}  
            <button  
                type="submit"  
                wire:loading.remove  
                wire:target="store"  
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 focus:outline-none"  
                @if($updateMode) style="display: none;" @endif  
            >  
                Create  
            </button>  
  
            {{-- Button "Update" --}}  
            <button  
                type="submit"  
                wire:loading.remove  
                wire:target="update"  
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 focus:outline-none"  
                @if(!$updateMode) style="display: none;" @endif  
            >  
                Update  
            </button>  
  
            {{-- Spinner for "store" --}}  
            <div  
                wire:loading  
                wire:target="store"  
                class="flex items-center text-blue-600"  
                @if($updateMode) style="display: none;" @endif  
            >  
                <svg class="animate-spin h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24">  
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />  
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />  
                </svg>  
                <span>Creating...</span>  
            </div>  
  
            {{-- Spinner for "update" --}}  
            <div  
                wire:loading  
                wire:target="update"  
                class="flex items-center text-blue-600"  
                @if(!$updateMode) style="display: none;" @endif  
            >  
                <svg class="animate-spin h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24">  
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />  
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />  
                </svg>  
                <span>Updating...</span>  
            </div>  
  
            {{-- Cancel --}}  
            @if($updateMode)  
                <button  
                    type="button"  
                    wire:click="cancel"  
                    class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 focus:outline-none"  
                >  
                    Cancel  
                </button>  
            @endif  
        </div>  
    </form>  
  
    <!-- List of loans -->  
    @if($loans->isEmpty())  
        <p class="text-center italic">No records</p>  
    @else  
        <div class="overflow-x-auto">  
            <table class="min-w-full bg-white border border-gray-200 text-sm sm:text-base">  
                <thead class="bg-gray-100 border-b">  
                <tr>  
                    <!-- Columns that should be visible even on a phone -->  
                    <th class="px-4 py-2 border-r text-left">ID</th>  
                    <th class="px-4 py-2 border-r text-left">Amount</th>  
  
                    <!-- Columns hidden on very small screens -->  
                    <th class="px-4 py-2 border-r text-left hidden sm:table-cell">  
                        Rate (%)  
                    </th>  
                    <th class="px-4 py-2 border-r text-left hidden sm:table-cell">  
                        Term (months)  
                    </th>  
  
                    <!-- Columns hidden until md screens -->  
                    <th class="px-4 py-2 border-r text-left hidden md:table-cell">  
                        Start Date  
                    </th>  
                    <th class="px-4 py-2 text-center">Actions</th>  
                </tr>  
                </thead>  
                <tbody>  
                @foreach($loans as $loan)  
                    <tr class="border-b border-gray-200">  
                        <td class="px-4 py-2 border-r">{{ $loan->id }}</td>  
                        <td class="px-4 py-2 border-r">{{ $loan->amount }}</td>  
                        <td class="px-4 py-2 border-r hidden sm:table-cell">  
                            {{ $loan->interest_rate }}  
                        </td>  
                        <td class="px-4 py-2 border-r hidden sm:table-cell">  
                            {{ $loan->term }}  
                        </td>  
                        <td class="px-4 py-2 border-r hidden md:table-cell">  
                            {{ \Carbon\Carbon::parse($loan->start_date)->format('d.m.Y') }}  
                        </td>  
                        <td class="px-4 py-2 text-center whitespace-nowrap" style="overflow: visible;">  
                            <!-- Button: Show the schedule -->  
                            <button  
                                wire:click="openPdfPhp({{ $loan->id }})"  
                                class="inline-block bg-blue-500 text-white px-2 py-1 rounded mr-1 hover:bg-blue-600 focus:outline-none"  
                            >  
                                Show schedule  
                            </button>  
                            <button  
                                wire:click="edit({{ $loan->id }})"  
                                class="inline-block bg-yellow-400 text-black px-2 py-1 rounded mr-1 hover:bg-yellow-500 focus:outline-none"  
                            >  
                                Edit  
                            </button>  
                            <button  
                                wire:click="delete({{ $loan->id }})"  
                                class="inline-block bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600 focus:outline-none"  
                                onclick="return confirm('Are you sure you want to delete?')"  
                            >  
                                Delete  
                            </button>  
                        </td>  
                    </tr>  
                @endforeach  
                </tbody>  
            </table>  
        </div>  
    @endif  
  
    <!-- PDF Modal -->  
    @if($showPrintModal)  
        <div  
            x-data="{ open: @entangle('showPrintModal') }"  
            x-show="open"  
            x-transition  
            class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-75 z-50"  
            style="display: none;"  
        >  
            <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-4xl sm:w-full">  
                <div class="px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">  
                    <button  
                        type="button"  
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm"  
                        onclick="printPdf()"  
                    >  
                        Print  
                    </button>  
                    <button  
                        type="button"  
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"  
                        wire:click="cancelModal"  
                    >  
                        Close  
                    </button>  
                </div>  
                <div class="px-4 py-5 sm:p-6">  
                    <!-- Loading indicator -->  
                    <div  
                        id="loading"  
                        style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-size: 20px; color: #333;"  
                        x-init='showLoading()'  
                    >  
                        Loading...  
                    </div>  
                    <div class="mb-4">  
                        <iframe id="pdfFrame" src="{{ $pdfUrl }}" width="100%" height="600px" class="border rounded" style="display: none;"></iframe>  
                    </div>  
                </div>  
            </div>  
        </div>  
    @endif  
</div>  
  
@push('scripts')  
<script>  
    function showLoading() {  
        // When the iframe has loaded, hide the loading text and show the iframe  
        var iframe = document.getElementById('pdfFrame');  
        var loading = document.getElementById('loading');  
        iframe.onload = function() {  
            loading.style.display = 'none';  // Hide loading text  
            iframe.style.display = 'block';  // Show iframe  
        };  
    }  
  
    function printPdf() {  
        const pdfFrame = document.querySelector('iframe');  
        if (pdfFrame) {  
            pdfFrame.contentWindow.focus();  
            pdfFrame.contentWindow.print();  
        } else {  
            console.error('Cannot find PDF iframe.');  
        }  
    }  
</script>  
@endpush  
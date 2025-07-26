<div>
    <!-- Saiti, kuru spiežot tiek atvērts modālais logs -->
    <!-- <a href="#" wire:click.prevent="openModal" class="text-blue-500 underline hover:text-blue-700">Iestatīt kā administrātoru</a> -->
    <!-- Saite/poga uz modālo logu -->
    <a href="#" wire:click.prevent="openModal" class="inline-block bg-blue-500 text-white font-bold py-2 px-4 rounded hover:bg-blue-700">
    ({{ Auth::user()->email }}) Iestatīt kā administrātoru
    </a>

    <!-- Modālais logs -->
    @if($isOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50">
            <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-sm mx-auto relative">
                <!-- Modālā loga aizvēršanas poga (X) -->
                <button wire:click="closeModal" class="absolute top-2 right-2 text-gray-400 hover:text-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>

                <!-- Modālā loga virsraksts -->
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Ievadiet paroli</h2>

                <!-- Ziņojums par kļūdainu paroli -->
                @if ($errorMessage)
                    <div class="bg-red-100 text-red-500 p-2 rounded mb-4">{{ $errorMessage }}</div>
                @endif
                @if ($infoMessage)
                    <div class="bg-green-100 text-green-500 p-2 rounded mb-4">{{ $infoMessage }}</div>
                @endif
                <!-- Paroles ievades lauks -->
                <input type="password" wire:model="password" class="border border-gray-300 p-2 w-full rounded mb-4 focus:ring focus:ring-blue-200" placeholder="Parole" autocomplete="new-password">

                <!-- Pogu bloks -->
                <div class="flex justify-end space-x-2">
                    <button wire:click="closeModal" class="bg-gray-300 hover:bg-gray-400 text-gray-700 py-2 px-4 rounded focus:outline-none">
                        Atcelt
                    </button>
                    <button wire:click="checkPassword" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded focus:outline-none">
                        Apstiprināt
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Ziņojums par pareizu paroli -->
@if (session()->has('message'))
    <div class="bg-green-500 text-white p-3 rounded mt-5">
        {{ session('message') }}
    </div>
@endif

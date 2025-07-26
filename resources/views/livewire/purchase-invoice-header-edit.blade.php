<div>
    <!-- Modālais logs -->
    @if($isOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50">
            <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-sm mx-auto relative" style="min-width: 600px; width: 100%;">
                <!-- Modālā loga aizvēršanas poga (X) -->
                <button wire:click="closeModal" class="absolute top-2 right-2 text-gray-400 hover:text-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>

                <!-- Modālā loga saturs -->
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Rediģēt pirkuma rēķinu</h5>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="saveData">
                            <div class="mb-4">
                                <label for="invoice_number" class="block text-gray-700">Rēķina numurs</label>
                                <input type="text" id="invoice_number" wire:model="invoice_number" class="w-full border-gray-300 rounded">
                                @error('invoice_number') <span class="text-red-500">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-4">
                                <label for="invoice_date" class="block text-gray-700">Rēķina datums</label>
                                <input type="date" id="invoice_date" wire:model="invoice_date" class="w-full border-gray-300 rounded">
                                @error('invoice_date') <span class="text-red-500">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-4">
                                <label for="buyer_name" class="block text-gray-700">Pircēja vārds</label>
                                <input type="text" id="buyer_name" wire:model="buyer_name" class="w-full border-gray-300 rounded">
                            </div>

                            <div class="mb-4">
                                <label for="buyer_address" class="block text-gray-700">Pircēja adrese</label>
                                <input type="text" id="buyer_address" wire:model="buyer_address" class="w-full border-gray-300 rounded">
                            </div>

                            <div class="mb-4">
                                <label for="seller_name" class="block text-gray-700">Pārdevēja vārds</label>
                                <input type="text" id="seller_name" wire:model="seller_name" class="w-full border-gray-300 rounded">
                            </div>

                            <div class="mb-4">
                                <label for="seller_address" class="block text-gray-700">Pārdevēja adrese</label>
                                <input type="text" id="seller_address" wire:model="seller_address" class="w-full border-gray-300 rounded">
                            </div>

                            <div class="mb-4">
                                <label for="waybill_number" class="block text-gray-700">Pavadzīmes numurs</label>
                                <input type="text" id="waybill_number" wire:model="waybill_number" class="w-full border-gray-300 rounded">
                            </div>

                            <div class="mb-4">
                                <label for="waybill_date" class="block text-gray-700">Pavadzīmes datums</label>
                                <input type="date" id="waybill_date" wire:model="waybill_date" class="w-full border-gray-300 rounded">
                            </div>

                            <div class="mb-4">
                                <label for="additional_info" class="block text-gray-700">Papildus informācija</label>
                                <textarea id="additional_info" wire:model="additional_info" class="w-full border-gray-300 rounded"></textarea>
                            </div>
                        </form>
                    </div>

                    <!-- Pogu bloks -->
                    <div class="flex justify-end space-x-2">
                        <button wire:click="closeModal" class="bg-gray-300 hover:bg-gray-400 text-gray-700 py-2 px-4 rounded focus:outline-none">
                            Atcelt
                        </button>
                        <button wire:click="saveData" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded focus:outline-none">
                            Apstiprināt
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>


<div id="position-list-form">
    <!-- Modālais logs -->
    @if($isOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-lg mx-auto relative" style="min-width: 1000px; width: 100%; max-height: 90vh; overflow-y: auto;">
            <!-- Modālā loga aizvēršanas poga (X) -->
            <button wire:click="closeModal" class="absolute top-2 right-2 text-gray-400 hover:text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <div x-data="{ tab: 'position' }" class="modal-content" style="padding: 0px;">
                <!-- Tabu izvēlne -->
                <div class="border-b border-gray-200 mb-4">
                    <nav class="flex space-x-4" aria-label="Tabs">
                        <a href="#" @click.prevent="tab = 'position'" :class="{'border-blue-500 text-blue-600': tab === 'position', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'position'}" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            <i class="fas fa-user-shield"></i> Amata informācija
                        </a>
                        <!--<a href="#" @click.prevent="tab = 'supplier'" :class="{'border-blue-500 text-blue-600': tab === 'supplier', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'supplier'}" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            <i class="fas fa-truck"></i> Piegādātāja un papildus informācija
                        </a>
                        <a href="#" @click.prevent="tab = 'lines'" :class="{'border-blue-500 text-blue-600': tab === 'lines', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'lines'}" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            <i class="fas fa-list"></i> Dokumenta Ieraksti
                        </a>-->
                    </nav>
                </div>

                <!-- Tabu saturs -->
                <div class="modal-body overflow-y-auto" style="max-height: 60vh;">
                    <div x-show="tab === 'position'">
                        <div class="border-b pb-4 mb-4">
                            <h6 class="text-lg font-bold mb-2"><i class="fas fa-user-shield"></i> Amata informācija</h6>
                            <div class="grid grid-cols-6 gap-4" style="margin-left:3px; margin-right:3px;">

                                <div class="mb-4 col-span-3">
                                    <label for="position_name" class="block text-sm font-medium text-gray-700">Amats:</label>
                                    <div x-data x-init=''>
                                        <input type="text" x-ref="position_name" id="position_name" wire:model.blur="position.position_name" class="@error('position.position_name') border-red-500 @else border-gray-300 @enderror mt-1 block w-full border border-gray-300 rounded-md shadow-sm" autocomplete="off" />
                                    </div>
                                    @error('position.position_name')
                                        <div hidden x-init="$wire.dispatch('showError', ['{{ $message }}']);"></div>
                                    @enderror
                                </div>
                                <div class="mb-4 col-span-2" style="margin: 0px !important;">
                                    <label for="department_id" class="block text-sm font-medium text-gray-700">Pakļautība:</label>
                                    <select id="department_id" wire:model.live="position.position_for_department_id" class="@error('position.position_for_department_id') border-red-500 @else border-gray-300 @enderror mt-1 block w-full border border-gray-300 rounded-md shadow-sm">
                                        <option value="">Nav</option>
                                        @foreach ($departments as $department)
                                            <option value="{{ $department['id'] }}" {{ $position['position_for_department_id'] === $department['id'] ? 'selected' : '' }}>
                                                {{ $department['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('position.position_for_department_id') 
                                        <div hidden x-init="$wire.dispatch('showError', ['{{ $message }}']);"></div>
                                    @enderror
                                </div>
                                <div class="mb-4 col-span-1">
                                    <label for="is_head" class="block text-sm font-medium text-gray-700">Vadītājs:</label>                                   
                                    <select id="is_head" wire:model.live="position.is_head" class="@error('position.is_head') border-red-500 @else border-gray-300 @enderror mt-1 block w-full border border-gray-300 rounded-md shadow-sm">
                                        <option value=0 {{ !$position['is_head'] ? 'selected' : '' }}>Ne</option>
                                        <option value=1 {{ $position['is_head'] ? 'selected' : '' }}>Jā</option>
                                    </select>
                                    @error('position.is_head')
                                        <div hidden x-init="$wire.dispatch('showError', ['{{ $message }}']);"></div>
                                    @enderror
                                </div>           
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Pogu bloks -->
            <div class="flex justify-end space-x-2 p-4 bg-gray-100 rounded-b-lg">
                <button wire:click="closeModal" class="bg-gray-300 hover:bg-gray-400 text-gray-700 py-2 px-4 rounded focus:outline-none">
                    Atcelt
                </button>
                <button wire:click="saveData" wire:loading.attr="disabled" class="saveDataButton bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded focus:outline-none">
                    Apstiprināt
                    <div wire:loading wire:target="saveData itemSelected">
                        <i class="fa fa-spinner fa-spin"></i>
                    </div>
                </button>
                <!-- <button wire:click="openPdfPhp" class="bg-gray-300 hover:bg-gray-400 text-gray-700 py-2 px-4 rounded focus:outline-none">
                        Apstiprināt/Drukāt
                    </button> -->
            </div>
        </div>
    </div>
    @endif
</div>



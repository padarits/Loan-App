<div id="department-list-form">
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

            <div x-data="{ tab: 'department' }" class="modal-content" style="padding: 0px;">
                <!-- Tabu izvēlne -->
                <div class="border-b border-gray-200 mb-4">
                    <nav class="flex space-x-4" aria-label="Tabs">
                        <a href="#" @click.prevent="tab = 'department'" :class="{'border-blue-500 text-blue-600': tab === 'department', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'department'}" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            <i class="fas fa-laptop-code"></i> Departementa informācija
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
                    <!-- Dokumenta un Saņēmēja informācija Tab -->
                    <div x-show="tab === 'department'">
                        <div class="border-b pb-4 mb-4">
                            <h6 class="text-lg font-bold mb-2"><i class="fas fa-box"></i> Departementa informācija</h6>
                            <div class="grid grid-cols-6 gap-4" style="margin-left:3px; margin-right:3px;">

                                <div class="mb-4">
                                    <label for="code" class="block text-sm font-medium text-gray-700">Kods:</label>
                                    <div x-data x-init=''>
                                        <input type="text" x-ref="code" id="code" wire:model.blur="department.code" class="@error('department.code') border-red-500 @else border-gray-300 @enderror mt-1 block w-full border border-gray-300 rounded-md shadow-sm" autocomplete="off" />
                                    </div>
                                    @error('department.code')
                                        <div hidden x-init="$wire.dispatch('showError', ['{{ $message }}']);"></div>
                                    @enderror
                                </div>
                                <div class="mb-4 col-span-3">
                                    <label for="name" class="block text-sm font-medium text-gray-700">Nosaukums:</label>
                                    <div x-data x-init=''>
                                        <input type="text" x-ref="name" id="name" wire:model.blur="department.name" class="@error('department.name') border-red-500 @else border-gray-300 @enderror mt-1 block w-full border border-gray-300 rounded-md shadow-sm" autocomplete="off" />
                                    </div>
                                    @error('department.name')
                                        <div hidden x-init="$wire.dispatch('showError', ['{{ $message }}']);"></div>
                                    @enderror
                                </div>
                                <div class="mb-4 col-span-2" style="margin: 0px !important;">
                                    <label for="parent_code" class="block text-sm font-medium text-gray-700">Pakļautība:</label>
                                    <select id="parent_code" wire:model.live="department.parent_code" class="@error('department.parent_code') border-red-500 @else border-gray-300 @enderror mt-1 block w-full border border-gray-300 rounded-md shadow-sm">
                                        <option value="">Nav</option>
                                        @foreach ($departments as $department)
                                            <option value="{{ $department['code'] }}" {{ $department['code'] === $department['parent_code'] ? 'selected' : '' }}>
                                                {{ $department['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('department.parent_code') 
                                        <div hidden x-init="$wire.dispatch('showError', ['{{ $message }}']);"></div>
                                    @enderror
                                </div>
                                <div class="mb-4 col-span-2">
                                    <label for="contact_person" class="block text-sm font-medium text-gray-700">Kontaktpersona:</label>
                                    <div x-data x-init=''>
                                        <input type="text" x-ref="contact_person" id="contact_person" wire:model.blur="department.contact_person" class="@error('department.contact_person') border-red-500 @else border-gray-300 @enderror mt-1 block w-full border border-gray-300 rounded-md shadow-sm" autocomplete="off" />
                                    </div>
                                    @error('department.contact_person')
                                        <div hidden x-init="$wire.dispatch('showError', ['{{ $message }}']);"></div>
                                    @enderror
                                </div>
                                <div class="mb-4 col-span-2">
                                    <label for="email" class="block text-sm font-medium text-gray-700">E-pasts:</label>
                                    <div x-data x-init=''>
                                        <input type="text" x-ref="email" id="email" wire:model.blur="department.email" class="@error('department.email') border-red-500 @else border-gray-300 @enderror mt-1 block w-full border border-gray-300 rounded-md shadow-sm" autocomplete="off" />
                                    </div>
                                    @error('department.email')
                                        <div hidden x-init="$wire.dispatch('showError', ['{{ $message }}']);"></div>
                                    @enderror
                                </div>     
                                <div class="mb-4 col-span-2">
                                    <label for="phone" class="block text-sm font-medium text-gray-700">Telefons:</label>
                                    <div x-data x-init=''>
                                        <input type="text" x-ref="phone" id="phone" wire:model.blur="department.phone" class="@error('department.phone') border-red-500 @else border-gray-300 @enderror mt-1 block w-full border border-gray-300 rounded-md shadow-sm" autocomplete="off" />
                                    </div>
                                    @error('department.phone')
                                        <div hidden x-init="$wire.dispatch('showError', ['{{ $message }}']);"></div>
                                    @enderror
                                </div>    
                                <div class="mb-4 col-span-3">
                                    <label for="address" class="block text-sm font-medium text-gray-700">Adrese:</label>
                                    <div x-data x-init=''>
                                        <input type="text" x-ref="address" id="address" wire:model.blur="department.address" class="@error('department.address') border-red-500 @else border-gray-300 @enderror mt-1 block w-full border border-gray-300 rounded-md shadow-sm" autocomplete="off" />
                                    </div>
                                    @error('department.address')
                                        <div hidden x-init="$wire.dispatch('showError', ['{{ $message }}']);"></div>
                                    @enderror
                                </div>   
                                <div class="mb-4 col-span">
                                    <label for="city" class="block text-sm font-medium text-gray-700">Pilseta:</label>
                                    <div x-data x-init=''>
                                        <input type="text" x-ref="city" id="city" wire:model.blur="department.city" class="@error('department.city') border-red-500 @else border-gray-300 @enderror mt-1 block w-full border border-gray-300 rounded-md shadow-sm" autocomplete="off" />
                                    </div>
                                    @error('department.city')
                                        <div hidden x-init="$wire.dispatch('showError', ['{{ $message }}']);"></div>
                                    @enderror
                                </div>    
                                <div class="mb-4 col-span">
                                    <label for="country" class="block text-sm font-medium text-gray-700">Valsts:</label>
                                    <div x-data x-init=''>
                                        <input type="text" x-ref="country" id="country" wire:model.blur="department.country" class="@error('department.country') border-red-500 @else border-gray-300 @enderror mt-1 block w-full border border-gray-300 rounded-md shadow-sm" autocomplete="off" />
                                    </div>
                                    @error('department.country')
                                        <div hidden x-init="$wire.dispatch('showError', ['{{ $message }}']);"></div>
                                    @enderror
                                </div>  
                                <div class="mb-4 col-span">
                                    <label for="zip" class="block text-sm font-medium text-gray-700">Pasta Indeks:</label>
                                    <div x-data x-init=''>
                                        <input type="text" x-ref="zip" id="zip" wire:model.blur="department.zip" class="@error('department.zip') border-red-500 @else border-gray-300 @enderror mt-1 block w-full border border-gray-300 rounded-md shadow-sm" autocomplete="off" />
                                    </div>
                                    @error('department.zip')
                                        <div hidden x-init="$wire.dispatch('showError', ['{{ $message }}']);"></div>
                                    @enderror
                                </div>  
                                <div class="mb-4 col-span-6">
                                    <label for="description" class="block text-sm font-medium text-gray-700">Informācija:</label>
                                    <div x-data x-init=''>
                                        <input type="text" x-ref="description" id="description" wire:model.blur="department.description" class="@error('department.description') border-red-500 @else border-gray-300 @enderror mt-1 block w-full border border-gray-300 rounded-md shadow-sm" autocomplete="off" />
                                    </div>
                                    @error('department.description')
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

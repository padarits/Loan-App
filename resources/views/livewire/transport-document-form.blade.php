<div>
    <!-- Modālais logs -->
    @if($isOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50">
        <div class="jjModalForm bg-white rounded-lg shadow-lg p-6 w-full max-w-lg mx-auto relative" style="min-width: 1000px; width: 100%; max-height: 90vh; overflow-y: auto;">   
        <!-- Modālā loga aizvēršanas poga (X) -->
            <button wire:click="closeModal" class="absolute top-2 right-2 text-gray-400 hover:text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <div x-data="{ tab: 'document' }" class="modal-content">
                <!-- Tabu izvēlne -->
                <div class="border-b border-gray-200 mb-4">
                    <nav class="flex space-x-4" aria-label="Tabs">
                        <a href="#" @click.prevent="tab = 'document'" :class="{'border-blue-500 text-blue-600': tab === 'document', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'document'}" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            <i class="fas fa-home"></i> Dokumenta un Saņēmēja informācija
                        </a>
                        <a href="#" @click.prevent="tab = 'supplier'" :class="{'border-blue-500 text-blue-600': tab === 'supplier', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'supplier'}" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            <i class="fas fa-truck"></i> Piegādātāja un papildus informācija
                        </a>
                        <a href="#" @click.prevent="tab = 'lines'" :class="{'border-blue-500 text-blue-600': tab === 'lines', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'lines'}" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            <i class="fas fa-list"></i> Dokumenta Ieraksti
                        </a>
                    </nav>
                </div>

                <!-- Tabu saturs -->
                <div class="modal-body overflow-y-auto" style="max-height: 60vh;">
                    <!-- Dokumenta un Saņēmēja informācija Tab -->
                    <div x-show="tab === 'document'">
                        <div class="border-b pb-4 mb-4">
                            <h6 class="text-lg font-bold mb-2"><i class="fas fa-file-alt"></i> Dokumenta Informācija</h6>
                            <div class="grid grid-cols-4 gap-4">
                                <div class="mb-4">
                                    <label for="document_number" class="block text-sm font-medium text-gray-700">Dokumenta Numurs:</label>
                                    <input type="text" id="document_number" wire:model.blur="document_number" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" autocomplete="off">
                                    @error('document_number') 
                                        <span class="text-red-500">{{ $message }}</span> 
                                        <div hidden x-init="$wire.dispatch('showError', ['{{ $message }}']);"></div>
                                    @enderror
                                </div>
                                <div class="mb-4">
                                    <label for="document_date" class="block text-sm font-medium text-gray-700">Dokumenta Datums:</label>
                                    <div x-data x-init='$( "#document_date" ).datepicker(
                                        {
                                            setDate: $refs.datepicker,
                                            regional: "lv",
                                            dateformat: "dd.mm.yyyy",
                                            showWeek: true,
                                            firstDay: 1,
                                            showButtonPanel: true,
                                            altField: "#document_date_a",
                                            altFormat: "yy-mm-dd",
                                        }).on("change", function(value) {
                                            $wire.set("document_date", $(this).val()); 
                                        });'>
                                        <input type="text" x-ref="datepicker" id="document_date" wire:model.blur="date" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" autocomplete="off"/>
                                    </div>
                                    @error('document_date') 
                                        <span class="text-red-500">{{ $message }}</span> 
                                        <div hidden x-init="$wire.dispatch('showError', ['{{ $message }}']);"></div>
                                    @enderror
                                </div>

                                <!-- Jaunais kravas statusa lauks -->
                                <div class="mb-4">
                                    <label for="status" class="block text-sm font-medium text-gray-700">Kravas Statuss:</label>
                                    <select id="status" wire:model.blur="status" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm">
                                        <option value="010-new">Jauns</option>
                                        <option value="020-prepared">Sagatavots</option>
                                        <option value="030-in_transit">Ceļā</option>
                                        <option value="040-received">Saņemts</option>
                                        <option value="050-waiting">Gaida</option>
                                        <option value="060-canceled">Atcēlts</option>
                                    </select>
                                    @error('status') 
                                        <span class="text-red-500">{{ $message }}</span> 
                                        <div hidden x-init="$wire.dispatch('showError', ['{{ $message }}']);"></div>
                                    @enderror
                                </div>

                                <!--vehicle_registration_number-->
                                <div class="mb-4">
                                    <label for="vehicle_registration_number" class="block text-sm font-medium text-gray-700">Transportlīdzekļa Numurs:</label>
                                    <input type="text" id="vehicle_registration_number" wire:model.blur="vehicle_registration_number" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" autocomplete="off">
                                    @error('vehicle_registration_number') 
                                        <span class="text-red-500">{{ $message }}</span> 
                                        <div hidden x-init="$wire.dispatch('showError', ['{{ $message }}']);"></div>
                                    @enderror
                                </div>
                                </div>
                            </div>
                            <div class="border-b pb-4 mb-4">
                            <h6 class="text-lg font-bold mb-2"><i class="fas fa-home"></i> Saņēmēja Informācija</h6>
                            <div class="grid grid-cols-2 gap-4">

                                <div class="mb-4">
                                    <label for="receiver_reg_number" class="block text-sm font-medium text-gray-700">Saņēmēja Reģistrācijas Numurs:</label>
                                    <div x-data x-init='              
                                        $( "#receiver_reg_number" ).autocomplete({
                                            source: function(request, response) {
                                                $.ajax({
                                                    url: "{{ route("search-receiver-reg-number") }}",
                                                    data: {
                                                        query: request.term
                                                    },
                                                    success: function(data) {
                                                        response($.map(data, function(item) {
                                                            return {
                                                                label: item.receiver_reg_number + " - " + item.receiver_name,  // Divu kolonnu attēlošana
                                                                code: item.receiver_reg_number, // Tas, kas tiks ievadīts laukā
                                                                name: item.receiver_name // Papildus informācija, ko var izmantot
                                                            };
                                                        }));                                                
                                                    }
                                                });
                                            },
                                            appendTo: "body",
                                            open: function(event, ui) {
                                                var $input = $(this),
                                                    $results = $input.autocomplete("widget"),
                                                    offset = $input.offset();
                                                $results.css({
                                                    top: offset.top + $input.outerHeight(),
                                                    left: offset.left
                                                });
                                            },
                                            minLength: 1,
                                            select: function(event, ui) {
                                                // Handle the select event
                                                Livewire.dispatch("receiverSelected", [ui.item.code, ui.item.name]);  // Dispatching custom event
                                                event.preventDefault(); // Prevent automatic insertion
                                                $(this).val(ui.item.code);
                                            }
                                        }).focus(function () {
                                            $(this).autocomplete("search", $(this).val());  // Triggers the dropdown to appear on focus
                                        });'>
                                        <input type="text" id="receiver_reg_number" wire:model.blur="receiver_reg_number" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" autocomplete="off">
                                    </div>
                                    @error('receiver_reg_number') 
                                        <span class="text-red-500">{{ $message }}</span> 
                                        <div hidden x-init="$wire.dispatch('showError', ['{{ $message }}']);"></div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="receiver_name" class="block text-sm font-medium text-gray-700">Saņēmēja Nosaukums:</label>
                                    <input type="text" id="receiver_name" wire:model.blur="receiver_name" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" autocomplete="off">
                                    @error('receiver_name') 
                                        <span class="text-red-500">{{ $message }}</span> 
                                        <div hidden x-init="$wire.dispatch('showError', ['{{ $message }}']);"></div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="receiver_address" class="block text-sm font-medium text-gray-700">Saņēmēja Adrese:</label>
                                    <div x-data x-init='              
                                        $( "#receiver_address" ).autocomplete({
                                            source: function(request, response) {
                                                $.ajax({
                                                    url: "{{ route("search-receiver-address") }}",
                                                    data: {
                                                        reg_number: "{{ $receiver_reg_number }}", //request.term
                                                        name: "{{ $receiver_name }}",
                                                    },
                                                    success: function(data) {
                                                        response($.map(data, function(item) {
                                                            return {
                                                                label: item.receiver_address + " - " + item.receiving_location,  // Divu kolonnu attēlošana
                                                                code: item.receiver_address, // Tas, kas tiks ievadīts laukā
                                                                name: item.receiving_location // Papildus informācija, ko var izmantot
                                                            };
                                                        }));                                                
                                                    }
                                                });
                                            },
                                            appendTo: "body",
                                            open: function(event, ui) {
                                                var $input = $(this),
                                                    $results = $input.autocomplete("widget"),
                                                    offset = $input.offset();
                                                $results.css({
                                                    top: offset.top + $input.outerHeight(),
                                                    left: offset.left
                                                });
                                            },
                                            minLength: 0,
                                            select: function(event, ui) {
                                                // Handle the select event
                                                Livewire.dispatch("receiverAddressSelected", [ui.item.code, ui.item.name]);  // Dispatching custom event
                                                event.preventDefault(); // Prevent automatic insertion
                                                $(this).val(ui.item.code);
                                            }
                                        }).focus(function () {
                                            $(this).autocomplete("search", "");  // Triggers the dropdown to appear on focus
                                        });'>
                                        <input type="text" id="receiver_address" wire:model.blur="receiver_address" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" autocomplete="off">
                                    </div>
                                    @error('receiver_address') 
                                        <span class="text-red-500">{{ $message }}</span> 
                                        <div hidden x-init="$wire.dispatch('showError', ['{{ $message }}']);"></div>
                                    @enderror
                                </div>
                                <div class="mb-4">
                                    <label for="receiving_location" class="block text-sm font-medium text-gray-700">Saņemšanas Vieta:</label>
                                    <input type="text" id="receiving_location" wire:model.blur="receiving_location" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" autocomplete="off">
                                    @error('receiving_location') 
                                        <span class="text-red-500">{{ $message }}</span> 
                                        <div hidden x-init="$wire.dispatch('showError', ['{{ $message }}']);"></div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Piegādātāja un papildus informācija Tab -->
                    <div x-show="tab === 'supplier'">
                        <div class="border-b pb-4 mb-4">
                            <h6 class="text-lg font-bold mb-2"><i class="fas fa-truck"></i> Piegādātāja Informācija</h6>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="mb-4">
                                    <label for="supplier_reg_number" class="block text-sm font-medium text-gray-700">Piegādātāja Reģistrācijas Numurs:</label>
                                    <div x-data x-init='              
                                        $( "#supplier_reg_number" ).autocomplete({
                                            source: function(request, response) {
                                                $.ajax({
                                                    url: "{{ route("search-supplier-reg-number") }}",
                                                    data: {
                                                        query: request.term
                                                    },
                                                    success: function(data) {
                                                        response($.map(data, function(item) {
                                                            return {
                                                                label: item.supplier_reg_number + " - " + item.supplier_name,  // Divu kolonnu attēlošana
                                                                code: item.supplier_reg_number, // Tas, kas tiks ievadīts laukā
                                                                name: item.supplier_name // Papildus informācija, ko var izmantot
                                                            };
                                                        }));                                                
                                                    }
                                                });
                                            },
                                            appendTo: "body",
                                            open: function(event, ui) {
                                                var $input = $(this),
                                                    $results = $input.autocomplete("widget"),
                                                    offset = $input.offset();
                                                $results.css({
                                                    top: offset.top + $input.outerHeight(),
                                                    left: offset.left
                                                });
                                            },
                                            minLength: 1,
                                            select: function(event, ui) {
                                                // Handle the select event
                                                Livewire.dispatch("supplierSelected", [ui.item.code, ui.item.name]);  // Dispatching custom event
                                                event.preventDefault(); // Prevent automatic insertion
                                                $(this).val(ui.item.code);
                                            }
                                        }).focus(function () {
                                            $(this).autocomplete("search", $(this).val());  // Triggers the dropdown to appear on focus
                                        });'>
                                        <input type="text" id="supplier_reg_number" wire:model.blur="supplier_reg_number" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" autocomplete="off">
                                    </div>
                                    @error('supplier_reg_number') 
                                        <span class="text-red-500">{{ $message }}</span> 
                                        <div hidden x-init="$wire.dispatch('showError', ['{{ $message }}']);"></div>
                                    @enderror
                                </div>
                                <div class="mb-4">
                                    <label for="supplier_name" class="block text-sm font-medium text-gray-700">Piegādātāja Nosaukums:</label>
                                    <input type="text" id="supplier_name" wire:model.blur="supplier_name" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" autocomplete="off">
                                    @error('supplier_name') 
                                        <span class="text-red-500">{{ $message }}</span> 
                                        <div hidden x-init="$wire.dispatch('showError', ['{{ $message }}']);"></div>
                                    @enderror
                                </div>
                                <div class="mb-4">
                                    <label for="supplier_address" class="block text-sm font-medium text-gray-700">Piegādātāja Adrese:</label>
                                    <div x-data x-init='              
                                        $( "#supplier_address" ).autocomplete({
                                            source: function(request, response) {
                                                $.ajax({
                                                    url: "{{ route("search-supplier-address") }}",
                                                    data: {
                                                        reg_number: "{{ $supplier_reg_number }}", //request.term
                                                        name: "{{ $supplier_name }}",
                                                    },
                                                    success: function(data) {
                                                        response($.map(data, function(item) {
                                                            return {
                                                                label: item.supplier_address ,  // Divu kolonnu attēlošana
                                                                address: item.supplier_address, // Tas, kas tiks ievadīts laukā
                                                                //name: item.receiving_location // Papildus informācija, ko var izmantot
                                                            };
                                                        }));                                                
                                                    }
                                                });
                                            },
                                            appendTo: "body",
                                            open: function(event, ui) {
                                                var $input = $(this),
                                                    $results = $input.autocomplete("widget"),
                                                    offset = $input.offset();
                                                $results.css({
                                                    top: offset.top + $input.outerHeight(),
                                                    left: offset.left
                                                });
                                            },
                                            minLength: 0,
                                            select: function(event, ui) {
                                                // Handle the select event
                                                Livewire.dispatch("supplierAddressSelected", [ui.item.address]);  // Dispatching custom event
                                                event.preventDefault(); // Prevent automatic insertion
                                                $(this).val(ui.item.address);                                                
                                            }
                                        }).focus(function () {
                                            $(this).autocomplete("search", "");  // Triggers the dropdown to appear on focus
                                        });'>
                                        <input type="text" id="supplier_address" wire:model.blur="supplier_address" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" autocomplete="off">
                                    </div>
                                    @error('supplier_address')
                                        <span class="text-red-500">{{ $message }}</span> 
                                        <div hidden x-init="$wire.dispatch('showError', ['{{ $message }}']);"></div>
                                    @enderror
                                </div>
                                <div class="mb-4">
                                    <label for="issuer_name" class="block text-sm font-medium text-gray-700">Izsniedzēja Vārds un Uzvārds:</label>
                                    <input type="text" id="issuer_name" wire:model.blur="issuer_name" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" autocomplete="off">
                                    @error('issuer_name') 
                                        <span class="text-red-500">{{ $message }}</span> 
                                        <div hidden x-init="$wire.dispatch('showError', ['{{ $message }}']);"></div>
                                    @enderror
                                </div>
                                <div class="mb-4 col-span-2">
                                    <label for="additional_info" class="text-lg font-bold mb-2">Papildu Informācija:</label>
                                    <textarea id="additional_info" wire:model.blur="additional_info" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" autocomplete="off"></textarea>
                                    @error('additional_info') 
                                        <span class="text-red-500">{{ $message }}</span> 
                                        <div hidden x-init="$wire.dispatch('showError', ['{{ $message }}']);"></div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Dokumenta līnijas Tab -->
                    <div x-show="tab === 'lines'">
                        <!-- Livewire tabulas -->
                        <h6 class="text-lg font-bold mb-4"><i class="fas fa-list"></i> Transporta Dokumenta Ieraksti</h6>

                        <div class="overflow-auto">
                            <div class="min-w-full grid grid-cols-9 gap-2 bg-gray-100 p-2 font-bold text-sm">
                                <div class="text-center">Darbības</div>
                                <div class="text-center col-span-2">Artikuls</div>
                                <div class="text-center col-span-3">Preces nosaukums</div>
                                <div class="text-center">Daudzums</div>
                                <div class="text-center">Cena</div>
                                <div class="text-center">Summa</div>
                            </div>

                            <!-- Cikls par katru rindu -->
                            @foreach ($lines as $index => $line)
                                <div class="min-w-full grid grid-cols-9 gap-2 p-2 border-b">
                                    <!-- Darbību poga -->
                                    <div class="flex justify-end items-center">
                                        <span class="mr-2">{{ $loop->iteration }}.</span>
                                        <!-- Dzēšanas poga, kas atver dialogu -->
                                        <button wire:click="confirmDelete({{ $index }})" class="bg-red-500 text-white p-0.5 w-8 h-8 rounded hover:bg-red-600 flex justify-center items-center">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    <!-- Produkta artikuls -->
                                    <input type="text" 
                                        id = "line{{ $index }}"
                                        wire:model.blur="lines.{{ $index }}.product_code" 
                                        class="@error('lines.'.$index.'.product_code') border-red-500 @else border-gray-300 @enderror rounded px-2 py-1 focus:outline-none focus:border-blue-500 text-center col-span-2" 
                                        x-init='itemAutocomplete("#line{{ $index }}", "{{ route("search-item-article") }}", {{ $index }}, "{{$lines[$index]["product_code"]}}");' />

                                    <!-- Dispatching Alpine.js event on error -->
                                    @error('lines.'.$index.'.product_code') 
                                        <div hidden x-init="$wire.dispatch('showError', ['{{ $message }}']);"></div>
                                    @enderror

                                    <!-- Produkta nosaukums -->
                                    <input type="text" 
                                        wire:model.blur="lines.{{ $index }}.product_name" 
                                        class="@error('lines.'.$index.'.product_name') border-red-500 @else border-gray-300 @enderror rounded px-2 py-1 focus:outline-none focus:border-blue-500 col-span-3" />
                                    @error('lines.'.$index.'.product_name') 
                                        <div hidden x-init="$wire.dispatch('showError', ['{{ $message }}']);"></div>
                                    @enderror

                                    <!-- Daudzums -->
                                    <input type="number" 
                                        wire:model.live="lines.{{ $index }}.quantity" 
                                        class="@error('lines.'.$index.'.quantity') border-red-500 @else border-gray-300 @enderror rounded px-2 py-1 text-right focus:outline-none focus:border-blue-500" 
                                        min="0" step="1" onkeydown="if(event.key === ','){Livewire.dispatch('showInfo', ['Lietojiet punktu, neviss komatu!'])};"/>
                                    @error('lines.'.$index.'.quantity') 
                                        <div hidden x-init="$wire.dispatch('showError', ['{{ $message }}']);"></div>
                                    @enderror

                                    <!-- Cena -->
                                    <input type="number" 
                                        wire:model.live="lines.{{ $index }}.price" 
                                        class="@error('lines.'.$index.'.price') border-red-500 @else border-gray-300 @enderror rounded px-2 py-1 text-right focus:outline-none focus:border-blue-500" 
                                        min="0" step="0.01" onkeydown="if(event.key === ','){Livewire.dispatch('showInfo', ['Lietojiet punktu, neviss komatu!'])};"/>
                                    @error('lines.'.$index.'.price') 
                                        <div hidden x-init="$wire.dispatch('showError', ['{{ $message }}']);"></div>
                                    @enderror

                                    <!-- Summa (automātiski atjaunināta) -->
                                    <input type="text" 
                                        value="{{ $line['total'] }}"
                                        class="border rounded px-2 py-1 text-right bg-gray-200 cursor-not-allowed" 
                                        disabled />
                                </div>
                            @endforeach
                        </div>

                        <!-- Pievienot jaunu rindu un saglabāt pogu -->
                        <div class="flex justify-between mt-4">
                            <button wire:click="addLine" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 mb-1.5">
                                +
                            </button>
                            <!--<button wire:click="saveLines" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                                Saglabāt
                            </button>-->
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
                    <button wire:click="openPdfPhp" class="bg-gray-300 hover:bg-gray-400 text-gray-700 py-2 px-4 rounded focus:outline-none">
                        Apstiprināt/Drukāt
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Delete Confirmation Modal -->
        @if($showDeleteModal)
            <div x-transition
                class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-75 z-50">
                <div class="bg-white rounded-lg p-6 w-1/3">
                    <h3 class="text-lg font-semibold mb-4">Vai tiešām vēlaties dzēst šo rindu?</h3>
                    <div class="flex justify-end space-x-4">
                        <button wire:click="cancelDelete" class="bg-gray-300 hover:bg-gray-400 text-gray-700 py-2 px-4 rounded">
                            Atcelt
                        </button>
                        <button wire:click="removeLine" wire:loading.attr="disabled" class="bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded">
                            Dzēst
                            <div wire:loading wire:target="removeLine">
                                <i class="fa fa-spinner fa-spin"></i>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        @endif
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
                        Drukāt
                    </button>
                    <button 
                        type="button" 
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                        @click="open = false"
                    >
                        Aizvērt
                    </button>
                </div>
                <div class="px-4 py-5 sm:p-6">
                    <!-- Loading indicator -->
                    <div id="loading" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-size: 20px; color: #333;"
                        x-init='showLoading()'>
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
    function itemAutocomplete(id, route, index, productCode) {
        $( id ).autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: route,
                    data: {
                        query: request.term
                    },
                    success: function(data) {
                        response($.map(data, function(item) {
                            return {
                                label: item.product_code + " - " + item.product_name,  // Divu kolonnu attēlošana
                                code: item.product_code, // Tas, kas tiks ievadīts laukā
                                name: item.product_name // Papildus informācija, ko var izmantot
                            };
                        }));                                                
                    }
                });
            },
            appendTo: "body",
            open: function(event, ui) {
                var $input = $(this),
                    $results = $input.autocomplete("widget"),
                    offset = $input.offset();
                $results.css({
                    top: offset.top + $input.outerHeight(),
                    left: offset.left
                });
            },
            minLength: 1,
            select: function(event, ui) {
                // Handle the select event
                Livewire.dispatch("itemSelected", [index, ui.item.code, ui.item.name]);  // Dispatching custom event
                event.preventDefault(); // Prevent automatic insertion
                $(this).val(ui.item.code);
            },
        }).focus(function () {
            $(this).autocomplete("search", productCode);  // Triggers the dropdown to appear on focus
        });
    }

    function showLoading() {
        // When the iframe has loaded, hide the loading text and show the iframe
        var iframe = document.getElementById('pdfFrame');
        var loading = document.getElementById('loading');

        iframe.onload = function() {
            loading.style.display = 'none';  // Hide loading text
            iframe.style.display = 'block';  // Show iframe
            // printPdf();
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

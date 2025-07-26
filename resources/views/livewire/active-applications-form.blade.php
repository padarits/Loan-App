<div>
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

            <div x-data="{ tab: 'document' }" class="modal-content" style="padding: 0px;">
                <!-- Tabu izvēlne -->
                <div class="border-b border-gray-200 mb-4">
                    <nav class="flex space-x-4" aria-label="Tabs">
                        <a href="#" @click.prevent="tab = 'document'" :class="{'border-blue-500 text-blue-600': tab === 'document', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'document'}" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            <i class="fas fa-box"></i> Preces un Dokumenta informācija
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
                    <div x-show="tab === 'document'">
                        <div class="border-b pb-4 mb-4">
                            <h6 class="text-lg font-bold mb-2"><i class="fas fa-box"></i> Preces saņemšana</h6>
                            <div class="grid grid-cols-6 gap-4" style="margin-left:3px; margin-right:3px;">

                                <!-- Darbības lauks -->
                                <div class="mb-4">
                                    <label for="actionForEntry" class="block text-sm font-medium text-gray-700">Darbība:</label>
                                    @if($typeAddedToInventoryOnly)
                                    <select id="actionForEntry" wire:model.blur="actionForEntry" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm">
                                        <option value="added_to_inventory">Pieņemt no inventarizācijas</option>
                                    </select>
                                    @else
                                    <select id="actionForEntry" wire:model.blur="actionForEntry" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm">
                                        <option value="accept">Pieņemt</option>
                                        <option value="cancel">Atcēlt</option>
                                    </select>
                                    @endif
                                    @error('actionForEntry')
                                    <span class="text-red-500">{{ $message }}</span>
                                    <div hidden x-init="$wire.dispatch('showError', ['{{ $message }}']);"></div>
                                    @enderror
                                </div>

                                <div class="mb-4" style="margin: 0px !important;">
                                    <label for="warehouse_code_a" class="block text-sm font-medium text-gray-700">Uz Noliktavu:</label>
                                    <select id="warehouse_code_a" wire:model.blur="warehouse_code" class="@error('warehouse_code') border-red-500 @else border-gray-300 @enderror mt-1 block w-full border border-gray-300 rounded-md shadow-sm">
                                        <option value="" disabled selected>Izvēlieties noliktavu</option>
                                        @foreach ($warehouses as $warehouse)
                                        <option value="{{ $warehouse['warehouse_code'] }}" {{ $warehouse['warehouse_code'] === $warehouse_code ? 'selected' : '' }}>
                                            {{ $warehouse['name'] }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('warehouse_code')
                                    <div hidden x-init="$wire.dispatch('showError', ['{{ $message }}']);"></div>
                                    @enderror
                                </div>

                                <!-- Daudzums -->
                                <div class="mb-4">
                                    <label for="quantity" class="block text-sm font-medium text-gray-700">Daudzums:</label>
                                    <input id="quantity" type="number"
                                        wire:model.live="quantity"
                                        class="@error('quantity') border-red-500 @else border-gray-300 @enderror text-right mt-1 block w-full border border-gray-300 rounded-md shadow-sm"
                                        min="0" step="1" onkeydown="if(event.key === ','){Livewire.dispatch('showInfo', ['Lietojiet punktu, neviss komatu!'])};" />
                                    @error('quantity')
                                    <div hidden x-init="$wire.dispatch('showError', ['{{ $message }}']);"></div>
                                    @enderror
                                </div>

                                <!-- unit -->
                                <div class="mb-4">
                                    <label for="unit" class="block text-sm font-medium text-gray-700">Mērvienība:</label>
                                    <div class="relative group">
                                        <input id="unit" type="text"
                                            wire:model.live="unit"
                                            class="@error('unit') border-red-500 @else border-gray-300 @enderror text-left mt-1 block w-full border border-gray-300 rounded-md shadow-sm"
                                            min="0" step="0.01" onkeydown="if(event.key === ','){Livewire.dispatch('showInfo', ['Lietojiet punktu, neviss komatu!'])};" />
                                        @error('unit')
                                        <div hidden x-init="$wire.dispatch('showError', ['{{ $message }}']);"></div>
                                        @enderror
                                        <div
                                            class="absolute bottom-full left-1/2 
                                                transform -translate-x-1/2 mb-2 
                                                w-max px-2 py-1 text-sm text-white
                                                bg-gray-700 rounded shadow-lg 
                                                opacity-0 group-hover:opacity-100 duration-300 delay-500">
                                            {{$unit}}
                                        </div>
                                    </div>
                                </div>

                                <!-- Cena -->
                                <div class="mb-4">
                                    <label for="price_per_unit" class="block text-sm font-medium text-gray-700">Cena par vienību:</label>
                                    <input id="price_per_unit" type="number"
                                        wire:model.live="price_per_unit"
                                        class="@error('price_per_unit') border-red-500 @else border-gray-300 @enderror text-right mt-1 block w-full border border-gray-300 rounded-md shadow-sm"
                                        min="0" step="0.01" onkeydown="if(event.key === ','){Livewire.dispatch('showInfo', ['Lietojiet punktu, neviss komatu!'])};" />
                                    @error('price_per_unit')
                                    <div hidden x-init="$wire.dispatch('showError', ['{{ $message }}']);"></div>
                                    @enderror
                                </div>

                                <!-- Summa (automātiski atjaunināta) -->
                                <div class="mb-4">
                                    <label for="summa" class="block text-sm font-medium text-gray-700">Summa:</label>
                                    <input id="summa" type="text"
                                        value="{{ $total_price }}"
                                        class="text-right mt-1 block w-full border border-gray-300 rounded-md shadow-sm cursor-not-allowed"
                                        disabled />
                                </div>

                                <!-- article -->
                                <div class="mb-4">
                                    <label for="article" class="block text-sm font-medium text-gray-700">Artikuls:</label>
                                    <div x-data x-init='              
                                        $( "#article" ).autocomplete({
                                            source: function(request, response) {
                                                $.ajax({
                                                    url: "{{ route("search-article") }}",
                                                    data: {
                                                        query: request.term
                                                    },
                                                    success: function(data) {
                                                        response($.map(data, function(item) {
                                                            return {
                                                                label: item.article + " - " + item.name + " - " + item.name_2 + " - " + item.material_grade,  // Trīs kolonnu attēlošana
                                                                article: item.article, // Tas, kas tiks ievadīts laukā
                                                                name: item.name, // Papildus informācija, ko var izmantot
                                                                name_2: item.name_2, // Papildus informācija, ko var izmantot
                                                                article_id: item.article_id,
                                                                material_grade: item.material_grade,
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
                                                    top: offset.top + $input.outerHeight() + 1,
                                                    left: offset.left
                                                });
                                            },
                                            minLength: 1,
                                            select: function(event, ui) {
                                                // Handle the select event
                                                Livewire.dispatch("articleSelected", [ui.item.article, ui.item.name, ui.item.name_2, ui.item.article_id, ui.item.material_grade]);  // Dispatching custom event
                                                event.preventDefault(); // Prevent automatic insertion
                                                $(this).val(ui.item.article);
                                            }
                                        }).focus(function () {
                                            $(this).autocomplete("search", $(this).val());  // Triggers the dropdown to appear on focus
                                        });'>

                                        <input id="article" type="text"
                                            wire:model.live="article"
                                            class="@error('article') border-red-500 @else border-gray-300 @enderror text-left mt-1 block w-full border border-gray-300 rounded-md shadow-sm" />
                                        @error('article')
                                        <div hidden x-init="$wire.dispatch('showError', ['{{ $message }}']);"></div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- name -->
                                <div class="mb-4 col-span-2">
                                    <label for="name1" class="block text-sm font-medium text-gray-700">Nosaukums:</label>
                                    <div x-data x-init='              
                                        $( "#name1" ).autocomplete({
                                            source: function(request, response) {
                                                $.ajax({
                                                    url: "{{ route("search-name1") }}",
                                                    data: {
                                                        query: request.term
                                                    },
                                                    success: function(data) {
                                                        response($.map(data, function(item) {
                                                            return {
                                                                label: item.name,  // kolonnu attēlošana
                                                                name1: item.name, // Papildus informācija, ko var izmantot
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
                                                    top: offset.top + $input.outerHeight() + 1,
                                                    left: offset.left
                                                });
                                            },
                                            minLength: 0,
                                            select: function(event, ui) {
                                                // Handle the select event
                                                Livewire.dispatch("name1Selected", [ui.item.name1]);  // Dispatching custom event
                                                event.preventDefault(); // Prevent automatic insertion
                                                $(this).val(ui.item.name1);
                                            }
                                        }).focus(function () {
                                            $(this).autocomplete("search", $(this).val());  // Triggers the dropdown to appear on focus
                                        });'>
                                        <input id="name1" type="text"
                                            wire:model.live="name"
                                            class="@error('name') border-red-500 @else border-gray-300 @enderror text-left mt-1 block w-full border border-gray-300 rounded-md shadow-sm" />
                                        @error('name')
                                        <div hidden x-init="$wire.dispatch('showError', ['{{ $message }}']);"></div>
                                        @enderror
                                    </div>
                                </div>
                                <!-- name_2 -->
                                <div class="mb-4 col-span-2">
                                    <label for="name_2" class="block text-sm font-medium text-gray-700">Nosaukums 2:</label>
                                    <div x-data x-init='              
                                        $( "#name_2" ).autocomplete({
                                            source: function(request, response) {
                                                $.ajax({
                                                    url: "{{ route("search-name2") }}",
                                                    data: {
                                                        query: request.term
                                                    },
                                                    success: function(data) {
                                                        response($.map(data, function(item) {
                                                            return {
                                                                label: item.name_2,  // kolonnu attēlošana
                                                                name2: item.name_2, // Papildus informācija, ko var izmantot
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
                                                    top: offset.top + $input.outerHeight() + 1,
                                                    left: offset.left
                                                });
                                            },
                                            minLength: 0,
                                            select: function(event, ui) {
                                                // Handle the select event
                                                Livewire.dispatch("name2Selected", [ui.item.name2]);  // Dispatching custom event
                                                event.preventDefault(); // Prevent automatic insertion
                                                $(this).val(ui.item.name2);
                                            }
                                        }).focus(function () {
                                            $(this).autocomplete("search", $(this).val());  // Triggers the dropdown to appear on focus
                                        });'>
                                        <input id="name_2" type="text"
                                            wire:model.live="name_2"
                                            class="@error('name_2') border-red-500 @else border-gray-300 @enderror text-left mt-1 block w-full border border-gray-300 rounded-md shadow-sm"
                                            min="0" step="0.01" onkeydown="if(event.key === ','){Livewire.dispatch('showInfo', ['Lietojiet punktu, neviss komatu!'])};" />
                                        @error('name_2')
                                        <div hidden x-init="$wire.dispatch('showError', ['{{ $message }}']);"></div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- material_grade -->
                                <div class="mb-4">
                                    <label for="material_grade" class="block text-sm font-medium text-gray-700">Materāla marka:</label>
                                    <div x-data x-init='              
                                        $( "#material_grade" ).autocomplete({
                                            source: function(request, response) {
                                                $.ajax({
                                                    url: "{{ route("search-material-grade") }}",
                                                    data: {
                                                        query: request.term
                                                    },
                                                    success: function(data) {
                                                        response($.map(data, function(item) {
                                                            return {
                                                                label: item.material_grade,  // kolonnu attēlošana
                                                                material_grade: item.material_grade, // Papildus informācija, ko var izmantot
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
                                                    top: offset.top + $input.outerHeight() + 1,
                                                    left: offset.left
                                                });
                                            },
                                            minLength: 0,
                                            select: function(event, ui) {
                                                // Handle the select event
                                                Livewire.dispatch("materialGradeSelected", [ui.item.material_grade]);  // Dispatching custom event
                                                event.preventDefault(); // Prevent automatic insertion
                                                $(this).val(ui.item.material_grade);
                                            }
                                        }).focus(function () {
                                            $(this).autocomplete("search", $(this).val());  // Triggers the dropdown to appear on focus
                                        });'>
                                        <input id="material_grade" type="text"
                                            wire:model.live="material_grade"
                                            class="@error('material_grade') border-red-500 @else border-gray-300 @enderror text-left mt-1 block w-full border border-gray-300 rounded-md shadow-sm"
                                            min="0" step="0.01" onkeydown="if(event.key === ','){Livewire.dispatch('showInfo', ['Lietojiet punktu, neviss komatu!'])};" />
                                        @error('material_grade')
                                        <div hidden x-init="$wire.dispatch('showError', ['{{ $message }}']);"></div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Statusa lauks -->
                                <div class="mb-4">
                                    <label for="status" class="block text-sm font-medium text-gray-700">Status:</label>
									<select id="status" wire:model.blur="status" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm">
                                        <option value="-" {{ $status == '-' ? 'selected' : '' }}>-</option>
                                        <option value="R" {{ $status == 'R' ? 'selected' : '' }}>R</option>
                                        <option value="Ri" {{ $status == 'Ri' ? 'selected' : '' }}>Ri</option>
                                        <option value="N" {{ $status == 'N' ? 'selected' : '' }}>N</option>
                                        <option value="M" {{ $status == 'M' ? 'selected' : '' }}>M</option>
                                    </select>
                                    @error('status')
                                    <span class="text-red-500">{{ $message }}</span>
                                    <div hidden x-init="$wire.dispatch('showError', ['{{ $message }}']);"></div>
                                    @enderror
                                </div>

                                <!-- code -->
                                <div class="mb-4 col-span-1">
                                    <label for="code" class="block text-sm font-medium text-gray-700">Kods:</label>
                                    <div x-data x-init='              
                                        $( "#code" ).autocomplete({
                                            source: function(request, response) {
                                                $.ajax({
                                                    url: "{{ route("search-item-code") }}",
                                                    data: {
                                                        query: request.term
                                                    },
                                                    success: function(data) {
                                                        response($.map(data, function(item) {
                                                            return {
                                                                label: item.code,  // kolonnu attēlošana
                                                                code: item.code, // Papildus informācija, ko var izmantot
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
                                                    top: offset.top + $input.outerHeight() + 1,
                                                    left: offset.left
                                                });
                                            },
                                            minLength: 0,
                                            select: function(event, ui) {
                                                // Handle the select event
                                                Livewire.dispatch("itemCodeSelected", [ui.item.code]);  // Dispatching custom event
                                                event.preventDefault(); // Prevent automatic insertion
                                                $(this).val(ui.item.code);
                                            }
                                        }).focus(function () {
                                            $(this).autocomplete("search", $(this).val());  // Triggers the dropdown to appear on focus
                                        });'>
                                            <input id="code" type="text"
                                                wire:model.live="code"
                                                class="@error('code') border-red-500 @else border-gray-300 @enderror text-left mt-1 block w-full border border-gray-300 rounded-md shadow-sm"
                                                min="0" step="0.01" onkeydown="if(event.key === ','){Livewire.dispatch('showInfo', ['Lietojiet punktu, neviss komatu!'])};" />
                                            @error('code')
                                            <div hidden x-init="$wire.dispatch('showError', ['{{ $message }}']);"></div>
                                            @enderror
                                    </div>
                                </div>

                                <!-- code_2 -->
                                <div class="mb-4">
                                    <label for="code_2" class="block text-sm font-medium text-gray-700">Kods 2:</label>
                                    <div class="relative group">
                                        <input id="code_2" type="text"
                                            wire:model.live="code_2"
                                            class="@error('code_2') border-red-500 @else border-gray-300 @enderror text-left mt-1 block w-full border border-gray-300 rounded-md shadow-sm"
                                            min="0" step="0.01" onkeydown="if(event.key === ','){Livewire.dispatch('showInfo', ['Lietojiet punktu, neviss komatu!'])};" />
                                        @error('code_2')
                                        <div hidden x-init="$wire.dispatch('showError', ['{{ $message }}']);"></div>
                                        @enderror
                                        <div
                                            class="absolute bottom-full left-1/2 
                                                transform -translate-x-1/2 mb-2 
                                                w-max px-2 py-1 text-sm text-white
                                                bg-gray-700 rounded shadow-lg 
                                                opacity-0 group-hover:opacity-100 duration-300 delay-500">
                                            {{$code_2}}
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="warehouse_date" class="block text-sm font-medium text-gray-700">Noliktavas Datums:</label>
                                    <div x-data x-init='$( "#warehouse_date" ).datepicker(
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
                                            $wire.set("warehouse_date", $(this).val());  
                                        });'>
                                        <input type="text" x-ref="datepicker" id="warehouse_date" wire:model.blur="warehouse_date" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" autocomplete="off" />
                                    </div>
                                    @error('warehouse_date')
                                    <span class="text-red-500">{{ $message }}</span>
                                    <div hidden x-init="$wire.dispatch('showError', ['{{ $message }}']);"></div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="recipient" class="block text-sm font-medium text-gray-700">Saņēmējs:</label>
                                    <div x-data x-init='              
                                        $( "#recipient" ).autocomplete({
                                            source: function(request, response) {
                                                $.ajax({
                                                    url: "{{ route("search-recipient") }}",
                                                    data: {
                                                        query: request.term
                                                    },
                                                    success: function(data) {
                                                        response($.map(data, function(item) {
                                                            return {
                                                                label: item.name + " (" + item.email + ")",  // kolonnu attēlošana
                                                                recipient: item.name, // Papildus informācija, ko var izmantot
                                                                recipient_guid: item.id,
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
                                                    top: offset.top + $input.outerHeight() + 1,
                                                    left: offset.left
                                                });
                                            },
                                            minLength: 0,
                                            select: function(event, ui) {
                                                // Handle the select event
                                                Livewire.dispatch("recipientSelected", [ui.item.recipient, ui.item.recipient_guid]);  // Dispatching custom event
                                                event.preventDefault(); // Prevent automatic insertion
                                                $(this).val(ui.item.recipient);
                                            }
                                        }).focus(function () {
                                            $(this).autocomplete("search", $(this).val());  // Triggers the dropdown to appear on focus
                                        });'>
                                        <input type="text" id="recipient" wire:model.blur="recipient" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" autocomplete="off">
                                        @error('recipient')
                                        <span class="text-red-500">{{ $message }}</span>
                                        <div hidden x-init="$wire.dispatch('showError', ['{{ $message }}']);"></div>
                                        @enderror
                                    </div>
                                </div>
                                <!-- recipientEmail  -->
                                <div class="mb-4">
                                    <label for="recipientEmail" class="block text-sm font-medium text-gray-700">E-pasts:</label>
                                    <div class="relative group">
                                        <input id="recipientEmail" type="text"
                                            value="{{ $recipientEmail }}"
                                            class="text-right mt-1 block w-full border border-gray-300 rounded-md shadow-sm cursor-not-allowed"
                                            disabled />
                                        <div
                                            class="absolute bottom-full left-1/2 
                                                transform -translate-x-1/2 mb-2 
                                                w-max px-2 py-1 text-sm text-white
                                                bg-gray-700 rounded shadow-lg 
                                                opacity-0 group-hover:opacity-100 duration-300 delay-500">
                                            {{$recipientEmail}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="border-b pb-4 mb-4">
                            <h6 class="text-lg font-bold mb-2"><i class="fas fa-file-alt"></i> Dokumenta Informācija</h6>
                            <div class="grid grid-cols-6 gap-4">

                                <div class="mb-4">
                                    <label for="invoice_date" class="block text-sm font-medium text-gray-700">Pavadzīmes Datums:</label>
                                    <div x-data x-init='$( "#invoice_date" ).datepicker(
                                        {
                                            setDate: $refs.datepicker1,
                                            regional: "lv",
                                            dateformat: "dd.mm.yyyy",
                                            showWeek: true,
                                            firstDay: 1,
                                            showButtonPanel: false,
                                        }).on("change", function(value) {
                                            $wire.set("invoice_date", $(this).val());                                       
                                        });'>
                                        <input type="text" x-ref="datepicker1" id="invoice_date" wire:model.blur="invoice_date" class="@error('invoice_date') border-red-500 @else border-gray-300 @enderror mt-1 block w-full border border-gray-300 rounded-md shadow-sm" autocomplete="off" />
                                    </div>
                                    @error('invoice_date')
                                    <div hidden x-init="$wire.dispatch('showError', ['{{ $message }}']);"></div>
                                    @enderror
                                </div>

                                <!-- invoice_number -->
                                <div class="mb-4">
                                    <label for="invoice_number" class="block text-sm font-medium text-gray-700">Pavadzīmes numurs:</label>
                                    <div class="relative group">
                                        <input id="invoice_number" type="text"
                                            wire:model.live="invoice_number"
                                            class="@error('invoice_number') border-red-500 @else border-gray-300 @enderror text-left mt-1 block w-full border border-gray-300 rounded-md shadow-sm"
                                            min="0" step="0.01" onkeydown="if(event.key === ','){Livewire.dispatch('showInfo', ['Lietojiet punktu, neviss komatu!'])};"  autocomplete="off" />
                                        @error('invoice_number')
                                        <div hidden x-init="$wire.dispatch('showError', ['{{ $message }}']);"></div>
                                        @enderror
                                        <div
                                            class="absolute bottom-full left-1/2 
                                                transform -translate-x-1/2 mb-2 
                                                w-max px-2 py-1 text-sm text-white
                                                bg-gray-700 rounded shadow-lg 
                                                opacity-0 group-hover:opacity-100 duration-300 delay-500">
                                            {{$invoice_number}}
                                        </div>
                                    </div>
                                </div>
                                <!--<div class="grid grid-cols-2 gap-4">-->

                                <div class="mb-4">
                                    <label for="supplier" class="block text-sm font-medium text-gray-700">Piegādātāja Numurs:</label>
                                    <div x-data x-init='              
                                        $( "#supplier" ).autocomplete({
                                            source: function(request, response) {
                                                $.ajax({
                                                    url: "{{ route("search-supplier-by-reg-number") }}",
                                                    data: {
                                                        query: request.term
                                                    },
                                                    success: function(data) {
                                                        response($.map(data, function(item) {
                                                            return {
                                                                label: item.supplier + " - " + item.supplier_company,  // Divu kolonnu attēlošana
                                                                code: item.supplier, // Tas, kas tiks ievadīts laukā
                                                                name: item.supplier_company // Papildus informācija, ko var izmantot
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
                                                Livewire.dispatch("supplierForItemSelected", [ui.item.code, ui.item.name]);  // Dispatching custom event
                                                event.preventDefault(); // Prevent automatic insertion
                                                $(this).val(ui.item.code);
                                            }
                                        }).focus(function () {
                                            $(this).autocomplete("search", $(this).val());  // Triggers the dropdown to appear on focus
                                        });'>
                                        <input type="text" id="supplier" wire:model.blur="supplier" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" autocomplete="off">
                                    </div>
                                    @error('supplier')
                                    <span class="text-red-500">{{ $message }}</span>
                                    <div hidden x-init="$wire.dispatch('showError', ['{{ $message }}']);"></div>
                                    @enderror
                                </div>

                                <div class="mb-4 col-span-2">
                                    <label for="supplier_company" class="block text-sm font-medium text-gray-700">Piegādātāja Nosaukums:</label>
                                    <input type="text" id="supplier_company" wire:model.blur="supplier_company" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" autocomplete="off">
                                    @error('supplier_company')
                                    <span class="text-red-500">{{ $message }}</span>
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
    style="display: none;">
    <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-4xl sm:w-full">
        <div class="px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button
                type="button"
                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm"
                onclick="printPdf()">
                Drukāt
            </button>
            <button
                type="button"
                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                @click="open = false">
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
    function showLoading() {
        // When the iframe has loaded, hide the loading text and show the iframe
        var iframe = document.getElementById('pdfFrame');
        var loading = document.getElementById('loading');

        iframe.onload = function() {
            loading.style.display = 'none'; // Hide loading text
            iframe.style.display = 'block'; // Show iframe
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
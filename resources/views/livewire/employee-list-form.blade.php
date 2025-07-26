<div id="employee-list-form">
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

            <div x-data="{ tab: 'employee' }" class="modal-content" style="padding: 0px;">
                <!-- Tabu izvēlne -->
                <div class="border-b border-gray-200 mb-4">
                    <nav class="flex space-x-4" aria-label="Tabs">
                        <a href="#" @click.prevent="tab = 'employee'" :class="{'border-blue-500 text-blue-600': tab === 'employee', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'employee'}" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            <i class="fas fa-laptop-code"></i> Darbinieka informācija
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
                    <div x-show="tab === 'employee'">
                        <div class="border-b pb-4 mb-4">
                            <h6 class="text-lg font-bold mb-2"><i class="fas fa-box"></i> Darbinieka informācija</h6>
                            <div class="grid grid-cols-6 gap-4" style="margin-left:3px; margin-right:3px;">

                                <div class="mb-4 col-span-3">
                                    <label for="email" class="block text-sm font-medium text-gray-700">email:</label>
                                    <div x-data x-init=''>
                                        <input disabled type="text" x-ref="email" id="email" wire:model.blur="employee.email" class="@error('employee.email') border-red-500 @else border-gray-300 @enderror mt-1 block w-full border border-gray-300 rounded-md shadow-sm" autocomplete="off" />
                                    </div>
                                    @error('employee.email')
                                        <div hidden x-init="$wire.dispatch('showError', ['{{ $message }}']);"></div>
                                    @enderror
                                </div>
                                <div class="mb-4 col-span-3">
                                    <label for="name" class="block text-sm font-medium text-gray-700">Vārds, Uzvārds:</label>
                                    <div x-data x-init=''>
                                        <input disabled type="text" x-ref="name" id="name" wire:model.blur="employee.name" class="@error('employee.name') border-red-500 @else border-gray-300 @enderror mt-1 block w-full border border-gray-300 rounded-md shadow-sm" autocomplete="off" />
                                    </div>
                                    @error('employee.name')
                                        <div hidden x-init="$wire.dispatch('showError', ['{{ $message }}']);"></div>
                                    @enderror
                                </div>
                                <!-- Amatu saraksts ar vertikālu ritināšanu -->
                                <div class="border-t mb-4 overflow-y-auto max-h-64  col-span-6"> <!-- Pievienots overflow-y-auto un max-h-64 -->
                                    <table class="table-auto w-full mb-4">
                                        <thead>
                                            <tr>
                                                <th class="px-4 py-2">Nr.</th>
                                                <th class="px-4 py-2 col-span-2">Departaments</th>
                                                <th class="px-4 py-2 col-span-3">Amats</th>
                                                <th class="px-4 py-2">Vadītājs</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($employeeForDepartments and count($employeeForDepartments) > 0)    
                                                @foreach($employeeForDepartments as $employeeForDepartment)
                                                    <tr>
                                                        <td>
                                                            <!-- Darbību poga -->
                                                            <div class="flex justify-end items-center">
                                                                <span class="mr-2">{{ $loop->iteration }}.</span>
                                                                <!-- Dzēšanas poga, kas atver dialogu -->
                                                                <button wire:click="deletePosition('{{ $employeeForDepartment->id }}')" class="bg-red-500 text-white p-0.5 w-8 h-8 rounded hover:bg-red-600 flex justify-center items-center">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </div>
                                                        </td>
                                                        <td class="border px-4 py-2">{{ $employeeForDepartment->department ? $employeeForDepartment->department->name : null }}</td>
                                                        <td class="border px-4 py-2">{{ $employeeForDepartment->position->position_name }}</td>
                                                        <td class="border px-4 py-2 text-center">
                                                            <input 
                                                                type="checkbox" 
                                                                wire:click="changePositionIsHead('{{ $employeeForDepartment->id }}')"
                                                                wire:confirm="Jūs tiešam grībat mainīt vadītāja statusu?"
                                                                @if($employeeForDepartment->is_head) checked @endif
                                                            >
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mb-4 col-span-3">
                                    <label for="name" class="block text-sm font-medium text-gray-700">Pievienot jaunu amatu:</label>
                                    <div class="flex items-center" x-data x-init='              
                                            $( "#newPosition" ).autocomplete({
                                                source: function(request, response) {
                                                    $.ajax({
                                                        url: "{{ route("search-position") }}",
                                                        data: {
                                                            query: request.term
                                                        },
                                                        success: function(data) {
                                                            response($.map(data, function(item) {
                                                                return {
                                                                    label: item.position_name ,  // Divu kolonnu attēlošana
                                                                    id: item.id,
                                                                    position_name: item.position_name, // Tas, kas tiks ievadīts laukā
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
                                                    Livewire.dispatch("positionSelected", [ui.item.id]);  // Dispatching custom event
                                                    event.preventDefault(); // Prevent automatic insertion
                                                    $(this).val(ui.item.position_name);                                                
                                                }
                                            }).focus(function () {
                                                $(this).autocomplete("search", $(this).val());  // Triggers the dropdown to appear on focus
                                            });'>
                                            <input type="text" id="newPosition" wire:model.live="newPosition" class="border rounded px-4 py-2 mr-2 w-full" placeholder="Jaunā amata nosaukums" autocomplete="off">
                                        
                                        <!--<input type="text" wire:model="newPosition" class="border rounded px-4 py-2 mr-2 w-full" placeholder="Jaunā amata nosaukums">-->
                                        <button wire:click="addPosition" class="bg-green-500 text-white px-4 py-2 rounded">Pievienot</button>
                                    </div>
                                    @error('newPosition') <span class="text-red-500">{{ $message }}</span> @enderror
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


<div>
    @if($isOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50">
            <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-lg mx-auto relative" style="min-width: 400px; width: 100%; max-height: 95vh; overflow-y: auto;">
                <!-- Modāla aizvēršanas poga -->
                <button wire:click="closeModal" class="absolute top-2 right-2 text-gray-400 hover:text-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>

                <h2 class="text-xl font-bold mb-4">Lietotājs: {{$email}}</h2>
                
                <!-- Paroles iestatīšana -->
                <div class="mb-4">
                    <h3 class="text-lg font-bold mb-2">Iestatīt jaunu paroli</h3>
                    <div class="flex items-center">
                        <input type="text" wire:model="newPassword" class="border rounded px-4 py-2 mr-2 w-full" placeholder="Jaunā parole">
                        <button wire:click="setPassword" class="bg-green-500 text-white px-4 py-2 rounded">Iestatīt</button>
                    </div>
                    @error('newPassword') <span class="text-red-500">{{ $message }}</span> @enderror
                </div>

                <!-- Lomu saraksts ar vertikālu ritināšanu -->
                <div class="mb-4 overflow-y-auto max-h-64"> <!-- Pievienots overflow-y-auto un max-h-64 -->
                    <table class="table-auto w-full mb-4">
                        <thead>
                            <tr>
                                <th class="px-4 py-2">Loma</th>
                                <th class="px-4 py-2">Pievienot/Dzēst</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($roles as $role)
                                <tr>
                                    <td class="border px-4 py-2">{{ $role->name }}</td>
                                    <td class="border px-4 py-2 text-center">
                                        <input 
                                            type="checkbox" 
                                            value="{{ $role->name }}" 
                                            wire:model="selectedRoles" 
                                            onclick="confirmRoleChange(event, '{{ $role->name }}', '{{$email}}')" 
                                        >
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Jaunas lomas pievienošana -->
                <div class="mb-4">
                    <h3 class="text-lg font-bold mb-2">Izveidot jaunu lomu</h3>
                    <div class="flex items-center">
                        <input type="text" wire:model="newRoleName" class="border rounded px-4 py-2 mr-2 w-full" placeholder="Jaunās lomas nosaukums">
                        <button wire:click="createRole" class="bg-green-500 text-white px-4 py-2 rounded">Izveidot</button>
                    </div>
                    @error('newRoleName') <span class="text-red-500">{{ $message }}</span> @enderror
                </div>

                <!-- Pogu bloks -->
                <div class="flex justify-end space-x-2 p-4 bg-gray-100 rounded-b-lg">
                    <button wire:click="closeModal" class="bg-gray-300 hover:bg-gray-400 text-gray-700 py-2 px-4 rounded focus:outline-none">
                        Atcelt
                    </button>
                    <!-- Saglabāt pogu -->
                    <div class="flex justify-end">
                        <button wire:click="saveRoles" class="bg-blue-500 text-white px-4 py-2 rounded">Saglabāt</button>
                    </div>
                </div>  
            </div>
        </div>
    @endif
    <!-- Javascript funkcija apstiprinājumam par lomas izmaiņām -->
    <script>
        function confirmRoleChange(event, roleName, email) {
            // Pārbaudām, vai tiek mainīta 'admin' loma
            if (roleName === 'admin') {
                // Ja checkbox tiek atzīmēts (pievienošana)
                if (event.target.checked) {
                    // Parādām apstiprinājuma dialogu
                    if (!confirm('Vai tiešām vēlaties piešķirt lietotājam: ' + email + ' "admin" lomu?')) {
                        event.preventDefault();
                        event.target.checked = false; // Atceļam izmaiņas, ja apstiprinājums netika sniegts
                    }
                }
                // Noņemšana (ja tiek noņemts checkbox)
                else {
                    if (!confirm('Vai tiešām vēlaties noņemt lietotājam: ' + email + ' "admin" lomu?')) {
                        event.preventDefault();
                        event.target.checked = true; // Atceļam izmaiņas, ja apstiprinājums netika sniegts
                    }
                }
            }
        }
    </script>
</div>


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
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="userDetailModalLabel">User Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @if ($user)
                            <p><strong>ID:</strong> {{ $user->id }}</p>
                            <p><strong>Name:</strong> {{ $user->name }}</p>
                            <p><strong>Email:</strong> {{ $user->email }}</p>
                            <!-- Add more fields as necessary -->
                        @else
                            <p>No user data available</p>
                        @endif
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
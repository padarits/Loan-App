<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                
                @if (App\Utils\MainUtils::checkIfUserHasNoRoles())
                    <!-- <span>Jums nav piešķirtas lomas.</span> -->
                    <livewire:loan-manager />   
                @endif

                <a href="{{ route('purchase-invoice-header') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" style="display:none;">
                    Apskatīt Pirkuma Rēķinus
                </a>
                
                <!-- Tikai admin lietotājiem -->
                @role('admin')
                <a href="{{ route('user-right') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" style="margin: 5px;">
                    Apskatīt Lietotājus
                </a>
                @endrole
                @role('transport')
                <a href="{{ route('transport-document') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" style="margin: 5px;">
                    Apskatīt Transporta Pavadzīmes
                </a>
                @endrole
                @role('transport')
                <a href="{{ route('warehouse-material') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" style="margin: 5px;">
                    Apskatīt Noliktavas Materiālus
                </a>
                @endrole
                @role('department')
                <a href="{{ route('department') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" style="margin: 5px;">
                    Apskatīt Departamentus
                </a>
                @endrole
            </div>
        </div>
    </div>
</x-app-layout>
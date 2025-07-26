<div class="py-5">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6" style="padding: 0px !important;">
            <input type="hidden" id="jj-warehouse-code-filter" value="{{ $jjWarehouseCodeFilter }}">
            <div x-data="{ 
                tab: 'active-applications',
                handleTabChange(newTab) { 
                    // console.log('Tab changed to:', newTab); 
                    document.dispatchEvent(new CustomEvent('dataTableAjaxReload-' + newTab));
                    setTimeout(
                        function(){
                            $($.fn.dataTable.tables(true)).DataTable()
                                .columns.adjust();
                        }, 300);    
                } 
            }" x-init="$watch('tab', handleTabChange)" class="container mx-auto px-4 py-6">
                <!-- Tabu izvēlne -->
                <div class="border-b border-gray-200 mb-4">
                    <nav class="flex space-x-4" aria-label="Tabs">
                        <a href="#" @click.prevent="tab = 'active-applications'" :class="{'border-blue-500 text-blue-600': tab === 'active-applications', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'active-applications'}" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            <i class="fas fa-tasks"></i> Aktivie pieteikumi
                        </a>
                        <a href="#" @click.prevent="tab = 'stock-receipt'" :class="{'border-blue-500 text-blue-600': tab === 'stock-receipt', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'stock-receipt'}" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            <i class="fas fa-truck-loading"></i> Saņemta Prece
                        </a>
                        <a href="#" @click.prevent="tab = 'stock-dispatch'" :class="{'border-blue-500 text-blue-600': tab === 'stock-dispatch', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'stock-dispatch'}" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            <i class="fas fa-shipping-fast"></i> Izsniegta Prece
                        </a>
                        <a href="#" @click.prevent="tab = 'stock-balance'" :class="{'border-blue-500 text-blue-600': tab === 'stock-balance', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'stock-balance'}" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            <i class="fas fa-boxes"></i> Preču atlikumi
                        </a>
                    </nav>
                </div>

                <!-- Tabu saturs -->
                <div class="tab-content">
                    <!-- Dokumenta un Saņēmēja informācija -->
                    <div x-show="tab === 'active-applications'">
                        <!--<h2 class="text-lg font-bold mb-4"  style="margin: 0px !important;"><i class="fas fa-tasks"></i> Aktivie pieteikumi</h2>-->
                        <div class="flex items-center justify-between mb-4" style="margin: 0px !important;">
                            <h2 class="text-lg font-bold">
                                <i class="fas fa-tasks"></i> Aktivie pieteikumi
                            </h2>
                            
                            <!-- Livewire noliktavas izvēlne labajā pusē -->
                            <div class="ml-auto">
                                @livewire('warehouse-selector', ['name' => 'active-applications'])
                            </div>
                        </div>
                        <!-- Livewire Komponents Aktīvie Pieteikumi -->
                        <livewire:active-applications />
                    </div>

                    <!-- Piegādātāja un papildus informācija -->
                    <div x-show="tab === 'stock-receipt'" x-cloak>
                        <!--<h2 class="text-lg font-bold mb-4" style="margin: 0px !important;"><i class="fas fa-truck-loading"></i> Preču saņemšana</h2>-->
                        <div class="flex items-center justify-between mb-4" style="margin: 0px !important;">
                            <h2 class="text-lg font-bold">
                                <i class="fas fa-truck-loading"></i> Saņemta Prece
                            </h2>
                            
                            <!-- Livewire noliktavas izvēlne labajā pusē -->
                            <div class="ml-auto">
                                @livewire('warehouse-selector', ['name' => 'stock-receipt'])	
                            </div>
                        </div>
                        <!-- Livewire Komponents Preču saņemšana -->
                        <livewire:stock-receipt />
                    </div>

                    <!-- Dokumenta Ieraksti -->
                    <div x-show="tab === 'stock-dispatch'" x-cloak>
                        <!--<h2 class="text-lg font-bold mb-4" style="margin: 0px !important;"><i class="fas fa-shipping-fast"></i> Preču izsniegšana</h2>-->
                        <div class="flex items-center justify-between mb-4" style="margin: 0px !important;">
                            <h2 class="text-lg font-bold">
                                <i class="fas fa-shipping-fast"></i> Izsniegta Prece
                            </h2>
                            
                            <!-- Livewire noliktavas izvēlne labajā pusē -->
                            <div class="ml-auto">
                                @livewire('warehouse-selector', ['name' => 'stock-dispatch'])
                            </div>
                        </div>
                        <livewire:stock-dispatch />
                    </div>

                    <!-- Cita Informācija -->
                    <div x-show="tab === 'stock-balance'" x-cloak>
                        <!--<h2 class="text-lg font-bold mb-4" style="margin: 0px !important;"><i class="fas fa-boxes"></i> Preču atlikumi</h2>-->
                        <div class="flex items-center justify-between mb-4" style="margin: 0px !important;">
                            <h2 class="text-lg font-bold">
                                <i class="fas fa-boxes"></i> Preču atlikumi
                            </h2>
                            
                            <!-- Livewire noliktavas izvēlne labajā pusē -->
                            <div class="ml-auto">
                                @livewire('warehouse-selector', ['name' => 'stock-balance'])
                            </div>
                        </div>
                        <!-- Livewire Komponents Preču atlikumi -->
                        <livewire:stock-balance />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

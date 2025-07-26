<!--izsniegto preču forma-->
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
                            <i class="fas fa-warehouse"></i> Preces kustības informācija
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
                <div class="modal-body overflow-y-auto" style="max-height: 70vh;">
                    <!-- Dokumenta un Saņēmēja informācija Tab -->
                    <div x-show="tab === 'document'">
                        <div class="border-b pb-4 mb-4" x-init="loadStockHistory('{{$id}}');">
                            <h6 class="text-lg font-bold mb-2"><i class="fas fa-warehouse"></i> Preces kustības informācija</h6>
                            <table id="stock-history-table" class="display table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Id</th> <!-- `guid` -->
                                        <th>Tips</th> <!-- `type` -->
                                        <th>Noliktava</th> <!-- `warehouse name` -->
                                        <th>Skaits</th> <!-- `quantity` -->
                                        <th>Artikuls</th> <!-- `article` -->
                                        <th>Datums</th> <!-- `date` -->
                                        <th>Kods</th> <!-- `code` -->
                                        <th>Statuss</th> <!-- `status` -->
                                        <th>Pas. numurs</th> <!-- `order_number` -->
                                        <th>Nosaukums</th> <!-- `name` -->
                                        <th>Nosaukums 2</th> <!-- `name_2` -->
                                        <th>Materiāla marka</th> <!-- `material_grade` -->
                                        <th>Mērvienība</th> <!-- `unit` -->
                                        <th>Daudzums</th> <!-- `quantity` -->
                                        <th>Cena par vienību</th> <!-- `price_per_unit` -->
                                        <th>Kopējā summa</th> <!-- `total_price` -->
                                        <th>Piegādātājs</th> <!-- `supplier` -->
                                        <th>Saņēmējs</th> <!-- `recipient` -->
                                        <th>Termiņš</th> <!-- `due_date` -->
                                        <th>Pavadzīmes numurs</th> <!-- `invoice_number` -->
                                        <th>Piegādātāja uzņēmums</th> <!-- `supplier_company` -->
                                        <th>Noliktavas datums</th> <!-- `warehouse_date` -->
                                        <!--<th>Atzīme par izdošanu</th>--> <!-- `issued` -->
                                        <th>Kods 2</th> <!-- `code_2` -->
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
<script>
    function loadStockHistory(id) {
        // Inicializē DataTable
        var $table3a = $('#stock-history-table').DataTable({
            processing: true,
            serverSide: true,
            stateSave: true,
            scrollX: true,
            ordering: false,
            fixedColumns: {
                start: 4 // Fiksē pirmās 4 kolonnas
            },
            autowidth: true,
            responsive: true,
            // scrollCollapse: true,
            destroy: true,
            buttons: [
                /*{
                    text: 'Jauns ieraksts',
                    action: function (e, dt, node, config) {
                        Livewire.dispatch('openModalNewDoc');
                    }
                }*/
            ],
            ajax: {
                url: "{{ route('stock-history') }}",
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: function (d) {
                    d.jjId = id;
                },
                error: function (xhr) {
                    if (xhr.status == 403) {
                        document.dispatchEvent(new CustomEvent('showWarning', { detail: ["Session expired or unauthorized! Reloading page..."] }));
                        setTimeout(function () {
                            window.location.reload();
                        }, 3000);
                    } else {
                        // Log detailed error information for debugging
                        console.log("Error Status: " + xhr.status);
                        console.log("Status Text: " + xhr.statusText);
                        console.log("Response Text: " + xhr.responseText);
                        document.dispatchEvent(new CustomEvent('showError', { detail: ["An error occurred while loading data! Please try again later."] }));
                    }
                }
            },
            drawCallback: function (settings) {
                // Iespēja apstrādāt DataTables atgrieztos datus
                // console.log(settings.json);
            },
            createdRow: function (row, data, dataIndex) {
                $(row).attr('data-id', data.guid);
            },
            columns: [
                {
                    data: 'guid',
                    className: 'pointer',
                    render: function (data, type, row, meta) {
                        var id = ''; //data;
                        return '<span>' + id + '</span> <i class="fas fa-edit" aria-label="Edit" title="Edit"></i>';
                    },
                },
                {data: 'type'},
                {data: 'warehouse_name'},
                {data: 'quantity'},
                /*{
                    data: 'delta_quantity',
                    render: function (data, type, row, meta) {
                        return data;
                    }
                },*/
                {data: 'article'},
                {data: 'date'},
                {data: 'code'},
                {
                    data: 'status',
                    render: function (data, type, row, meta) {
                        return data ? `<span>${data}</span>` : '<span>-</span>';
                    }
                },
                {data: 'order_number'},
                {data: 'name', width: '100px'},
                {data: 'name_2', width: '100px'},
                {data: 'material_grade'},
                {data: 'unit'},
                {data: 'quantity', className: 'text-align-right'},
                {data: 'price_per_unit', className: 'text-align-right'},
                {
                    data: 'total_price',
                    type: 'num',
                    orderable: false,
                    className: 'text-align-right',
                    render: function (data, type, row, meta) {
                        return data ? data : '0.00';
                    }
                },
                {data: 'supplier'},
                {data: 'recipient'},
                {data: 'due_date'},
                {data: 'invoice_number'},
                {data: 'supplier_company'},
                {data: 'warehouse_date'},
                /*{
                    data: 'issued',
                    render: function (data, type, row, meta) {
                        return data ? 'Izsniegts' : 'Nav izsniegts';
                    }
                },*/
                {data: 'code_2'}
            ],
            columnDefs: [
            {
                width: '150px',
                targets: [7,8, 9],
            }, /*{
                "targets": 0, // Pirmā kolonna (indekss sākas no 0)
                "createdCell": function(td, cellData, rowData, row, col) {
                    $(td).addClass('first-element');
                }
            }, */
            ],
            lengthMenu: [
                [10, 25, 50],
                [10, 25, 50]
            ],
            layout: {
                topStart: '',   //'buttons',
                topEnd: '',     //'search',
                bottomStart: ['pageLength', 'info'],
                bottomEnd: ['paging']
            },
            initComplete: function (settings, json) {
                // this.api().page('last').draw('page');
                // console.log('initComplete');
            }
        });
    };
</script>

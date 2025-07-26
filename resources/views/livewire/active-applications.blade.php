<div class="py-5" style="padding: 0px !important;">
            <table id="warehouse-movements-table" class="display table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>Id</th> <!-- `guid` -->
                        <th>Tips</th> <!-- `type` -->
                        <th>Nav saņemts</th> <!-- `delta_quantity` -->
                        <th>Artikuls</th> <!-- `article` -->
                        <th>Datums</th> <!-- `date` -->
                        <th>Kods</th> <!-- `code` -->
                        <th>Statuss</th> <!-- `status` -->
                        <th>Pasūtījuma numurs</th> <!-- `order_number` -->
                        <th>Nosaukums</th> <!-- `name` -->
                        <th>Nosaukums 2</th> <!-- `name_2` -->
                        <th>Materiāla marka</th> <!-- `material_grade` -->
                        <th>Mērvienība</th> <!-- `unit` -->
                        <th>Daudzums</th> <!-- `quantity` -->
                        <th>Cena par vienību</th> <!-- `price_per_unit` -->
                        <th>Kopējā summa</th> <!-- `total_price` -->
                        <th>Piegādātāja Reģ</th> <!-- `supplier` -->
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
        <livewire:active-applications-form />    
</div>
<script>
    document.addEventListener('livewire:initialized', function () {
        // Inicializē DataTable
        var $table = $('#warehouse-movements-table').DataTable({
            // deferLoading: 0, // Neizmanto deferLoading, lai atslēgtu redzešanu
            processing: true,
            serverSide: true,
            stateSave: true,
            scrollX: true,
            fixedColumns: {
                start: 4 // Fiksē pirmās 4 kolonnas
            },
            autowidth: true,
            responsive: true,
            // scrollCollapse: true,
            destroy: true,
            buttons: [
                {
                    text: 'Jauns ieraksts',
                    action: function (e, dt, node, config) {
                        Livewire.dispatch('openModalNewDocActiveApplicationsForm');
                    }
                }
            ],
            ajax: {
                url: "{{ route('warehouse-materials') }}",
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: function (d) {
                    d.jjWarehouseCodeFilter = $('#jj-warehouse-code-filter').val(); // Pievieno vērtību 
                    // console.log('d.jjWarehouseCode:', d.jjWarehouseCodeFilter);
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
                        return '<span class="first-column">' + id + '</span> <i class="fas fa-edit" aria-label="Edit" title="Edit"></i>';
                    },
                },
                {data: 'type'},
                {
                    data: 'delta_quantity',
                    render: function (data, type, row, meta) {
                        return data;
                    }
                },
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
            }, {
                "targets": 0, // Pirmā kolonna (indekss sākas no 0)
                "createdCell": function(td, cellData, rowData, row, col) {
                    $(td).addClass('first-element');
                }
            }, /*{
                "targets": 1, // Pirmā kolonna (indekss sākas no 0)
                "createdCell": function(td, cellData, rowData, row, col) {
                    $(td).attr('contenteditable', 'true');
                }
            }*/
            ],
            lengthMenu: [
                [10, 25, 50],
                [10, 25, 50]
            ],
            layout: {
                topStart: 'buttons',
                topEnd: 'search',
                bottomStart: ['pageLength', 'info'],
                bottomEnd: ['paging']
            },
            initComplete: function (settings, json) {
                this.api().page('last').draw('page');
            }
        });
        
        document.addEventListener('dataTableAjaxReload-active-applications', function () {
            // console.log('dataTableAjaxReload-active-applications');
            $table.ajax.reload(null, false); // Nepārstartē lapošanu pēc atjaunināšanas
        });

        var $tableBody = $table.table().body();
        // Pievieno klikšķa notikumu katrai rindai
        $($tableBody).on('click', 'td.first-element', function () {
            var Id = $(this).closest('tr').data('id');
            if (!Id) {
                console.log('ID not found!');
                // toastr.warning("ID not found!");
            }
            Livewire.dispatch('openModal1', {id: Id});
        });

        $('#warehouse-movements-table tbody').on('input', 'td[contenteditable="true"]', function() {
                var cell = $table.cell(this);
                var rowData = $table.row(cell.index().row).data();
                var columnIndex = cell.index().column;
                var newValue = $(this).text();

                console.log('Šūnas vērtība mainīta:', {
                    row: cell.index().row,
                    column: columnIndex,
                    value: newValue
                });
        });
    });
</script>


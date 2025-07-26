<div class="py-5" style="padding: 0px !important;">
            <table id="employee-list" class="display table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>Id</th> <!-- `email` -->
                        <th>Email</th> <!-- `email` -->
                        <th>Vārds, Uzvārds</th> <!-- `name` -->
                    </tr>
                </thead>
            </table>
        <livewire:employee-list-form />  
</div>
<script>
    document.addEventListener('livewire:initialized', function () {
        // Inicializē DataTable
        var $table = $('#employee-list').DataTable({
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
                /*{
                    text: 'Jauns ieraksts',
                    action: function (e, dt, node, config) {
                        const doc = document.getElementById("employee-list-form");
                        Livewire.find(doc.getAttribute("wire:id")).dispatch('openModalNewDoc');
                    }
                }*/
            ],
            ajax: {
                url: "{{ route('employee-list') }}",
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: function (d) {
                    // d.jjWarehouseCodeFilter = $('#jj-warehouse-code-filter').val(); // Pievieno vērtību 
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
                {data: 'email'},
                {
                    data: 'name',
                    render: function (data, type, row, meta) {
                        return data;
                    }
                }
            ],
            columnDefs: [
            {
                width: '150px',
                targets: [1],
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
        
        document.addEventListener('dataTableAjaxReload-employees', function () {
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
            // Livewire.dispatch('openModal1', {id: Id});
            const doc = document.getElementById("employee-list-form");
            Livewire.find(doc.getAttribute("wire:id")).dispatch('openModalEmployee', {id: Id});
        });

        $('#employee-list tbody').on('input', 'td[contenteditable="true"]', function() {
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




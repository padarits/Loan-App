<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <div class="py-5">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <table id="transport-document" class="display table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <!--<th><i class="fas fa-truck"></i> Transportlīdzekļa numurs</th>-->
                            <th><i class="fas fa-file-check"></i> Statuss</th>
                            <th><i class="fas fa-file-alt"></i> Dokumenta numurs</th>
                            <th>Dokumenta datums</th>
                            <th><i class="fas fa-home"></i> Saņemēja nosaukums</th>
                            <th> Sūtītāja nosaukums</th>
                            <th>Auto Nr.</th>
                            <th>Pavisam</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <livewire:transport-document-form />
</x-app-layout>
<script>
    document.addEventListener('livewire:initialized', function () {
        // Initialize DataTables
        var $table = $('#transport-document').DataTable({
            // Enable processing and server-side
            processing: true,
            serverSide: true,
            stateSave: true,
            buttons: [
                {
                    text: 'Jauns ieraksts',
                    action: function ( e, dt, node, config ) {
                        Livewire.dispatch('openModalNewDoc');
                    }
                }
            ],
            ajax: {
                url: "./transport-documents",
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                error: function(xhr) {
                    if (xhr.status == 403) {
                        // If the user is unauthorized, reload the page
                        console.log("Session expired or unauthorized! Reloading page...");
                        document.dispatchEvent(new CustomEvent('showWarning', { detail: ["Session expired or unauthorized! Reloading page..."] }));
                        setTimeout(function() {
                            window.location.reload();
                        }, 3000);
                    } else {
                        // Otherwise, display the error message
                        console.log("An error occurred while loading data! Please try again later.");
                        console.log(xhr.statusText);
                        document.dispatchEvent(new CustomEvent('showError', { detail: ["An error occurred while loading data! Please try again later."] }));
                    }
                }
            },
            drawCallback: function (settings) { 
                // Here the response
                // var response = settings.json;
                // console.log(response);
            },
            createdRow: function(row, data, dataIndex) {
                // Add a data attribute, data-id
                $(row).attr('data-id', data.id);
            },   
            columns: [
                {   data: 'id', 
                    className: 'pointer',
                    render: function (data, type, row, meta) {
                        var data2 = '';
                        return '<span>' + data2 + '</span> ' + '<i class="fas fa-edit" aria-label="Edit" title="Edit"></i>';
                    },
                },
                { data: 'status', 
                    render: function (data, type, row, meta) {
                        switch (data) {
                            case '010-new':
                                return '<span style="display: inline-flex; align-items: center;"><i class="fas fa-file-alt" style="color: #007bff;" aria-label="Jauns" title="Jauns"></i> <span style="color: #007bff;"> Jauns</span></span>';  // New document icon (blue)
                            case '020-prepared':
                                return '<span style="display: inline-flex; align-items: center;"><i class="fas fa-file-signature" style="color: #28a745;" aria-label="Sagatavots" title="Sagatavots"></i> <span style="color: #28a745;"> Sagatavots</span></span>';  // Prepared document icon (green)
                            case '030-in_transit':
                                return '<span style="display: inline-flex; align-items: center;"><i class="fas fa-truck" style="color: #ffc107;" aria-label="Ceļā" title="Ceļā"></i> <span style="color: #ffc107;"> Ceļā</span></span>';  // In transit icon (yellow)
                            case '040-received':
                                return '<span style="display: inline-flex; align-items: center;"><i class="fas fa-file-import" style="color: #17a2b8;" aria-label="Saņemts" title="Saņemts"></i> <span style="color: #17a2b8;"> Saņemts</span></span>';  // Received document icon (cyan)
                            case '050-waiting':
                                return '<span style="display: inline-flex; align-items: center;"><i class="fas fa-hourglass-half" style="color: #fd7e14;" aria-label="Gaida" title="Gaida"></i> <span style="color: #fd7e14;"> Gaida</span></span>';  // Waiting icon (orange)
                            case '060-canceled':
                                return '<span style="display: inline-flex; align-items: center;"><i class="fas fa-times-circle" style="color: #dc3545;" aria-label="Atcēlts" title="Atcēlts"></i> <span style="color: #dc3545;"> Atcēlts</span></span>';  // Canceled icon (red)
                            default:
                                return '<span style="display: inline-flex; align-items: center;"><i class="fas fa-file" style="color: #6c757d;" aria-label="Unknown Status" title="Unknown Status"></i> <span style="color: #6c757d;">' + data + '</span></span>';  // Default icon (gray)
                        }                    
                    },
                },
                { data: 'document_number' },
                { data: 'document_date', className: 'text-align-left', },
                { data: 'receiver_name' },
                { data: 'supplier_name' },
                { data: 'vehicle_registration_number' },
                { 
                    data: 'fn_total_sum', 
                    "type": "num",      // Set as numeric
                    "orderable": false  // Disable sorting
                },
                //{ data: 'updated_at' },
            ],
            lengthMenu: [
                [10, 25, 50],
                [10, 25, 50]
            ],
            layout: {
                //top2Start: 'pageLength',
                //top2End: 'search',
                topStart: 'buttons', //'pageLength',
                topEnd: 'search',
                bottomStart: ['pageLength', 'info'],
                bottomEnd: ['paging'],
                //bottom2Start: 'info',
                //bottom2End: 'paging'
            },
            initComplete: function(settings, json) {
                // Open the last page when the table is first loaded
                this.api().page('last').draw('page');
            }
        });
        
        document.addEventListener('dataTableAjaxReload', function () {
            $table.ajax.reload(null, false); // user paging is not reset on reload
        });

        var $tableBody = $table.table().body();
        // Attach click event to each row in the DataTable
        $($tableBody).on('click', 'tr', function () {
            var Id = $(this).closest('tr').data('id');
            if (!Id) {
                // toastr.warning("ID not found!");
            }
            Livewire.dispatch('openModal', {id: Id});
        }); 
    });
</script>
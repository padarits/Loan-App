<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <div class="py-5">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <table id="users-table" class="display table table-striped table-bordered" style="width:100%" >
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th><i class="fas fa-user" style="color: grey;"></i> Lietotājs</th>
                            <th><i class="fas fa-envelope" style="color: grey;"></i> e-pasts</th>
                            <th><i class="fas fa-check-circle" style="color: #28a745;"></i> e-pasts apstiprināts</th>
                            <th>Izveidots</th>
                            <!--<th><i class="fas fa-home"></i> Saņemēja nosaukums</th>
                            <th>Auto Nr.</th>
                            <th>Pavisam</th>-->
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- user-profile.blade.php -->
    <livewire:manage-user-roles />    
    <!--<livewire:user-modal />-->
</x-app-layout>
<script>
    document.addEventListener('livewire:initialized', function () {
        var $table = $('#users-table').DataTable({
        // dom: '<"top"fl>rt<"bottom"ip><"clear">',   // Place length dropdown in footer
        // Enable processing and server-side
        processing: true,
        serverSide: true,
        stateSave: true,
        ajax: {
            url: './users',
            type: 'GET',
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
                    showData = '' ; //data;
                    return '<span>' + showData + '</span> ' + '<i class="fas fa-edit" aria-label="Edit" title="Edit"></i>';
                },
            },
            { data: 'name' },
            { data: 'email' },
            { data: 'email_verified_at' },
            { data: 'created_at' },
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
    });

    document.addEventListener('dataTableAjaxReload', function () {
        $table.ajax.reload(null, false); // user paging is not reset on reload
    });
    
    var $tableBody = $table.table().body();
    // Attach click event to each row in the DataTable
    $($tableBody).on('click', 'tr', function () {
        var userId = $(this).closest('tr').data('id');
        if (!userId) {
            // toastr.warning("ID not found!");
        }
        Livewire.dispatch('openModal', {id: userId});
    });
    });
</script>
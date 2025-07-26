import jQuery from 'jquery';
import "datatables.net-dt";
window.$ = window.jQuery = jQuery;

import DataTable from 'datatables.net-dt';
window.DataTable = DataTable;

// Import DataTables Buttons extension (optional if using buttons)
import "datatables.net-buttons-dt";
import "datatables.net-buttons/js/buttons.html5.js";
import "datatables.net-buttons/js/buttons.print.js";

// Import FontAwesome CSS
import '@fortawesome/fontawesome-free/css/all.min.css';

// Import toastr
import toastr from 'toastr';
import 'toastr/build/toastr.min.css';

// jQuery kods
$(function(){
    // Initialize DataTables
    var $table = $('#purchase-invoice-header').DataTable({
        // Enable processing and server-side
        processing: true,
        serverSide: true,
        stateSave: true,
        ajax: {
            url: './api/purchase-invoice-headers',
            type: 'GET',
            headers: {
                'Authorization': 'Bearer ' + $('meta[name="api-token"]').attr('content') // Add api token for AJAX requests
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
                    return '<span>' + data + '</span> ' + '<i class="fas fa-edit" aria-label="Edit" title="Edit"></i>';
                },
            },
            { data: 'name' },
            { data: 'email' },
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
            topStart: 'pageLength',
            topEnd: 'search',
            bottomStart: 'info',
            bottomEnd: 'paging',
            //bottom2Start: 'info',
            //bottom2End: 'paging'
        }
    });

    var $tableBody = $table.table().body();
    // Attach click event to each row in the DataTable
    $($tableBody).on('click', 'tr', function () {
        var userId = $(this).closest('tr').data('id');
        if (!userId) {
            toastr.warning("ID not found!");
        }
        Livewire.dispatch('openModal', {id: userId});
    });
});

// Example of using Toastr
/*window.addEventListener('showWarning', event => {
    if (typeof toastr !== 'undefined') {
        console.log(event.detail[0]);
        toastr.warning(event.detail[0]);
    } else {
        console.error('Toastr is not defined');
    }
});*/

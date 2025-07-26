import './bootstrap';

// Import the DataTable initialization script
import './purchase-invoice-header';

import $ from 'jquery';
import jQuery from 'jquery';
import "datatables.net-dt";
window.$ = window.jQuery = jQuery;

import DataTable from 'datatables.net-dt';
window.DataTable = DataTable;

// Import DataTables Buttons extension (optional if using buttons)
import 'datatables.net-buttons'
import "datatables.net-buttons-dt";
import "datatables.net-buttons/js/buttons.html5.js";
import "datatables.net-buttons/js/buttons.print.js";
// Import Buttons CSS
import 'datatables.net-buttons-dt/css/buttons.dataTables.css';

// Import FontAwesome CSS
import '@fortawesome/fontawesome-free/css/all.min.css';

// Import toastr
import toastr from 'toastr';
import 'toastr/build/toastr.min.css';

import 'jquery-ui';
import 'jquery-ui/ui/widgets/datepicker.js';
import 'jquery-ui/ui/i18n/datepicker-lv.js';

// Import jQuery UI
import 'jquery-ui/ui/widgets/dialog';
// Import the button widget from jQuery UI
import 'jquery-ui/ui/widgets/button';

import 'jquery-ui/ui/widgets/menu'; // Import the menu widget
import 'jquery-ui/ui/unique-id';     // Ensure that the uniqueId utility is imported
import 'jquery-ui/ui/keycode';  // Import the KeyCode utility
import 'jquery-ui/ui/widgets/autocomplete'; // If you're using autocomplete as well
import 'jquery-ui/themes/base/all.css'; // Optionally import jQuery UI CSS theme
// import 'jquery-ui/ui/widgets/tooltip'; // Import the tooltip widget
import 'datatables.net-fixedcolumns-dt'; // Importējiet FixedColumns paplašinājumu

$(function(){
    // Example of using Toastr
    window.addEventListener('showWarning', event => {
        if (typeof toastr !== 'undefined') {
            console.log(event.detail[0]);
            toastr.warning(event.detail[0], "Sistēma", {
                "timeOut": "10000",
                "extendedTimeOut": "10000"
              });
        } else {
            console.error('Toastr is not defined');
        }
    });
    window.addEventListener('showInfo', event => {
        if (typeof toastr !== 'undefined') {
            console.log(event.detail[0]);
            toastr.info(event.detail[0]);
        } else {
            console.error('Toastr is not defined');
        }
    });
    window.addEventListener('showSuccess', event => {
        if (typeof toastr !== 'undefined') {
            console.log(event.detail[0]);
            toastr.success(event.detail[0]);
        } else {
            console.error('Toastr is not defined');
        }
    });
    window.addEventListener('showError', event => {
        if (typeof toastr !== 'undefined') {
            console.log(event.detail[0]);
            toastr.error(event.detail[0], null, {
                "timeOut": "10000",           // Prevents the toast from auto-closing
                "extendedTimeOut": "0",   // Ensures it doesn't close after user interaction
                "closeButton": true       // Adds a close button for manual dismissal
              });
        } else {
            console.error('Toastr is not defined');
        }
    });  
     
});


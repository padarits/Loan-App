<?php

use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use App\Http\Controllers\TransportDocumentController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\LoanDocController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\MainMiddleware;
use App\Http\Livewire\WarehouseMaterial;
use App\Http\Livewire\Department;
use App\Http\Controllers\DataTable\WarehouseMaterialMovementsController;
use App\Http\Controllers\DataTable\WarehouseMaterialMovementsBalanceController;
use App\Http\Controllers\DataTable\WarehouseMaterialMovementsStockReceiptController;
use App\Http\Controllers\DataTable\WarehouseMaterialMovementsStockDispatchController;
use App\Http\Controllers\DataTable\DepartmetListController;
use App\Http\Controllers\DataTable\EmployeeListController;
use App\Http\Controllers\DataTable\PositionListController;

use App\Http\Controllers\StockHistoryController;

Livewire::setScriptRoute(function ($handle) {
    return Route::get( env('SiteApp', 'http://localhost') . '/vendor/livewire/livewire.min.js');
});

Livewire::setUpdateRoute(function ($handle) {
    return Route::post( env('SiteApp') . '/livewire/update', $handle)->name('app.livewire.update');
});

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    
    Route::view('/loans', 'loans.index')->name('loans.index');  
    // Maršruts PDF ģenerēšanai ar parametru {loanId}
    Route::get('/loan/pdf/{loanId}', [LoanDocController::class, 'generatePdf'])->name('Loan.pdf');

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/purchase-invoice-header', function () {
        return view('purchase-invoice-header');
    })->name('purchase-invoice-header');

    Route::middleware([MainMiddleware::class . ':transport,transport-edit'])->group(function () {
        Route::get('/transport-document', function () {
            return view('transport-document');
        })->name('transport-document');

        Route::post('/transport-documents', [TransportDocumentController::class, 'index'])->name('transport-documents');
        Route::get('/search-receiver-reg-number', [TransportDocumentController::class, 'search_receiver_reg_number'])->name('search-receiver-reg-number');
        Route::get('/search-supplier-reg-number', [TransportDocumentController::class, 'search_supplier_reg_number'])->name('search-supplier-reg-number');
        Route::get('/search-receiver-address', [TransportDocumentController::class, 'search_receiver_address'])->name('search-receiver-address');
        Route::get('/search-supplier-address', [TransportDocumentController::class, 'search_supplier_address'])->name('search-supplier-address');
        Route::get('/search-item-article', [TransportDocumentController::class, 'search_item_article'])->name('search-item-article');
        // Maršruts PDF ģenerēšanai ar parametru {invoiceId}
        Route::get('/invoice/pdf/{invoiceId}', [InvoiceController::class, 'generatePdf'])->name('invoice.pdf');
    });
    
    Route::middleware([MainMiddleware::class . ':transport,transport-edit'])->group(function () {
        Route::post('/warehouse-materials', [WarehouseMaterialMovementsController::class, 'index'])->name('warehouse-materials');
        Route::post('/warehouse-balance', [WarehouseMaterialMovementsBalanceController::class, 'index'])->name('warehouse-balance');      
        Route::post('/stock-receipt', [WarehouseMaterialMovementsStockReceiptController::class, 'index'])->name('stock-receipt');
        Route::post('/stock-dispatch', [WarehouseMaterialMovementsStockDispatchController::class, 'index'])->name('stock-dispatch');
        Route::get('/search-article', [WarehouseMaterialMovementsController::class, 'search_article'])->name('search-article');

        Route::get('/search-name1', [WarehouseMaterialMovementsController::class, 'search_name1'])->name('search-name1');
        Route::get('/search-name2', [WarehouseMaterialMovementsController::class, 'search_name2'])->name('search-name2');
        Route::get('/search-material-grade', [WarehouseMaterialMovementsController::class, 'search_material_grade'])->name('search-material-grade');
        Route::get('/search-recipient', [WarehouseMaterialMovementsController::class, 'search_recipient'])->name('search-recipient');
        Route::get('/search-supplier-by-reg-number', [WarehouseMaterialMovementsController::class, 'search_supplier_by_reg_number'])->name('search-supplier-by-reg-number');
        Route::get('/search-item-code', [WarehouseMaterialMovementsController::class, 'search_item_code'])->name('search-item-code');

        Route::get('/warehouse-material', WarehouseMaterial::class)->name('warehouse-material');
        Route::post('/stock-history', [StockHistoryController::class, 'index'])->name('stock-history');
    });
    
    Route::middleware([MainMiddleware::class . ':department,department-edit'])->group(function () {
        Route::get('/department', Department::class)->name('department');
        Route::post('/department-list', [DepartmetListController::class, 'index'])->name('department-list');
        Route::post('/employee-list', [EmployeeListController::class, 'index'])->name('employee-list');
        Route::post('/position-list', [PositionListController::class, 'index'])->name('position-list');
        Route::get('/search-position', [PositionListController::class, 'search_position'])->name('search-position');
    });

    Route::middleware([AdminMiddleware::class])->group(function () {
        Route::get('/user-right', function () {
            return view('user-right');
        })->name('user-right');
        
        Route::get('/users', [UserController::class, 'index'])->name('users');
    });
});

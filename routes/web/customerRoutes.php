<?php
use Illuminate\Support\Facades\Route;

// Client Invoice Routes
Route::get('/customer/getAll', [\App\Http\Controllers\CustomerController::class, 'getAllCustomers'])->name('getAllCustomers');
Route::get('/customer/add', [\App\Http\Controllers\CustomerController::class, 'addForm'])->name('addCustomer');
Route::get('/customer/{id}', [\App\Http\Controllers\CustomerController::class, 'editForm'])->name('showCustomer');

Route::post('/addCustomer', [\App\Http\Controllers\CustomerController::class, 'addCustomer'])->name('addCustomerAjax');
Route::post('/updateCustomer', [\App\Http\Controllers\CustomerController::class, 'updateCustomer'])->name('updateCustomerAjax');
Route::post('/updateCustomerInvoiceNotification', [\App\Http\Controllers\CustomerController::class, 'updateCustomerInvoiceNotification'])->name('updateCustomerInvoiceNotificationAjax');

?>

<?php
use Illuminate\Support\Facades\Route;

Route::get('/viewCustomerInvoice/{id}', [\App\Http\Controllers\CustomerInvoiceController::class, 'viewCustomerInvoice'])->name('viewCustomerInvoice');
Route::get('/sendCustomerInvoice/{id}', [\App\Http\Controllers\CustomerInvoiceController::class, 'sendInvoice'])->name('sendCustomerInvoice');

?>

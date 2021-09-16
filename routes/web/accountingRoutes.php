<?php
use Illuminate\Support\Facades\Route;

// Client Invoice Routes
Route::get('/accounting/getClientInvoices', [\App\Http\Controllers\AccountingController::class, 'getClientInvoices'])->name('getAccountClientInvoices');
Route::get('/accounting/getClientReceivables', [\App\Http\Controllers\AccountingController::class, 'getClientReceivables'])->name('getAccountClientReceivables');
Route::get('/accounting/getClientReceivableReport', [\App\Http\Controllers\AccountingController::class, 'getClientReceivableReport'])->name('getAccountClientReceivableReport');
Route::get('/accounting/getClientRemittanceReport', [\App\Http\Controllers\AccountingController::class, 'getClientRemittanceReport'])->name('getClientRemittanceReport');


// Customer Invoice Routes
Route::get('/accounting/getCustomerInvoices', [\App\Http\Controllers\AccountingController::class, 'getCustomerInvoices'])->name('getAccountCustomerInvoices');
Route::get('/accounting/getCustomerReceivables', [\App\Http\Controllers\AccountingController::class, 'getCustomerReceivables'])->name('getAccountCustomerReceivables');

// Client Remittance Report
Route::get('/accounting/getClientRemittance', [\App\Http\Controllers\AccountingController::class, 'getClientRemittance'])->name('getAccountClientRemittance');
Route::get('/accounting/getClientRemittanceDetails', [\App\Http\Controllers\AccountingController::class, 'getClientRemittanceDetails'])->name('getClientRemittanceDetails');



?>
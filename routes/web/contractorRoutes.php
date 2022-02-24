<?php
use Illuminate\Support\Facades\Route;

Route::get('/contractor/get', [\App\Http\Controllers\ContractorController::class, 'get'])->name('getContractor');
Route::get('/contractor/add', [\App\Http\Controllers\ContractorController::class, 'addContractorForm'])->name('addContractor');
Route::get('/contractor/authList', [\App\Http\Controllers\ContractorController::class, 'getAuthorizations'])->name('getContractorAuthorizationList');
Route::get('/contractor/{id}', [\App\Http\Controllers\ContractorController::class, 'editContractorForm'])->name('editContractor');

Route::post('/addNew', [\App\Http\Controllers\ContractorController::class, 'addNewContractor'])->name('addNewContractor');
Route::post('/updateContractor', [\App\Http\Controllers\ContractorController::class, 'updateContractor'])->name('updateContractor');
Route::post('/updateContractorInvoiceNotification', [\App\Http\Controllers\ContractorController::class, 'updateContractorInvoiceNotification'])->name('updateContractorInvoiceNotificationAjax');
Route::post('/findContractors', [\App\Http\Controllers\ContractorController::class, 'findContractors'])->name('findContractors');
Route::post('/viewContractorMarker', [\App\Http\Controllers\ContractorController::class, 'viewContractorMarker'])->name('viewContractorMarker');

?>

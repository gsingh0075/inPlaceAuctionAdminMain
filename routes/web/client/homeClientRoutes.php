<?php
use Illuminate\Support\Facades\Route;

Route::get('/getClientInvoices', [\App\Http\Controllers\ClientHomeController::class, 'getClientInvoices'])->name('getClientInvoices');
Route::get('/viewMyInvoice/{id}/{assignmentId}', [\App\Http\Controllers\ClientHomeController::class, 'viewClientInvoice'])->name('getMyInvoices');

Route::get('logoUpload', [ \App\Http\Controllers\ClientHomeController::class, 'logoUpload' ])->name('client.logoUpload');
Route::post('logoUpload', [ \App\Http\Controllers\ClientHomeController::class, 'logoUploadPost' ])->name('client.logoUpload.post');

?>

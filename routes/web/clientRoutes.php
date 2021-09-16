<?php
use Illuminate\Support\Facades\Route;

Route::get('/client/get', [\App\Http\Controllers\ClientsController::class, 'get'])->name('getClients');
Route::get('/client/{id}', [\App\Http\Controllers\ClientsController::class, 'show'])->name('showClient');
Route::get('/client/addContactClient/{id}', [\App\Http\Controllers\ClientsController::class, 'addContact'])->name('addContactClient');
Route::get('/addClient', [\App\Http\Controllers\ClientsController::class, 'addForm'])->name('addClient');
Route::get('/clientChat', [\App\Http\Controllers\ClientsController::class, 'clientChat'])->name('clientChat');
Route::get('/loginAsClient/{id}', [\App\Http\Controllers\ClientsController::class, 'loginAsClient'])->name('loginAsClient');

Route::post('/addClient', [\App\Http\Controllers\ClientsController::class, 'addClient'])->name('addClientAjax');
Route::post('/updateClient', [\App\Http\Controllers\ClientsController::class, 'updateClient'])->name('updateClientAjax');
Route::post('/addClientContact', [\App\Http\Controllers\ClientsController::class, 'addClientContact'])->name('addClientContactAjax');
Route::post('/getClientChat', [\App\Http\Controllers\ClientsController::class, 'getClientChat'])->name('getClientChat');
Route::post('/saveCommunication', [\App\Http\Controllers\ClientsController::class, 'saveCommunication'])->name('saveCommunication');
Route::post('/updateInvoiceNotification', [\App\Http\Controllers\ClientsController::class, 'updateInvoiceNotification'])->name('updateInvoiceNotificationAjax');

?>

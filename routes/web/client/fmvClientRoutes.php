<?php
use Illuminate\Support\Facades\Route;

Route::get('/fmvClient/get', [\App\Http\Controllers\FmvClientController::class, 'get'])->name('getFmvClient');
Route::get('/getFmvClientDatatable', [\App\Http\Controllers\FmvClientController::class, 'getDatatable'])->name('getFmvClientDatatable');
Route::get('/generateFmvClientPDF/{id}', [\App\Http\Controllers\FmvClientController::class, 'generateFmvClientPDF'])->name('generateFmvClientPDF');
Route::get('/fmvClient/add', [\App\Http\Controllers\FmvClientController::class, 'addForm'])->name('addFmvClient');
Route::post('/addFmvClientAjax', [\App\Http\Controllers\FmvClientController::class, 'addFmv'])->name('addFmvClientAjax');

?>

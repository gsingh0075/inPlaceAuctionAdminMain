<?php
use Illuminate\Support\Facades\Route;

// Get Routes
Route::get('/fmv/get', [\App\Http\Controllers\FmvController::class, 'get'])->name('getFmv');
Route::get('/fmv/getArchiveFMV', [\App\Http\Controllers\FmvController::class, 'getArchiveFMV'])->name('getArchiveFMV');
Route::get('/fmv/add', [\App\Http\Controllers\FmvController::class, 'addForm'])->name('addFmv');
Route::get('/fmv/{id}', [\App\Http\Controllers\FmvController::class, 'show'])->name('showFmv');
Route::get('/generatePDF/{id}', [\App\Http\Controllers\FmvController::class, 'generatePDF'])->name('generatePDF');
Route::get('/sendFmv/{id}', [\App\Http\Controllers\FmvController::class, 'sendFmv'])->name('sendFmv');
Route::get('/deleteFmv/{id}', [\App\Http\Controllers\FmvController::class, 'deleteFmv'])->name('deleteFmv');
Route::get('/fmvDeleteItem/{id}', [\App\Http\Controllers\FmvController::class, 'deleteItem'])->name('deleteItemFmv');
Route::get('/fmvDeleteFile/{id}', [\App\Http\Controllers\FmvController::class, 'deleteFile'])->name('deleteFileFmv');
Route::get('/editItem/{id}', [\App\Http\Controllers\FmvController::class, 'showItem'])->name('editItem');

// Post Routes
Route::post('/updateFmvItemAjax', [\App\Http\Controllers\FmvController::class, 'updateItem'])->name('updateFmvItemAjax');
Route::post('/addFmv', [\App\Http\Controllers\FmvController::class, 'addFmv'])->name('addFmvAjax');
Route::post('/updateFmv', [\App\Http\Controllers\FmvController::class, 'updateFmv'])->name('updateFmvAjax');
Route::post('/addItem', [\App\Http\Controllers\FmvController::class, 'addItem'])->name('addItemAjax');
Route::post('/addFmvFiles', [\App\Http\Controllers\FmvController::class, 'addFiles'])->name('addFmvFiles');
Route::post('/itemSuggestion', [\App\Http\Controllers\FmvController::class, 'itemSuggestion'])->name('itemSuggestion');

?>

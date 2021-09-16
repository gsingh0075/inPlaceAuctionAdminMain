<?php
use Illuminate\Support\Facades\Route;

Route::get('/itemClient/get', [\App\Http\Controllers\ItemsClientController::class, 'get'])->name('getItemsClient');


?>

<?php

use Illuminate\Support\Facades\Route;

Route::get('/show/{id}', [App\Http\Controllers\NotificationController::class, 'show'])->name('showNotification');

?>
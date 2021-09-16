<?php
use Illuminate\Support\Facades\Route;

Route::get('/user/changePassword', [\App\Http\Controllers\UserController::class, 'changePassword'])->name('userChangePassword');

Route::post('/user/updatePassword', [\App\Http\Controllers\UserController::class, 'updatePassword'])->name('userUpdatePassword');

?>

<?php
use Illuminate\Support\Facades\Route;

Route::get('/assignmentClient/get', [\App\Http\Controllers\AssignmentClientController::class, 'get'])->name('getAssignmentClient');
Route::get('/assignmentClient/add', [\App\Http\Controllers\AssignmentClientController::class, 'addNewAssignment'])->name('addNewAssignmentClient');
Route::get('/assignmentClient/{id}', [\App\Http\Controllers\AssignmentClientController::class, 'show'])->name('showAssignmentClient');
Route::post('/saveClientCommunicationAssignment', [\App\Http\Controllers\AssignmentClientController::class, 'saveClientCommunicationAssignment'])->name('saveClientCommunicationAssignment');

Route::post('/saveClientCommunication', [\App\Http\Controllers\AssignmentClientController::class, 'saveClientCommunication'])->name('saveClientCommunication');


?>

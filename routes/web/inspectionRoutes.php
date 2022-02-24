<?php
use Illuminate\Support\Facades\Route;

Route::get('/inspection/get', [\App\Http\Controllers\InspectionController::class, 'get'])->name('getInspection');
Route::get('/getInspectionDatatable', [\App\Http\Controllers\InspectionController::class, 'getDatatable'])->name('getInspectionDatatable');
Route::get('/inspection/add', [\App\Http\Controllers\InspectionController::class, 'addForm'])->name('addNewInspection');
Route::get('/inspection/reports', [\App\Http\Controllers\InspectionController::class, 'getInspectionReports'])->name('getInspectionReports');
//Route::get('/inspection/report/{id}', [\App\Http\Controllers\InspectionController::class, 'showReport'])->name('showReport');
Route::get('/inspection/report/{id}', [\App\Http\Controllers\InspectionController::class, 'showReport'])->name('showReport');
?>

<?php

use Illuminate\Support\Facades\Route;

// Admin Dashboard
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/yearComparisonChart', [App\Http\Controllers\HomeController::class, 'yearComparisonChart'])->name('yearComparisonChart');
Route::get('/getUnsoldItemsAssignments', [App\Http\Controllers\HomeController::class, 'getUnsoldItemsAssignments'])->name('getUnsoldItemsAssignments');
Route::get('/getAllClosedAssignments', [App\Http\Controllers\HomeController::class, 'getAllClosedAssignments'])->name('getAllClosedAssignments');

Route::post('/assignmentMarker', [\App\Http\Controllers\HomeController::class, 'assignmentMarker'])->name('assignmentMarker');
Route::post('/loadFmvTypeAnalysis', [App\Http\Controllers\HomeController::class, 'loadFmvTypeAnalysis'])->name('loadFmvTypeAnalysis');
Route::post('/getEquipmentInvoices', [App\Http\Controllers\HomeController::class, 'getEquipmentInvoices'])->name('getEquipmentInvoices');
Route::post('/getCustomerInvoices', [App\Http\Controllers\HomeController::class, 'getCustomerInvoices'])->name('getCustomerInvoices');
Route::post('/getClientRemittance', [App\Http\Controllers\HomeController::class, 'getClientRemittance'])->name('getClientRemittance');
Route::post('/homeAnalytics', [App\Http\Controllers\HomeController::class, 'homeAnalytics'])->name('homeAnalytics');

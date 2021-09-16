<?php
use Illuminate\Support\Facades\Route;

Route::get('/assignment/add', [\App\Http\Controllers\AssignmentController::class, 'addForm'])->name('addNewAssignment');
Route::get('/assignment/get', [\App\Http\Controllers\AssignmentController::class, 'get'])->name('getAssignment');
Route::get('/getAssignmentDatatable', [\App\Http\Controllers\AssignmentController::class, 'getDatatable'])->name('getAssignmentDatatable');
Route::get('/assignment/{id}', [\App\Http\Controllers\AssignmentController::class, 'show'])->name('showAssignment');
Route::get('/createAssignmentFromFmv/{id}', [\App\Http\Controllers\AssignmentController::class, 'createAssignmentFromFmv'])->name('createAssignmentFromFmv');
Route::post('/addAssignmentFiles', [\App\Http\Controllers\AssignmentController::class, 'addFiles'])->name('addAssignmentFiles');
Route::post('/visibilityReportFiles', [\App\Http\Controllers\AssignmentController::class, 'visibilityReportFiles'])->name('visibilityReportFiles');
Route::get('/assignmentDeleteFile/{id}', [\App\Http\Controllers\AssignmentController::class, 'deleteFile'])->name('deleteAssignmentFmv');
Route::get('/fmvToAssignment', [\App\Http\Controllers\AssignmentController::class, 'fmvToAssignmentByClient'])->name('fmvToAssignment')->middleware('signed');
Route::get('/uploadAuthorizedItemPictures', [\App\Http\Controllers\AssignmentController::class, 'uploadAuthorizedItemPictures'])->name('uploadAuthorizedItemPictures');
//->middleware('signed')

Route::post('/addNewAssignment', [\App\Http\Controllers\AssignmentController::class, 'addNewAssignment'])->name('saveNewAssignment');
Route::post('/updateAssignment', [\App\Http\Controllers\AssignmentController::class, 'updateAssignment'])->name('updateAssignment');

Route::post('/getChildInfoAssignment', [\App\Http\Controllers\AssignmentController::class, 'getChildInfoAssignment'])->name('getChildInfoAssignment');
Route::post('/createAssignmentFromFmvByClient', [\App\Http\Controllers\AssignmentController::class, 'createAssignmentFromFmvByClient'])->name('createAssignmentFromFmvByClient');
Route::post('/findNearByContractors', [\App\Http\Controllers\AssignmentController::class, 'findNearByContractors'])->name('findNearByContractors');
Route::post('/saveCommunicationAssignment', [\App\Http\Controllers\AssignmentController::class, 'saveCommunicationAssignment'])->name('saveCommunicationAssignment');
Route::post('/contractorMarker', [\App\Http\Controllers\AssignmentController::class, 'contractorMarker'])->name('contractorMarker');
Route::post('/authorizeContractor', [\App\Http\Controllers\AssignmentController::class, 'authorizeContractor'])->name('authorizeContractor');
Route::get('/viewContractorAuthorization/{id}/{assignmentId}', [\App\Http\Controllers\AssignmentController::class, 'viewContractorAuthorization'])->name('viewContractorAuthorization');
Route::get('/viewClientInvoice/{id}/{assignmentId}', [\App\Http\Controllers\AssignmentController::class, 'viewClientInvoice'])->name('viewClientInvoice');
Route::get('/sendClientInvoice/{id}/{assignmentId}', [\App\Http\Controllers\AssignmentController::class, 'sendClientInvoice'])->name('sendClientInvoice');
Route::get('/sendContractorAuthorization/{id}/{assignmentId}', [\App\Http\Controllers\AssignmentController::class, 'sendContractorAuthorization'])->name('sendContractorAuthorization');

Route::post('/customerInvoicePaid', [\App\Http\Controllers\AssignmentController::class, 'customerInvoicePaid'])->name('customerInvoicePaid');
Route::post('/clientInvoicePaid', [\App\Http\Controllers\AssignmentController::class, 'clientInvoicePaid'])->name('clientInvoicePaid');
Route::post('/customerAmountRemitted', [\App\Http\Controllers\AssignmentController::class, 'customerAmountRemitted'])->name('customerAmountRemitted');

Route::get('/viewClientRemittance/{id}', [\App\Http\Controllers\AssignmentController::class, 'viewClientRemittancePdf'])->name('viewClientRemittancePdf');

Route::get('/closedAssignments', [\App\Http\Controllers\AssignmentController::class, 'closedAssignments'])->name('closedAssignments');

Route::get('/reAssignAssignments', [\App\Http\Controllers\AssignmentController::class, 'reassignAssignment'])->name('reassignAssignment');
Route::post('/ajaxReassignAssignment', [\App\Http\Controllers\AssignmentController::class, 'ajaxReassignAssignment'])->name('ajaxReassignAssignment');

?>

<?php
use Illuminate\Support\Facades\Route;

Route::get('/item/add/{id}', [\App\Http\Controllers\ItemController::class, 'addForm'])->name('addItem');
Route::get('/item/view/{id}', [\App\Http\Controllers\ItemController::class, 'editForm'])->name('viewItem');
Route::get('/item/images/{id}', [\App\Http\Controllers\ItemController::class, 'getItemImages'])->name('itemImages');
Route::get('/deleteReport/{id}', [\App\Http\Controllers\ItemController::class, 'deleteReport'])->name('deleteReport');
Route::get('/acceptBid/{id}', [\App\Http\Controllers\ItemController::class, 'acceptBid'])->name('acceptBid');
Route::get('/deleteItemPicture/{id}', [\App\Http\Controllers\ItemController::class, 'deleteItemPicture'])->name('deleteItemPicture');
Route::get('/viewItemImage/{id}', [\App\Http\Controllers\ItemController::class, 'viewItemImage'])->name('viewItemImage');
Route::get('/equipments/listBids', [\App\Http\Controllers\ItemController::class, 'listBids'])->name('listBids');
Route::get('/equipments/listItems', [\App\Http\Controllers\ItemController::class, 'listItems'])->name('listItems');

Route::post('/saveItem', [\App\Http\Controllers\ItemController::class, 'saveItem'])->name('saveItemAssignment');
Route::post('/updateItem', [\App\Http\Controllers\ItemController::class, 'updateItem'])->name('updateItemAssignment');
Route::post('/addItemImages', [\App\Http\Controllers\ItemController::class, 'addImages'])->name('addItemImages');
Route::post('/addReports', [\App\Http\Controllers\ItemController::class, 'addReports'])->name('addReports');
Route::post('/addBidsToItem', [\App\Http\Controllers\ItemController::class, 'addBidsToItem'])->name('addBidsToItem');
Route::post('/addExpenseToItem', [\App\Http\Controllers\ItemController::class, 'addExpenseToItem'])->name('addExpenseToItem');
Route::post('/generatePictureReport', [\App\Http\Controllers\ItemController::class, 'generatePictureReport'])->name('generatePictureReport');
Route::post('/visibilityReport', [\App\Http\Controllers\ItemController::class, 'visibilityReport'])->name('visibilityReport');
Route::post('/generateCustomerInvoice', [\App\Http\Controllers\ItemController::class, 'generateCustomerInvoice'])->name('generateCustomerInvoice');


// Category Routes.
Route::get('/tools/listCategories', [\App\Http\Controllers\ItemController::class, 'listCategories'])->name('listCategories');

Route::post('/addNewCategory', [\App\Http\Controllers\ItemController::class, 'addNewCategory'])->name('addNewCategory');
Route::post('/updateCategory', [\App\Http\Controllers\ItemController::class, 'updateCategory'])->name('updateCategory');


?>

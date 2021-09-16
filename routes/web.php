<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/********* HOME PAGE BEFORE LOGIN *********************/
Route::get('/', function () { return redirect()->route('login'); });

Auth::routes();
Route::get('/login/client', [\App\Http\Controllers\Auth\LoginController::class, 'showLoginFormClient'])->name('clientLoginForm');
Route::post('/login/client', [\App\Http\Controllers\Auth\LoginController::class, 'clientLogin'])->name('clientLogin');
Route::get('/clientHome', [\App\Http\Controllers\ClientHomeController::class, 'home'])->name('homePageClient');

require base_path('routes/web/dashboardRoutes.php');
require base_path('routes/web/clientRoutes.php');
require base_path('routes/web/fmvRoutes.php');
require base_path('routes/web/assignmentRoutes.php');
require base_path('routes/web/itemRoutes.php');
require base_path('routes/web/customerInvoiceRoutes.php');
require base_path('routes/web/contractorRoutes.php');
require base_path('routes/web/accountingRoutes.php');
require base_path('routes/web/customerRoutes.php');
require base_path('routes/web/userRoutes.php');
require base_path('routes/web/notificationRoutes.php');

require base_path('routes/web/client/assignmentClientRoutes.php');
require base_path('routes/web/client/fmvClientRoutes.php');
require base_path('routes/web/client/homeClientRoutes.php');
require base_path('routes/web/client/itemRoutes.php');




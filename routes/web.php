<?php

use Illuminate\Support\Facades\Route;
use TCG\Voyager\Facades\Voyager;

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

Route::redirect('att','admin');
Route::group(['prefix' => 'att/admin'], function () {
    Voyager::routes();
    Route::group(['middleware' => 'auth'], function () {

        Route::get('addActivity', [\App\Http\Controllers\TransactionController::class, 'addActivity'])->name('add-activity');
        Route::post('storeSession', [\App\Http\Controllers\TransactionController::class, 'storeSession'])->name('store-session');
        Route::post('/attend/store/{session}', [\App\Http\Controllers\TransactionController::class, 'storeTransaction'])->name('store-trans');
        Route::get('/atttttt', [\App\Http\Controllers\TransactionController::class, 'getAllocator'])->name('get-allocator');
    });
});

<?php

// use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\ProductController;

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


//Clear route cache:
 Route::get('/route-cache', function() {
     $exitCode = Artisan::call('route:cache');
     return 'Routes cache cleared';
 });

 //Clear config cache:
 Route::get('/config-cache', function() {
     $exitCode = Artisan::call('config:cache');
     return 'Config cache cleared';
 }); 

// Clear application cache:
 Route::get('/clear-cache', function() {
     $exitCode = Artisan::call('cache:clear');
     return 'Application cache cleared';
 });

 // Clear view cache:
 Route::get('/view-clear', function() {
     $exitCode = Artisan::call('view:clear');
     return 'View cache cleared';
 });

Route::get('/', function () {
    return view('welcome');
})->middleware(['verify.shopify'])->name('home');

//This will redirect user to login page.
Route::get('/login', function () {
    if (Auth::user()) {
        return redirect()->route('home');
    }
    return view('login');
})->name('login');

Route::get('api/v1/handling-fees-add', 'App\Http\Controllers\ProductController@createHandlingProduct')->name('api-handling-fees-add');
Route::any('api/v1/cart-create-fees', 'App\Http\Controllers\CartFeesController@createCartFees')->name('api-cart-fees-add');
Route::any('api/v1/get-fees', 'App\Http\Controllers\CartFeesController@getAllFees')->name('api-get-fees');
Route::any('api/v1/get-fix-price-product', 'App\Http\Controllers\ProductController@getFixProductsForCart')->name('api-get-fix-product');
Route::delete('api/v1/user/delete', 'App\Http\Controllers\CartFeesController@deleteFees')->name('delete-fees');
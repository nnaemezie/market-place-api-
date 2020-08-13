<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'auth:api'], function () {
    Route::apiResource('/products','ProductController');
    Route::get('/user/products', 'UserController@products');
});

Route::get('/all-products', 'ProductOnlyController@index')->name('products');
Route::get('/all-services', 'ServiceOnlyController@index')->name('services');
Route::get('/offices', 'UserController@offices')->name('offices');

Route::post('/register', 'UserController@store')->name('register');
Route::post('/login', 'UserController@login')->name('login');
Route::get('/login',function(){
    return "Un Authorised";
});






//Route::apiResource('/services','ServiceController');

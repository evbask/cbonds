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

// List products
Route::get('products', 'TestController@index');

// List products with specific name
Route::get('products/search/{name}', 'TestController@search');

// Create new product
Route::post('product','TestController@store');

// Update product
Route::put('product','TestController@store');

// Delete product
Route::delete('product/{id}','TestController@destroy');
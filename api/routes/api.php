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
Route::post('register', 'UserController@register');
Route::post('login', 'UserController@login');
Route::get('open', 'DataController@open');

Route::group(['middleware' => ['auth.jwt']], function() {
    Route::get('logout', 'ApiController@logout');
    Route::get('user', 'UserController@getAuthUser');
    Route::get('closed', 'DataController@closed');
    Route::get('departments', 'DepartmentController@index');
    Route::get('departments/{id}', 'DepartmentController@show');
    Route::post('departments', 'DepartmentController@store');
    Route::put('departments/{id}', 'DepartmentController@update');
    Route::delete('departments/{id}', 'DepartmentController@destroy');
});
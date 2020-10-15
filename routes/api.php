<?php
 
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
 
Route::post('register', 'API\UserController@register');
Route::post('login', 'API\UserController@login');
Route::post('activelogin', 'API\UserController@activelogin');


// Route::middleware('auth:api')->group( function () {
//     Route::resource('articles', 'API\ArticleController');
// });


// using permision 
Route::post('update', 'API\ArticleController@update')
->middleware(['auth:api','userarticle']);





Route::post('store', 'API\ArticleController@store')
->middleware(['auth:api']);

Route::post('destroy', 'API\ArticleController@destroy')
->middleware(['auth:api']);

Route::post('show', 'API\ArticleController@show')
->middleware(['auth:api']);

Route::get('index', 'API\ArticleController@index')
->middleware(['auth:api']);

Route::get('search', 'API\ArticleController@search')
->middleware(['auth:api']);
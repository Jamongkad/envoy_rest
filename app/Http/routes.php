<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return 'Hi Guest. ';
});

Route::get('/test', function () {
    return "MATHEW";
});

Route::get('login', 'AuthController@login');
Route::get('test_token', 'AuthController@test_token');
Route::get('refresh_token', 'AuthController@refresh_token');
Route::get('revoke', 'AuthController@revoke');

Route::get('get_products', 'UberController@get_products');
Route::get('get_price_estimates', 'UberController@get_price_estimates');
Route::get('get_time_estimates', 'UberController@get_time_estimates');
Route::get('make_request', 'UberController@make_request');

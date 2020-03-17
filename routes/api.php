<?php

use Illuminate\Http\Request;

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
Route::post('/login', 'Auth\LoginController@login')->name('login');

Route::group(['middleware' => 'auth:api'], function () use ($router) {
    $router->resource('/outlets', 'API\OutletController')->except(['show']);
    $router->resource('/couriers', 'API\UserController')->except(['create', 'show', 'update']);
    $router->post('/couriers/{id}', 'API\UserController@update')->name('couriers.update');
    $router->resource('/product', 'API\ProductController')->except(['create', 'show']);
    $router->get('/product/laundry-type', 'API\ProductController@getLaundryType');
    $router->post('/product/laundry-type', 'API\ProductController@storeLaundryType');



    // setting
    $router->get('roles', 'API\RolePermissionController@getAllRole')->name('roles');
    $router->get('permissions', 'API\RolePermissionController@getAllPermission')->name('permission');
    $router->post('role-permission', 'API\RolePermissionController@getRolePermission')->name('role_permission');
    $router->post('set-role-permission', 'API\RolePermissionController@setRolePermission')->name('set_role_permission');
    $router->post('set-role-user', 'API\RolePermissionController@setRoleUser')->name('user.set_role');
    $router->get('user-authenticated', 'API\UserController@getUserLogin')->name('user.authenticated');
    $router->get('user-lists', 'API\UserController@userLists')->name('user.index');
});

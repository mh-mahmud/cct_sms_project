<?php

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

Auth::routes();

Route::middleware('auth')->get('/', 'DashboardController@index')->name('dashboard');
Route::middleware('auth')->get('/dashboard', 'DashboardController@index')->name('dashboard');
Route::get('/logout','Auth\LoginController@logout');
Route::get('/extlogin','Auth\LoginController@attemptLogin');

// Admin Routes
Route::get('/admin/login', 'Auth\AdminLoginController@showLoginForm')->name('admin.login');
Route::post('/admin/login', 'Auth\AdminLoginController@attemptLogin')->name('admin.login.submit');
Route::get('/admin/logout','Auth\AdminLoginController@logout')->name('admin.logout');

Route::get('/admin/dashboard', 'Admin\AdminDashboardController@index')->name('admin.home');

// Admin Dashboard Api
Route::get('/admin-api/dashboard', 'Admin\AdminDashboardController@getDashboardData');

// Admin Inbound Api
Route::get('/admin-api/inbound-list', 'Admin\AdminInboundController@getList');
Route::get('/admin-api/inbox-list/{from}/{to}', 'Admin\AdminInboxController@getInboxList');
// Admin Outbound Api
Route::get('/admin-api/outbound-list', 'Admin\AdminOutboundController@getList');
// Admin Audit Log Api
Route::get('/admin-api/audit-log-list', 'Admin\AdminAuditLogController@index');

Route::middleware('auth')->get('/users','UsersController@index')->name('users');
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/authCheck/{account_id}/{extn}', 'DashboardController@getAuthCheck');

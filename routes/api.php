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


// get all constant variables
Route::middleware('auth')->get('/get-const-var', 'BaseController@getConstVar');
Route::group(['middleware' => ['auth']], function() {
    Route::resources([
        'users' => 'UsersController',
    ]);
    Route::resources([
        'contacts' => 'ContactController',
    ]);
    Route::resources([
        'templates' => 'TemplateController',
    ]);
    Route::resources([
        'schedules' => 'ScheduleController',
    ]);
    Route::resources([
        'history' => 'HistoryController',
    ]);
    Route::resources([
        'groups' => 'GroupController',
    ]);
    Route::get('/search-contact-list', 'ContactController@getSearchContactList');
    // Compose
    Route::get('/compose', 'ComposeController@index');    
    Route::post('/compose-create', 'ComposeController@saveCompose');
    Route::post('/reply-create', 'ComposeController@saveReply');
    Route::post('/compose-update/{id}', 'ComposeController@updateCompose');
    Route::get('/compose-detail/{schedule_id}', 'ComposeController@getDetail');    
    // List
    Route::get('/group-list', 'GroupController@getList');
    Route::get('/search-list', 'GroupController@getSearchList');
    Route::get('/group-detail/{id}', 'GroupController@getGroupDetail');
    Route::post('/groups-list', 'GroupController@postGroupList');
    Route::post('/add-contact/{id}', 'GroupController@postAddContactToGroup');
    // List contacts
    Route::get('/contact-list', 'ContactController@getContactList');
    Route::post('/contact-list', 'ContactController@postContactList');
    Route::get('/contact-list-modal', 'ContactController@getContactListModal');
    Route::get('/contact-list-modal-filter/{id}', 'ContactController@getContactListModalFilter');
    Route::get('/contact-list-modal-filter-selectall/{id}', 'ContactController@getContactListModalFilterSelectAll');
    Route::get('/contact-group-list/{id}', 'ContactController@getContactListByGroup');
    Route::get('/contact-import', 'ContactController@getContactImport');
    Route::post('/contact-import', 'ContactController@postContactImport');
    Route::post('/contact-import-create', 'ContactController@postContactImportCreate');
    Route::get('/contact-country-list', 'ContactController@getCountries');
    Route::get('/country-phone-code/{country}', 'ContactController@getCountryPhoneCode');
    Route::post('/contact-delete/{group_id}', 'ContactController@postDeleteContactFromGroupSelected');
    Route::get('/contact-delete-selectall/{group_id}', 'ContactController@postDeleteContactFromGroupSelectedAll');
    Route::delete('/contact-delete/{id}/{group_id}', 'ContactController@postDeleteContactFromGroup');
    // Schedule
    Route::get('/schedule-detail/{id}', 'ScheduleController@getScheduleDetail');
    Route::get('/schedule-change-status/{log_time}/{account_id}/{did}/{client_number}/{callid}/{status}', 'ScheduleController@getScheduleChangeStatus');
    // Outbound
    Route::get('/outbound-list', 'OutboundController@getList');
    // Inbound
    Route::get('/inbound-list', 'InboundController@getList');
    Route::get('/inbox-list/{from}/{to}', 'InboxController@getInboxList');
    // Audit Log
    Route::get('/audit-log-list', 'AuditLogController@index');
    // List users
    Route::get('/user-list', 'UsersController@getUsersList');
    Route::get('/password-reset', 'UsersController@getPasswordReset');
    Route::post('/password-reset', 'UsersController@postPasswordReset');
    //Dashboard
    Route::get('/dashboard', 'DashboardController@getDashboardData');
});

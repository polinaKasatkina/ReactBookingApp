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

Route::get('/', function () {
    return view('index');
});

Route::post('/register', 'RegisterController@register');
Route::post('/login', 'LoginController@login');

Route::post('/search', 'SearchController@searchAvailableProperty')->name('search');


Route::get('/get_properties', 'SearchController@index');
Route::post('/get_properties_by_id', 'PropertiesController@getPropertiesById');
Route::post('/add_to_booking', 'BookingController@addBookingCookie');

Route::get('/get_user_data/{id}', 'UserController@getUserData');

Route::get('/booking/place', function() {
    return view('bookings.place');
});


Route::group([
    'prefix' => 'profile',
    'middleware' => 'auth',
], function () {

    Route::get('{profile}/edit/password', [
        'as' => 'profile.edit.password',
        'uses' => 'ProfileController@showPasswordForm'
    ]);
    Route::get('{profile}/edit/notice', [
        'as' => 'profile.edit.notice',
        'uses' => 'ProfileController@showNoticeForm'
    ]);
    Route::get('{profile}/edit/account', [
        'as' => 'profile.edit.account',
        'uses' => 'ProfileController@showAccountForm'
    ]);
    Route::patch('{profile}/edit/password', 'ProfileController@updatePassword');
    Route::patch('{profile}/edit/notice', 'ProfileController@updateNotifications');
    Route::patch('{profile}/edit/account', 'ProfileController@softDelete');

    Route::get('{profile}/bookings', 'BookingController@bookingsList');
    Route::get('{profile}/bookings/{booking}', 'BookingController@info');

    Route::get('{profile}/bookings/{booking}/invoice', 'BookingController@invoice');

});

Route::resource('profile', 'ProfileController', ['except' => ['create']])->middleware('auth');


Route::group([
    'prefix' => 'admin',
    'namespace' => 'Admin',
   // 'middleware' => 'is_admin',
], function () {
    Route::get('/', 'HomeController@index');
    Route::resource('bookings', 'BookingsController', ['except' => ['create']]);
    Route::resource('users', 'UsersController', ['except' => ['create']]);
    Route::get('properties', 'PropertyController@index');
    Route::get('properties/get', 'PropertyController@parseProperties');
    Route::get('emails', 'EmailsController@index');
    Route::get('emails/{email}', 'EmailsController@show');
    Route::get('emails/{email}/send', 'EmailsController@send');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

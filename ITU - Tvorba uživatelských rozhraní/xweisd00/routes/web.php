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

Route::get('/', 'PagesController@index');
Route::get('/about', 'PagesController@about');

Route::get('/payviolation', function() {  return redirect()->back(); });
Route::post('/payviolation', 'PagesController@payviolation');

Route::get('/buyvignette', function() {  return redirect()->back(); });
Route::post('/buyvignette', 'PagesController@buyvignette');

Route::post('/payviolation/payment', 'PagesController@payForViolations');
Route::post('/buyvignette/payment', 'PagesController@payForVignettes');

Route::get('/newregistration', function() {  return redirect()->back(); });
Route::post('/newregistration', 'HomeController@newregistration');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/clerk', 'ClerkController@index')->name('clerk');

Route::get('/addviolation', function(){ return redirect()->back(); });
Route::post('/addviolation', 'ClerkController@addViolation');

Route::prefix('clerk')->group(function() {
    Route::get('/login', 'Auth\ClerkLoginController@showLoginForm')->name('clerk.login');
    Route::post('/login', 'Auth\ClerkLoginController@login')->name('clerk.login.submit');
    Route::get('/', 'ClerkController@index')->name('clerk.dashboard');
});


Route::get('/search',function(){ return redirect()->back(); });
Route::post('/clerk','ClerkController@searchFunction');

Route::get('/clerk/accept', function(){ return redirect()->back(); });
Route::post('/clerk/accept','ClerkController@acceptRequest');

Route::get('/clerk/reject', function(){ return redirect()->back(); });
Route::post('/clerk/reject','ClerkController@rejectRequest');

Route::get('/clerk/delete', function(){ return redirect()->back(); });
Route::post('/clerk/delete','ClerkController@deleteRequest');
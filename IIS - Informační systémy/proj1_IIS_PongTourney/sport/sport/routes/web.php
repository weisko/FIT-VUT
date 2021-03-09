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

Route::get('/', 'PagesController@home');
Route::get('/', 'TournamentsController@index_home');
Route::get('/dashboard', 'PagesController@dashboard');
Route::get('/user', 'PagesController@user');
Route::get('/team', 'PagesController@team');
Route::get('/tournament', 'PagesController@tournament');
Route::get('/logins', 'PagesController@login');
// Route::get('/userEdit', 'PagesController@edit');

Auth::routes();
Route::get('/dashboard', 'DashboardController@index');
Route::resource('team', 'TeamController');
Route::resource('tournament', 'TournamentsController');

Route::resource('user', 'UsersController');
// Route::get('/userEdit/{id}/','UsersController@edit');
 Route::post('/user/{id}/edit','UsersController@update');

Route::resource('match', 'MatchesController');
Route::post('/match/create/{id}/','MatchesController@store');
Route::post('/match/{id}/edit/','MatchesController@update');

//Route::get('/tournament/{id}', 'MatchesController@index');
// Route::get('/match/create/{id}', 'MatchesController@create');

// Route::post('/tournament/{id}/', 'MatchesController@store');

Route::get('/registeredToTeam', 'PagesController@registeredToMyTeam');
Route::get('/registeredToTour', 'PagesController@registeredToMyTournament');

Route::post('/registerToSingleTournament/{id}', 'PagesController@registerToSingleTournament');
Route::post('/registerToDoubleTournament/{id}', 'PagesController@registerToDoubleTournament');
Route::post('/registerOnTeam/{id}', 'PagesController@registerToTeam');

Route::get('/player', 'PagesController@writePlayers');
Route::post('/playerStats/{id}/', 'PagesController@writePlayerStats');

Route::post('/registeredToTour/{t_id}/acceptTourPlayer/{p_id}', 'PagesController@acceptTourPlayer');
Route::post('/registeredToTour/{t_id}/acceptRefferee/{p_id}', 'PagesController@acceptRefferee');
Route::post('/registeredToTour/{t_id}/declineTourPlayer/{p_id}', 'PagesController@declineTourPlayer');

Route::post('/registeredToTeam/{t_id}/acceptTeamPlayer/{p_id}/{player_name}', 'PagesController@acceptTeamPlayer');

Route::post('/registeredToTeam/{t_id}/declineTeamPlayer/{p_id}', 'PagesController@declineTeamPlayer');
Route::post('/registerRefferee/{id}', 'PagesController@registerRefferee');

Route::get('/createMatch/{id}', 'pagesController@createMatch')->name('create.match');

Route::get('/changePassword', 'DashboardController@showChangePasswordForm');
Route::post('/changePassword','DashboardController@changePassword')->name('changePassword');
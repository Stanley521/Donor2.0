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

error_reporting(E_ALL);
ini_set('display_errors', 'on');

Auth::routes();

//Route::get('/home', 'HomeController@index');
Route::get('/', 'HomeController@index')->name('home');

Route::get('user/profile', 'UserController@profile')->name('user.profile');
Route::get('user/upload', 'UserController@form')->name('user.form');
Route::get('user/edit', 'UserController@editing')->name('user.editing');
Route::get('user/index', 'UserController@index')->name('user.index');

Route::post('user/search', 'UserController@search')->name('user.search');
Route::post('user/edit', 'UserController@edit')->name('user.edit');
Route::post('user/upload', 'UserController@upload')->name('user.upload');
Route::post('user/file/upload', 'UserController@fileupload')->name('user.file.upload');
Route::post('user/delete', 'UserController@delete')->name('user.delete');



Route::get('file/upload', 'FileController@form')->name('file.form');
Route::get('file/index', 'FileController@index')->name('file.index');
Route::post('file/upload', 'FileController@upload')->name('file.upload');

Route::get('donor/index', 'DonorController@index')->name('donor.index');
Route::get('donor/find', 'DonorController@find')->name('donor.find');
Route::get('donor/{id}', 'DonorController@personal')->name('donor.personal');
Route::post('donor', 'DonorController@donor')->name('donor.donor');


Route::post('conversation/request', 'ConversationController@request')->name('conversation.request');

Route::get('/chats', 'ConversationController@index')->name('chat.index');
Route::get('/chat/{friend_id}', 'ChatsController@index')->name('chat.chat');
Route::get('/messages/{friend_id}', 'ChatsController@fetchMessages')->name('chat.fetch');
Route::post('/messages', 'ChatsController@sendMessage')->name('chat.send');
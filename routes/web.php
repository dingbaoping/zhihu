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

Route::get('/','QuestionsController@index');

Auth::routes();

Route::get('home', 'HomeController@index');
Route::get('email/verify/{token}', ['as'=>'email.verify','user' => 'EmailController@verify']);

Route::resource('questions', 'QuestionsController',['names'=>[
	'create' => 'questions.create',
	'show' => 'questions.show',
]]);

Route::post('questions/{question}/answer', 'AnswersController@store'); //回答

Route::get('question/{question}/follow', 'QuestionFollowController@follow'); //关注

Route::get('/user/followers/{id}','FollowersController@follow'); //用户之间关注

Route::get('notifications','NotificationsController@index');  //消息通知

Route::post('/answer/{id}/votes/users','VotesController@users');  //点赞
Route::post('/answer/vote','VotesController@vote');  //对点赞的操作

Route::post('/message/store','MessageController@store');  //发送私信
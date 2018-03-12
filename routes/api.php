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

Route::middleware('api')->get('/topics', function (Request $request) {
	$topics= \App\Topic::select(['id','name'])->where('name','like','%'.$request->query('q').'%')->get();
    return $topics;
});


Route::middleware('api')->post('/question/follower', function (Request $request) {
	$user = Auth::guard('api')->user();
	$followed=\App\Follow::where('question_id',$request->get('question'))
						->where('user_id',$user->id)
						->count();
	if($followed){
		return response()->json(['followed' => true]);
	}
    return response()->json(['followed' => false]);
});

Route::middleware('api')->post('/question/follow', function (Request $request) {
	$user = Auth::guard('api')->user();
	$followed=\App\Follow::where('question_id',$request->get('question'))
						->where('user_id',$user->id)
						->first();
	if($followed !== null){
		$followed->delete();
		return response()->json(['followed' => false]);
	}

	\App\Follow::create([
		'question_id',$request->get('question'),
		'user_id',$request->get('user'),
	]);
    return response()->json(['followed' => true]);
});


Route::get('/user/followers/{id}','FollowerController@index');
Route::post('/user/follow','FollowerController@follow');
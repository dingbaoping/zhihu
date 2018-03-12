<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth;
use App\Notifications\NewUserFollowNotification;

class FollowersController extends Controller
{
    // public function index($id)
    // {
    //     $user = User::find($id);
    //     $followers=$user->follower()->pluck('follower_id')->toArray();
    //     if(in_array(Auth::guard('api')->user()->id, $followers)){
    //         return response()->json(['followed' => true]);
    //     }
    //     return response()->json(['followed' => false]);
    // }

    // public function follow()
    // {
    // 	$userToFollow = User::find(request('user'));
    // 	$followed = Auth::guard('api')->followThisUser($userToFollow->id);
    //     if(count($followed['attached']) > 0){
    //         $userToFollow -> increment('followers_count');
    //         return response()->json(['followed' => true]);
    //     }
    //     $userToFollow -> decrement('followers_count');
    //     return response()->json(['followed' => false]);
    // }

    // public function index($id)
    // {
    //     $user = User::find($id);
    //     $followers=$user->follower()->pluck('follower_id')->toArray();
    //     if(in_array(Auth::guard('api')->user()->id, $followers)){
    //         return response()->json(['followed' => true]);
    //     }
    //     return response()->json(['followed' => false]);
    // }

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function follow($question)
    {
        $userToFollow = User::find($question);
        $followed = Auth::user()->followThisUser($userToFollow->id);



       if(count($followed['attached']) > 0){
            $userToFollow -> notify(new NewUserFollowNotification());
            $userToFollow -> increment('followers_count');

        }else{
            $userToFollow -> decrement('followers_count');
        }
        return back();
    }
}

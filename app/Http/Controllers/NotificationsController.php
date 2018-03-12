<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class NotificationsController extends Controller
{
    public function index()
    {
    	$user = Auth::user();
 		// dd($user->notifications);
    	return view('notifications/index',compact('user'));
    }
}

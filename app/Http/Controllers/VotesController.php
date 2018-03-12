<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class VotesController extends Controller
{
    public function users($id)
    {
    	$user= Auth::user();
    	if($user->voteFor($id)){
    		
    	}
    }

    public function vote()
    {

    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Message;
use Auth;

class MessageController extends Controller
{
    public function store(Request $request){

    	$rules=[
            'body' => 'required',
        ];

        $messages = [
            'body.required' => '内容不能为空！',
        ];

        $this->validate($request,$rules,$messages);

        $data=[
            'to_user_id'=>$request->get('to_user_id'),
            'body'=>$request->get('body'),
            'form_user_id'=>Auth::id(),
        ];
        $message=Message::create($data);

        if($message){
        	return response()->json(['status' => true]);
        }

       return response()->json(['status' => false]);
    }
}

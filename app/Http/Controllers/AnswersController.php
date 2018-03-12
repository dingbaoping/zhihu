<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Answer;
use Auth;

class AnswersController extends Controller
{
    public function index()
    {

    }

    public function store(Request $request,$question)
    {

        $rules=[
            'body' => 'required',
        ];

        $messages = [
            'body.required' => '内容不能为空！',
        ];

        $this->validate($request,$rules,$messages);

        $data=[
        	'question_id'=>$question,
        	'user_id'=>Auth::id(),
            'body'=>$request->get('body'),           
        ];

        $question=Answer::create($data);
        $question->question()->increment('answers_count');

        return back();
    }
}

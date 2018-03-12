<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Topic;
use App\Question;
use App\User;

class QuestionsController extends Controller
{   

    public function __construct()
    {

        $this->middleware('auth')->except(['index','show']);
    }


    public function index()
    {
        $question=Question::published()->latest('updated_at')->with('user')->get();
        // dd($question);
        return view('question/index',compact('question'));
    }

    public function create()
    {
        //
        return view('question/create');
    }

    // StoreQuestionRequest
    public function store(Request $request)
    {   
        $topics=$this->normalizeTopic($request->get('topics'));
        
        $rules=[
            'title' => 'required|min:2|max:255',
            'body' => 'required',
        ];

        $messages = [
            'title.required' => '标题不能为空！',
            'title.min' => '标题不能少于2个字符！',
            'title.max' => '标题不能大于255个字符！',
            'body.required' => '内容不能为空！',
        ];

        $this->validate($request,$rules,$messages);

        $data=[
            'title'=>$request->get('title'),
            'body'=>$request->get('body'),
            'user_id'=>Auth::id(),
        ];
        $question=Question::create($data);
        $question->topics()->attach($topics);

        return redirect()->route('questions.show',[$question->id]);
    }

    public function show($id)
    {
        
        $question=Question::where('id',$id)->with(['topics','answers'])->first();

        return view('question/show',compact('question'));
       
    }

    public function edit($id)
    {
        $question=Question::where('id',$id)->with('topics')->first();
        if(Auth::user()->owns($question)){
            return view('question/edit',compact('question'));
        }
        return back();
    }

    public function update(Request $request,$id)
    {

         $question=Question::where('id',$id)->with('topics')->first();


        $topics=$this->normalizeTopic($request->get('topics'));

         $rules=[
            'title' => 'required|min:2|max:255',
            'body' => 'required',
        ];

        $messages = [
            'title.required' => '标题不能为空！',
            'title.min' => '标题不能少于2个字符！',
            'title.max' => '标题不能大于255个字符！',
            'body.required' => '内容不能为空！',
        ];

        $question->update([
            'title' => $request->get('title'),
            'body' => $request->get('body'),
        ]);

        $question->topics()->sync($topics);

        return redirect()->route('questions.show',[$question->id]);
    }

    public function destroy($id)
    {
        $question=Question::where('id',$id)->with('topics')->first();
        if(Auth::user()->owns($question)){
            $question->delete();
            return redirect('/');
        }
        abort(403,'没有操作权限');
    }

    public function normalizeTopic(array $topics){
        return collect($topics)->map(function($topic){
            if(is_numeric($topic)){
                Topic::find($topic)->increment('questions_count');
                return (int)$topic;
            }
            $newTopic=Topic::create(['name'=>$topic,'questions_count'=>1]);
            return $newTopic->id;
        })->toArray();
    }
}

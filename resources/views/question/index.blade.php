@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            
                            
            @foreach($question as $v)
                <div class="media">
                    <div class="media-left">
                        <a href="">
                            <img class="media-object" src="{{$v->user->avatar}}" alt="{{$v->user->name}}" width="48px">
                        </a>
                    </div>
                    <div class="media-body">
                        <h4 class="media-heading">
                            <a href="/questions/{{$v->id}}">{{$v->title}}</a>
                        </h4>
                    </div>
                </div>
            @endforeach   



        </div>
    </div>
</div>

@endsection

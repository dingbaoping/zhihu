@extends('layouts.app')

@section('content')
@include('vendor.ueditor.assets')

<div class="container">
    <div class="row">
        <div class="col-md-9">
            <!-- 文章模块 -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{$question->title}}

                    @if(Auth::check() && Auth::user()->owns($question))
                        <span class="edit" style="margin-left: 20px;"><a href="/questions/{{$question->id}}/edit">编辑</a></span>
                        <span class="delete">
                            <form action="/questions/{{$question->id}}" method="POST">
                                 {{ method_field('DELETE') }}
                                 {{ csrf_field() }}
                                 <button type="submit" class="botton">删除</button>
                             </form>
                        </span>
                    @endif
          
                    @foreach($question->topics as $topic)
                        <a class="topic pull-right" href="/topic/{{$topic->id}}">{{$topic->name}}</a>
                    @endforeach                   
                </div>

                <div class="panel-body">
                    {!! $question->body !!}
                </div>              
            </div>
            <!-- 评论模块 -->
             <div class="panel panel-default">
                <div class="panel-heading">
                    {{$question->answers_count}} 个答案                 
                </div>

                <div class="panel-body">

                @foreach($question->answers as $v)
                    <div class="media">
                        <div class="media-left">
                            <a href="">
                                <img class="media-object" src="{{$v->user->avatar}}" alt="{{$v->user->name}}" width="36">
                            </a>
                        </div>
                        <div class="media-body">
                            <h4 class="media-heading">
                                <a href="/user/{{$v->user->id}}">{{$v->user->name}}</a>
                            </h4>
                            {!! $v->body !!}
                        </div>
                    </div>
                @endforeach
                
                @if(Auth::check())
                    <form action="/questions/{{$question->id}}/answer" method="POST">
                        {{ csrf_field() }}
                       <!-- 编辑器容器 -->
                       <div class="form-group">
                            <script id="container" name="body" type="text/plain">
                                {!! old('body') !!}
                            </script>
                        </div>

                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif


                        <button type="submit" class="btn btn-success pull-right">提交答案</button>
                    </form>
                @else
                    <a href="{{url('login')}}" class="btn btn-success btn-block">登录提交答案</a>
                @endif
                </div>

               
            </div>
        </div>
        <div class="col-md-3">
            <!-- 关注模块 -->
            <div class="panel panel-default">
                <div class="panel-heading question-follow">
                    <h2>{{ $question->followers_count}}</h2>
                    <span>关注者</span>
                </div>
                <div class="panel-body">
                    <div style="display: flex;justify-content: space-between;">
                        <a href="/question/{{$question->id}}/follow"
                         class="btn btn-default {{Auth::user()->followed($question->id) ? 'btn-primary' : ''}}">
                            {{Auth::user()->followed($question->id) ? '已关注' : '关注问题'}}
                         </a>
                        <!--  <question-follow-button question="{{$question->id}}" user="{{Auth::id()}}"></question-follow-button> -->
                        <a href="#editor" class="btn btn-primary">撰写答案</a>
                    </div>
                </div>
            </div>
            <!-- 结束关注模块 -->
            <!-- 作者模块 -->
             <div class="panel panel-default">
                <div class="panel-heading question-follow">
                    <h5>关于作者</h5>
                </div>
                <div class="panel-body">
                    <div class="media">
                        <div class="media-left">
                            <a href="">
                                <img src="{{$question->user->avatar}}" alt="{{$question->user->name}}" width="60">
                            </a>
                        </div>
                        <div class="media-body">
                            <div class="media-heading">
                                <a href="">{{$question->user->name}}</a>
                            </div>
                        </div>
                        <div class="user-statics">
                            <div class="statics-item text-center">
                                <div class="statics-text">问题</div>
                                <div class="statics-count">{{$question->user->questions_count}}</div>
                            </div>
                            <div class="statics-item text-center">
                                <div class="statics-text">回答</div>
                                <div class="statics-count">{{$question->user->answers_count}}</div>
                            </div>
                            <div class="statics-item text-center">
                                <div class="statics-text">关注者</div>
                                <div class="statics-count">{{$question->user->followers_count}}</div>
                            </div>
                        </div>
                    </div>
                    <div style="display: flex;justify-content: space-between;">
                        <a href="/user/followers/{{$question->user_id}}"
                         class="btn btn-default {{Auth::user()->followedUser($question->user_id) ? 'btn-success' : ''}}">
                            {{Auth::user()->followedUser($question->user_id) ? '已关注' : '关注他' }}
                         </a>
                         <!-- <user-follow-button user="{{$question->user_id}}"></user-follow-button> -->
                        <a href="#editor" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" data-id="{{$question->user_id}}" id="sendMessageShow">发送私信</a>
                    </div> 
                </div>
            </div>
            <!--结束作者模块 -->
        </div>
    </div>
    

   <!--  模态框 -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="exampleModalLabel">发送私信</h4>
          </div>
          <div class="modal-body">

                <textarea class="form-control" id="body" name="body" rows="5"></textarea>

                <div class="alert alert-success" style="display: none;">
                    <strong>私信发送成功</strong>
                </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
            <button type="button" class="btn btn-primary" id="sendMessage">发送私信</button>
          </div>
        </div>
      </div>
    </div>
    <!-- 模态框结束 -->
    
</div>

@endsection

@section('js')
<!-- 实例化编辑器 -->
<script type="text/javascript">
    //编辑器
     var ue = UE.getEditor('container');
    ue.ready(function() {
        ue.execCommand('serverparam', '_token', '{{ csrf_token() }}'); // 设置 CSRF token.
    });

    $.ajaxSetup({
    headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $("#sendMessage").click(function(){
        $.ajax({
            type: "POST",
            url: "/message/store",
            data: {
                to_user_id:$("#sendMessageShow").attr('data-id'),
                body:$("#body").val()
            },
            dataType: "json",
            success: function(msg){
                console.log(msg);
                if(msg.status){
                    $(".modal-body").find("#body").hide();
                    $(".modal-body").find(".alert-success").show();
                    setTimeout(function(){
                        $('#exampleModal').modal('hide');
                    },1000);
                    
                }else{
                    $(".modal-body").find("#body").show();
                    $(".modal-body").find(".alert-success").hide();
                }
            }
        });
    })

</script>
@endsection

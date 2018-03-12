@extends('layouts.app')

@section('content')
@include('vendor.ueditor.assets')

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">发布问题</div>

                <div class="panel-body">
                    <form action="/questions/{{$question->id}}" method="POST">
                        {{ method_field('PATCH') }}
                        {!! csrf_field() !!}
                        <div class="form-group">
                            <label for="title">标题</label>
                            <input type="text" id="title" name="title" class="form-control" placeholder="标题" value="{{$question->title}}">
                        </div>
                        
                        <div class="form-group has-success has-feedback">
                            <label for="title">话题</label>
                            <select class="js-example-basic-multiple js-data-example-ajax form-control" name="topics[]" multiple="multiple" placeholder="添加话题">
                                @foreach($question->topics as $topic)
                                    <option value="{{$topic->id}}" selected="selected">{{$topic->name}}</option>
                                @endforeach
                            </select>
                             <!-- <span class="glyphicon glyphicon-search form-control-feedback" aria-hidden="true"></span> -->
                        </div>

                       <!-- 编辑器容器 -->
                       <div class="form-group">
                            <label for="container">内容</label>
                            <script id="container" name="body" type="text/plain">
                                {!! $question->body !!}
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


                        <button type="submit" class="btn btn-success pull-right">发布问题</button>
                    </form>
                </div>
            </div>
            

        </div>

    </div>


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

        //模糊筛选
        $(".js-example-basic-multiple").select2({
          ajax: {
            
            url: "/api/topics",
            dataType: 'json',
            delay: 250,
            data: function (params) {
              return {
                q: params.term,
              };
            },
            processResults: function (data, params) {

              return {
                results: data
              };
            },
            cache: true
          },
          tags: true,
          placeholder: '选择相关话题',
          // minimumInputLength: 2,
          templateResult: formatRepo,
          templateSelection: formatRepoSelection
        });

        function formatRepo (repo) {
            
          return "<div class='select2-result-repository clearfix'>" +
            "<div class='select2-result-repository__meta'>" +
              "<div class='select2-result-repository__title'>" + repo.name ? repo.name :"Laravel" + "</div></div></div>";
        }

        function formatRepoSelection (repo) {
          return repo.name || repo.text;
        }

</script>

@endsection

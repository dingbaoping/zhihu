<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('answers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
            $table->integer('question_id')->unsigned()->index();
            $table->text('body');
            $table->integer('votes_count')->default(0); //点赞总数
            $table->integer('comments_count')->default(0); //评论数
            $table->string('is_hidden',8)->default('F');
            $table->string('close_comment',8)->default('F');  //写文章的作者是否关闭评论
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('answers');
    }
}

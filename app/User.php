<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Naux\Mail\SendCloudTemplate;
use Mail;
use Illuminate\Database\Eloquent\Model;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','avatar','confirmation_token','api_token',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function owns(Model $model)
    {
        return $this->id == $model->user_id;
    }
    // 关注问题
    public function follows()
    {
        return $this->belongsToMany(Question::class,'user_question')->withTimestamps();
    }
    public function followsThis($question)
    {
        return $this->follows()->toggle($question);//切换关联
    }
    public function followed($question)
    {
        return !!$this->follows()->where('question_id',$question)->count();
    }

    // 登录用户 与 文章用户 之间关注
    public function followers()
    {
        return $this->belongsToMany(self::class,'followers','follower_id','followed_id')->withTimestamps();
    }
    public function followerUser()
    {
        return $this->belongsToMany(self::class,'followers','followed_id','follower_id')->withTimestamps();
    }

    public function followThisUser($user)
    {
        return $this->followerUser()->toggle($user);
    }
     public function followedUser($user)
    {
       
        return !!$this->followerUser()->where('follower_id',$user)->count();
    }

    //点赞 用户与问题答案之间多对多的关系
    public function votes()
    {
       return $this->belongsToMany(Answer::class,'votes')->withTimestamps();
    }
    public function voteFor($answer)
    {
        return $this->votes()->toggle($answer);
    }

    //发私信
    public function messages()
    {
       return $this->hasMany(Messages::class,'to_user_id');
    }

    public function sendPasswordResetNotification($token){

        // 模板变量
        $bind_data = [
            'url' => url('password/reset', $token)
        ];
        $template = new SendCloudTemplate('zhihu_test_template', $bind_data);

        Mail::raw($template, function ($message){
            $message->from('1692073717@qq.com', 'Laravel');
            $message->to($this->email);
        });
    }


}

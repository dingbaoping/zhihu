<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
	protected $table= 'messages';
    protected $guarded = [];

    public function formUser(){
    	return $this->belongsTo(User::class,'form_user_id');
    }

    public function toUser(){
    	return $this->belongsTo(User::class,'to_user_id');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Playlist extends Model
{
    protected $guarded = array('id');

    public function User()
    {
        return $this->belongsTo('App\User');
    }

    public function Comment()
    {
        return $this->hasMany('App\Comment');
    }
}

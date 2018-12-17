<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class UsersHistory extends Model
{
    //

    protected $fillable = [
        'user_id', 'user_name','description','ip','action','status','created_at'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class DocumentAutor extends Model
{
    //

    protected $fillable = [
        'name', 'email'
    ];

    public function document()
    {
        return $this->hasMany('App\Document','author_id','id');
    }
}

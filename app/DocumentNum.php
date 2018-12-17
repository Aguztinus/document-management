<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class DocumentNum extends Model
{
    //

    protected $fillable = [
        'number', 'name', 'used'
    ];

    public function document()
    {
        return $this->hasOne('App\Document','document_num_id','id');
    }
}

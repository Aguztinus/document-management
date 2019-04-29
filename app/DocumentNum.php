<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class DocumentNum extends Model
{
    //

    protected $fillable = [
        'number', 'name', 'used','document_type_id','user_id'
    ];

    public function document()
    {
        return $this->hasOne('App\Document','document_num_id','id');
    }

    public function documenttype()
    {
        return $this->belongsTo('App\DocumentType','document_type_id','id');
    }
}

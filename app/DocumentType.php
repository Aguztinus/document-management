<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocumentType extends Model
{
    //
    protected $fillable = [
        'name', 'description'
    ];

    public function document()
    {
        return $this->hasMany('App\Document','document_type_id','id');
    }

    public function documentNum()
    {
        return $this->hasMany('App\DocumentNum','document_type_id','id');
    }
}

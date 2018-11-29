<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    //
    

    public function documenttype()
    {
        return $this->belongsTo('App\DocumentType');
    }

    public function user()
    {
        return $this->belongsToMany(User::class);
    }

    public function reference(){
        return $this->belongsToMany('App\Document', 'document_reference', 'document_id', 'document_id_detail');
    }

    public function referenceBy(){
        return $this->belongsToMany('App\Document', 'document_reference', 'document_id_detail', 'document_id');
    }

    public function history(){
        return $this->belongsToMany('App\Document', 'document_history', 'document_id', 'document_id_his');
    }

}

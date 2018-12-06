<?php

namespace App;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    //
    protected $appends = ['is_mine'];

    protected $fillable = [
        'name', 'description', 'file_ext', 'url', 'size','size_int','slug','status','owner_id','document_type_id','unit_id'
    ];

    public function documenttype()
    {
        return $this->belongsTo('App\DocumentType','document_type_id','id');
    }

    public function userowner()
    {
        return $this->belongsTo('App\User','owner_id','id');
    }

    public function getIsMineAttribute()
    {
        $value = Auth::id();
        $cek = false;
        if (ucfirst($this->owner_id) == $value) {
            $cek = true;
        }
        return  $cek;
    }
    
    public function unit()
    {
        return $this->belongsTo('App\Units');
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

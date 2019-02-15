<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;

class Units extends Model
{
    use HasApiTokens;

    protected $fillable = [
        'name', 'description'
    ];

    public function user()
    {
        return $this->hasMany('App\User','unit_id','id');
    }

    public function document()
    {
        return $this->hasMany('App\Document','unit_id','id');
    }

    public function users()
    {
        return $this->belongsToMany('App\User', 'users_units', 'unit_id','user_id');
    }
}

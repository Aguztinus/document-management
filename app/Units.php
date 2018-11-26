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
}

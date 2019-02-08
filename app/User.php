<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'bio', 'photo','type','unit_id','active'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function unit()
    {
        return $this->belongsTo('App\Units');
    }

    public function owndocument()
    {
        return $this->hasMany('App\Document','owner_id','id');
    }

    public function document()
    {
        return $this->belongsToMany(Document::class);
    }

    public function units()
    {
        return $this->belongsToMany('App\Units', 'users_units', 'user_id', 'unit_id');
    }

    public function history()
    {
        return $this->hasMany('App\UsersHistory','user_id','id');
    }
}

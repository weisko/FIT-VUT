<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    /**
     * assign table to model.
     *
     * @var table
     */
    protected $table = 'vehicles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'stk', 'ek',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'registered', 'registration number', 'plate', 
    ];

    public function vignettes(){
        return $this->hasMany('App\Vignette');
    }
    
    public function violations(){
        return $this->hasMany('App\Violation');
    }
}

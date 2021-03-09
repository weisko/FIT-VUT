<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vignette extends Model
{
    /**
     * assign table to model.
     *
     * @var table
     */
    protected $table = 'vignettes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'valid_since', 'valid_until',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
         
    ];
}

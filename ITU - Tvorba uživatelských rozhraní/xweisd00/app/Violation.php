<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Violation extends Model
{
    /**
     * assign table to model.
     *
     * @var table
     */
    protected $table = 'violations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'happened_on', 'happened_at', 'vehicle_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
         
    ];

    
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    // Table Name
    protected $table = 'tournaments';
    // Primary key
    public $primaryKey = 'id';
    // Timestapms
    public $timestamps = true;
}


<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Match extends Model
{
    // Table Name
    protected $table = 'matches';
    // PK
    public $primaryKey = 'id';
    // Timestamps
    public $timestamps = true;
}

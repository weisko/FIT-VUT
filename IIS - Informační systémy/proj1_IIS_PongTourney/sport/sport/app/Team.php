<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    // Table Name
    protected $table = 'teams';
    // PK
    public $primaryKey = 'id';
    // Timestamps
    public $timestamps = true;
}

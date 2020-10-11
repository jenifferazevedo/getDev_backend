<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Internship_type extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name'
    ];
}

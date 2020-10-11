<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'user_id', 'name', 'logo', 'description', 'site', 'email', 'linkedin', 'morada', 'location_id', 'status_id'
    ];

    protected $hidden = [
        'user_id', 'location_id', 'status_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }
}

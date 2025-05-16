<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsersLocation extends Model
{
    protected $guarded = ['id'];

    /**
     * Get the user that owns the gps location.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}

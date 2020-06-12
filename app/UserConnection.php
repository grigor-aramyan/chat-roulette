<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserConnection extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'connected_from', 'connected_to'
    ];
}

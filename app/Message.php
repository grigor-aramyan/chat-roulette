<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Message extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'addressed_to', 'content'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

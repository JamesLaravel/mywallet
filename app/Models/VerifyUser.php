<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VerifyUser extends Model
{
    protected $fillable = ['user_id', 'token', 'created_at', 'updated_at'];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}

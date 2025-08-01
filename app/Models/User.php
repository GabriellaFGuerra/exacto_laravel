<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $primaryKey = 'user_id';

    protected $fillable = [
        'name', 'email', 'login', 'password', 'user_type', 'status', 'notification',
        'address', 'number', 'complement', 'neighborhood', 'municipality_id', 'zip_code',
        'created_at', 'photo', 'deleted'
    ];

    public function municipality()
    {
        return $this->belongsTo(Municipality::class, 'municipality_id');
    }
}
<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    protected $table = 'users';

    protected $fillable = [
        'name', 'email', 'password','role','collector_unique_id','mobile_number'
    ];

    protected $hidden = [
        'password',
    ];
}
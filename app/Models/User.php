<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class User extends Authenticatable
{
    protected $table = 'workers';

    use Notifiable;

    protected $fillable = [
        'employee_id', 'role', 'password', 
    ];

    protected $hidden = [
        'password',
    ];

    

}
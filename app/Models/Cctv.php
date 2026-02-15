<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Cctv extends Model
{
    protected $fillable = 
    [
        'name',
        'location',
        'source',
        'type',
        'online',
        'notes',
    ];
}

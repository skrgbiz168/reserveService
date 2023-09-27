<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserves extends Model
{
    use HasFactory;

    protected $fillable = [
        'users_id',
        'status',
        'start_at',
        'finish_at'
    ];
}

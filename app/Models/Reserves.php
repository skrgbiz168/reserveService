<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserves extends Model
{
    use HasFactory;

    protected $fillable = [
        'users_id',
        'status',
        'refund_id',
        'amount',
        'start_at',
        'finish_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}

<?php

namespace App\Models;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserves extends Model
{
    use HasFactory;

    protected $fillable = [
        'users_id',
        'status',
        'charge_id',
        'amount',
        'start_at',
        'finish_at',
        'deleted_at',
        'refund_id',
    ];
    protected $dates = [
        'deleted_at',
        'published_at',
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function scopeSeachAdminList($query, $user_id, $user_name, $type)
    {
        if (!is_null($user_id) && $user_id != "") {
            $query->where('users_id', $user_id);
        }

        if (!is_null($user_name) && $user_name != "") {
            $query->whereHas('user', function ($query) use ($user_name) {
                $query->where('name', "LIKE", "%".$user_name."%");
            });
        }

        $now = Carbon::now();
        if ($type == 0) {
            return $query->where('start_at', '>', $now);
        } else {
            return $query->where('start_at', '<', $now);
        }
    }
}

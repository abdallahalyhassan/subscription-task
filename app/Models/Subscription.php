<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'user_id', 'package_id', 'paid_amount', 'screenshot','expire_at','status'
    ];

    protected $casts = [
        'exipire_at'   => 'datetime',
    ];

    

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}

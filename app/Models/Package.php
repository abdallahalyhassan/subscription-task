<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    /** @use HasFactory<\Database\Factories\PackageFactory> */
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'duration',
        'percent',
        'status',
        'expire_at',
    ];

    protected $casts = [
        'status' => 'boolean',
        'expire_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];


    // protected static function booted()
    // {
    //     static::creating(function ($package) {
    //         if ($package->duration) {
    //             $package->expire_at = now()->addMonths($package->duration);
    //         }
    //     });
    // }


    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function users()
    {
        
        return $this->belongsToMany(User::class, 'subscriptions')
            ->withPivot(['payment_screenshot', 'start_at', 'end_at', 'status'])
            ->withTimestamps();
    }

}

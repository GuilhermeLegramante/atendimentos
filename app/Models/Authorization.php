<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Authorization extends Model
{
    protected $fillable = [
        'user_id',
        'patient_id',
        'partner_id',
        'observations',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'patient_id');
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'partner_id');
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'authorization_service')
            ->withPivot('status')
            ->withTimestamps();
    }

    protected static function booted()
    {
        static::creating(function ($authorization) {
            if (! $authorization->user_id) {
                $authorization->user_id = auth()->id();
            }
        });
    }
}

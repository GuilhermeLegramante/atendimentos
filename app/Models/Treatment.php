<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Treatment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'patient_id',
        'partner_id',
        'date',
        'receipt',
        'request',
        'report',
        'ok',
        'ok_note',
    ];

    protected $casts = [
        'ok' => 'boolean',
    ];

    public function providedServices(): HasMany
    {
        return $this->hasMany(ProvidedService::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'patient_id');
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'partner_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

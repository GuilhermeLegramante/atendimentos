<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Treatment extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'patient_id',
        'partner_id',
        'reviewed',
        'value',
        'quantity',
        'date',
        'receipt',
    ];

    protected $casts = [
        'reviewed' => 'boolean',
        'value' => 'double',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'patient_id');
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'partner_id');
    }
}

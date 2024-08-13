<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProvidedService extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'quantity',
        'value',
        'patient_value',
        'description',
    ];

    protected $appends = ['total'];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function treatment(): BelongsTo
    {
        return $this->belongsTo(Treatment::class, 'treatment_id');
    }

    public function getTotalAttribute()
    {
        return $this->value * $this->quantity;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'value',
        'titular_value',
        'dependent_value',
        'is_active'
    ];

    public function tratments(): HasMany
    {
        return $this->hasMany(Treatment::class);
    }
}

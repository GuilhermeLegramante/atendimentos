<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Person extends Model
{
    use HasFactory;

    protected $fillable = [
        'registration',
        'name',
        'cpf_cnpj',
        'is_active',
        'partner',
        'patient',
        'dependent',
        'address',
        'phone',
        'can_edit_values',
    ];
}

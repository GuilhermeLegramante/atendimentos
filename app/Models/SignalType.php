<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SignalType extends Model
{
    use HasFactory;

    protected $connection = 'marcaesinal';

    protected $table = 'agro_tipo_sinal';

    protected $fillable = [
        'descricao',
        'url',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'plataforma_id',
        'is_acceso'
    ];

    protected $casts = [
        'is_acceso' => 'boolean'
    ];
}

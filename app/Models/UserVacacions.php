<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserVacacions extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'dias_vacaciones_id',
        'contador',
        'activo',
    ];
}

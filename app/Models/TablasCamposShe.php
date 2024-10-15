<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TablasCamposShe extends Model
{
    use HasFactory;

    protected $fillable = [
        'tabla_id',
        'campo_id',
        'sitio_id',
    ];
}
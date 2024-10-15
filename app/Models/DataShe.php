<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataShe extends Model
{
    use HasFactory;

    protected $fillable = [
        'tabla_campo_id',
        'value',
        'año_mes',
    ];
}
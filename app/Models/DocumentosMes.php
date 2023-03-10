<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentosMes extends Model
{
    use HasFactory;

    protected $fillable = [
        'proceso_id',
        'user_id',
        'user_id',
        'documento_url',
        'fecha_mes',
    ];
}

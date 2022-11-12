<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Politic extends Model
{
    use HasFactory;


    protected $fillable = [
        'namepolitica',
        'descripcion',
        'type_politic',
        'id_statu',
        'autor',
        'imagePolitic',
        'pdf',
        'empleado_id'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rubro extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'proceso_id',
        'activo'
    ];

    public function calificaciones ()
    {
        return $this->hasMany(CalfRubroMe::class, 'rubro_id');
    }
}

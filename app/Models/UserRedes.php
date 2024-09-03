<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRedes extends Model
{
    use HasFactory;

    protected $fillable = [
        'redes_id',
        'user_id',
        'value',
    ];
}

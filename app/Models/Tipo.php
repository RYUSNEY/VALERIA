<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tipo extends Model
{
    protected $table = 'tipo';
    protected $primaryKey = 'id_tip'; // 👈 Aquí está el arreglo clave
}

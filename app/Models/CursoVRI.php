<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CursoVRI extends Model
{
    protected $table = 'cursos_vri'; // 👈 Especifica el nombre correcto
    public $timestamps = false;
}
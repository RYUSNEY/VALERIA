<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topico extends Model
{
    protected $table = 'topicos';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function cursosVri()
    {
        return $this->hasMany(CursoVri::class, 'id_topico');
    }
}
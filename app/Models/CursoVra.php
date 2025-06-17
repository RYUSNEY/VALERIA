<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CursoVra extends Model
{
    protected $table = 'cursos_vra';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function topico()
    {
        return $this->belongsTo(Topico::class, 'id_topico');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CertificadoOtorgadoVri extends Model
{
    protected $table = 'certificados_otorgados_vri';
    protected $primaryKey = 'id_cert';
    public $timestamps = false;

    public function curso()
    {
        return $this->belongsTo(CursoVri::class, 'id_cursos_vri');
    }
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'dni_usuario', 'dni');
    }
}
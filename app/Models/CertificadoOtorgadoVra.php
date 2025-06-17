<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CertificadoOtorgadoVra extends Model
{
    protected $table = 'certificados_otorgados_vra';
    protected $primaryKey = 'id_cert';
    public $timestamps = false;

    public function curso()
    {
        return $this->belongsTo(CursoVra::class, 'id_cursos_vra');
    }
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'dni_usuario', 'dni');
    }
}
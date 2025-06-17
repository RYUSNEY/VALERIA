<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $table = 'usuario';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function certificadosVra()
    {
        return $this->hasMany(CertificadoOtorgadoVra::class, 'dni_usuario', 'dni');
    }

    public function certificadosVri()
    {
        return $this->hasMany(CertificadoOtorgadoVri::class, 'dni_usuario', 'dni');
    }
}
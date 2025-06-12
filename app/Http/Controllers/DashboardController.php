<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CursoVRI;
use App\Models\CertificadoOtorgadoVRI;
use App\Models\Usuario;
use App\Models\Tipo;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Cursos VRI con conteo de certificados
        $cursos = CursoVRI::select('cursos_vri.id', 'cursos_vri.nombre', DB::raw('COUNT(certificados_otorgados_vri.id_cert) as total_certificados'))
            ->leftJoin('certificados_otorgados_vri', 'cursos_vri.id', '=', 'certificados_otorgados_vri.id_cursos_vri')
            ->groupBy('cursos_vri.id', 'cursos_vri.nombre')
            ->get();

        // 2. Usuarios con total de cursos seguidos
        $usuarios = Usuario::select('usuario.dni', 'usuario.nombres', 'usuario.ap_paterno', 'usuario.ap_materno', DB::raw('COUNT(certificados_otorgados_vri.id_cert) as total_cursos'))
            ->leftJoin('certificados_otorgados_vri', 'usuario.dni', '=', 'certificados_otorgados_vri.dni_usuario')
            ->groupBy('usuario.dni', 'usuario.nombres', 'usuario.ap_paterno', 'usuario.ap_materno')
            ->get();

        // 3. Tipo más frecuente
        $tipo_mas_frecuente = DB::table('certificados_otorgados_vri')
            ->select('id_tipo', DB::raw('COUNT(*) as total'))
            ->groupBy('id_tipo')
            ->orderByDesc('total')
            ->first();

        $tipo_nombre = $tipo_mas_frecuente
            ? Tipo::find($tipo_mas_frecuente->id_tipo)?->nombre
            : 'No disponible';

        return view('dashboard', compact('cursos', 'usuarios', 'tipo_nombre'));
    }
}

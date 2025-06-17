<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CertificadoOtorgadoVra;
use App\Models\CertificadoOtorgadoVri;
use App\Models\Usuario;
use App\Models\CursoVra;
use App\Models\CursoVri;

class InicioController extends Controller
{
    public function resumen()
    {
        // Total certificados (VRA + VRI)
        $totalCertificados = CertificadoOtorgadoVra::count() + CertificadoOtorgadoVri::count();

        // Total docentes registrados
        $totalDocentes = Usuario::count();

        // Total cursos (VRA + VRI)
        $totalCursos = CursoVra::count() + CursoVri::count();

        // Promedio de certificados por docente
        $totalDocentesParaPromedio = $totalDocentes > 0 ? $totalDocentes : 1; // evitar división por cero
        $promedioPorDocente = round($totalCertificados / $totalDocentesParaPromedio, 2);

        return response()->json([
            'total_certificados' => $totalCertificados,
            'total_docentes' => $totalDocentes,
            'total_cursos' => $totalCursos,
            'promedio_por_docente' => $promedioPorDocente,
        ]);
    }
} 
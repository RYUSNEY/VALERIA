<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Topico;
use Illuminate\Support\Facades\DB;

class DocenteController extends Controller
{
    public function index()
    {
        $docentes = Usuario::withCount([
            'certificadosVra as certificados_vra_count',
            'certificadosVri as certificados_vri_count',
        ])
        ->get()
        ->sortByDesc(function ($docente) {
            return $docente->certificados_vra_count + $docente->certificados_vri_count;
        })
        ->values(); // para resetear índices

        return response()->json($docentes);
    }

    public function detalle(Request $request, $dni)
    {
        $año = $request->input('año');
        $topico = $request->input('topico');

        $usuario = Usuario::where('dni', $dni)->first();

        $vri = $usuario->certificadosVri()->with(['curso' => function ($q) use ($año, $topico) {
            if ($año) $q->where('año', $año);
            if ($topico) $q->where('id_topico', $topico);
        }])->get();

        $vra = $usuario->certificadosVra()->with(['curso' => function ($q) use ($año, $topico) {
            if ($año) $q->where('año', $año);
            if ($topico) $q->where('id_topico', $topico);
        }])->get();

        return response()->json([
            'nombre_completo' => $usuario->nombres . ' ' . $usuario->ap_paterno,
            'vri' => $vri,
            'vra' => $vra
        ]);
    }

    public function filtros()
    {
        return response()->json([
            'topicos' => Topico::all(),
            'años' => range(2015, now()->year)
        ]);
    }
}

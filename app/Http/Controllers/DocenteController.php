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
        $usuarios = Usuario::withCount(['certificadosVra', 'certificadosVri'])->get();
        return response()->json($usuarios);
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

        return response()->json(['vri' => $vri, 'vra' => $vra]);
    }

    public function filtros()
    {
        return response()->json([
            'topicos' => Topico::all(),
            'años' => range(2015, now()->year)
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\CursoVra;
use App\Models\CursoVri;
use App\Models\CertificadoOtorgadoVra;
use App\Models\CertificadoOtorgadoVri;
use App\Models\Topico; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CursoController extends Controller
{
    /**
     * Devuelve una lista combinada de cursos VRI y VRA
     * con el conteo de sus participantes.
     */
    public function index()
    {
        // Obtenemos cursos VRI con el conteo de certificados asociados
        $cursosVri = CursoVri::withCount('certificados')->get()->map(function ($curso) {
            $curso->origen = 'vri'; // Añadimos origen para identificar en el frontend
            $curso->participantes_count = $curso->certificados_count; // Renombramos para claridad
            unset($curso->certificados_count);
            return $curso;
        });

        // Obtenemos cursos VRA con el conteo de certificados asociados
        $cursosVra = CursoVra::withCount('certificados')->get()->map(function ($curso) {
            $curso->origen = 'vra'; // Añadimos origen
            $curso->participantes_count = $curso->certificados_count;
            unset($curso->certificados_count);
            return $curso;
        });

        // Combinamos las dos colecciones y las ordenamos por nombre
        $todosLosCursos = $cursosVri->merge($cursosVra)->sortBy('nombre')->values();

        return response()->json($todosLosCursos);
    }

    /**
     * Devuelve la lista de nombres de los participantes de un curso específico.
     */
    public function getParticipantes($origen, $id)
    {
        $participantes = collect();

        if ($origen === 'vri') {
            $participantes = CertificadoOtorgadoVri::where('id_cursos_vri', $id)
                ->with('usuario') // Carga la información del usuario relacionado
                ->get()
                ->map(fn ($c) => $c->usuario); // Extrae solo los datos del usuario

        } elseif ($origen === 'vra') {
            $participantes = CertificadoOtorgadoVra::where('id_cursos_vra', $id)
                ->with('usuario')
                ->get()
                ->map(fn ($c) => $c->usuario);
        }

        // Filtramos posibles nulos si un DNI no se encuentra en la tabla de usuarios
        $nombresCompletos = $participantes->filter()->map(function ($usuario) {
            return "{$usuario->nombres} {$usuario->ap_paterno} {$usuario->ap_materno}";
        })->sort()->values();

        return response()->json($nombresCompletos);
    }
    public function getFiltros()
    {
        // Obtenemos los años distintos de la tabla de cursos VRI.
        // Usamos DB::raw para asegurar que solo traemos el valor del año.
        $anios_vri = CursoVri::select(DB::raw('DISTINCT año as valor'))
            ->whereNotNull('año')
            ->orderBy('valor', 'desc')
            ->get()->pluck('valor');

        // Obtenemos todos los tópicos.
        $topicos = Topico::select('id', 'nombre')->orderBy('nombre')->get();

        return response()->json([
            'anios' => $anios_vri,
            'topicos' => $topicos,
        ]);
    }
}
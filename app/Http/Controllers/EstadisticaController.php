<?php

namespace App\Http\Controllers;

use App\Models\CursoVri;
use App\Models\Topico;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EstadisticaController extends Controller
{
    public function index()
    {
        // --- 1. Docentes con más cursos ---
        // Usamos withCount para contar los certificados de cada tipo por usuario
        $docentes = Usuario::withCount(['certificadosVri', 'certificadosVra'])->get();
        
        // Sumamos los conteos en una nueva propiedad y ordenamos
        $topDocentes = $docentes->map(function ($docente) {
            $docente->cursos_totales = $docente->certificados_vri_count + $docente->certificados_vra_count;
            return $docente;
        })
        ->where('cursos_totales', '>', 0) // Solo mostramos docentes que tengan al menos 1 curso
        ->sortByDesc('cursos_totales')
        ->take(10) // Tomamos el Top 10
        ->values() // Resetea los índices del array
        ->map(function ($docente) { // Devolvemos solo los datos necesarios
            return [
                'nombre' => "{$docente->nombres} {$docente->ap_paterno}",
                'total' => $docente->cursos_totales,
            ];
        });

        // --- 2. Tópicos con más cursos ---
        // (Asumiendo que los tópicos solo se relacionan con cursos_vri)
        $topTopicos = Topico::withCount('cursosVri')
            ->orderBy('cursos_vri_count', 'desc')
            ->take(7) // Tomamos el Top 7 para que quepa bien en un gráfico de dona
            ->get(['nombre', 'cursos_vri_count as total']);

        // --- 3. Años con más cursos ---
        // (Asumiendo que los años solo están en cursos_vri)
        $cursosPorAnio = CursoVri::select('año', DB::raw('count(*) as total'))
            ->whereNotNull('año')
            ->groupBy('año')
            ->orderBy('año', 'asc') // Ordenamos por año para el gráfico de barras
            ->get();

        return response()->json([
            'topDocentes' => $topDocentes,
            'topTopicos' => $topTopicos,
            'cursosPorAnio' => $cursosPorAnio,
        ]);
    }
}
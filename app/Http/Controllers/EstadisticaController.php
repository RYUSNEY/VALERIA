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
        $topicosConteo = Topico::withCount(['cursosVri', 'cursosVra'])->get();

        // Ahora, sumamos los conteos, ordenamos, y tomamos el Top 7
        $topTopicos = $topicosConteo->map(function ($topico) {
            // Creamos una nueva propiedad 'total' con la suma
            $topico->total = $topico->cursos_vri_count + $topico->cursos_vra_count;
            return $topico;
        })
        ->where('total', '>', 0) // Mostramos solo tópicos que tengan cursos
        ->sortByDesc('total')
        ->take(8)
        ->values() // Resetea los índices para un JSON limpio
        ->map(function ($topico) { // Devolvemos solo lo que el gráfico necesita
            return [
                'nombre' => $topico->nombre,
                'total' => $topico->total
            ];
        });

        // --- 3. Años con más cursos (VRI + VRA) ---
        $vriQuery = DB::table('cursos_vri')->select('año')->whereNotNull('año');
        $vraQuery = DB::table('cursos_vra')->select('año')->whereNotNull('año');
        $unionQuery = $vriQuery->unionAll($vraQuery);

        // Ahora, hacemos una consulta sobre esa lista unida para agrupar y contar
        $cursosPorAnio = DB::query()
            ->fromSub($unionQuery, 'todos_los_cursos') // Trata la unión como una tabla temporal
            ->select('año', DB::raw('count(*) as total'))
            ->groupBy('año')
            ->orderBy('año', 'asc')
            ->get();

        return response()->json([
            'topDocentes' => $topDocentes,
            'topTopicos' => $topTopicos,
            'cursosPorAnio' => $cursosPorAnio, // Enviamos los nuevos datos combinados
        ]);
    }
}
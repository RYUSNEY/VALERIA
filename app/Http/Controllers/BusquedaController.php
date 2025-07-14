<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class BusquedaController extends Controller
{
    public function mostrarBusqueda()
    {
        return view('busqueda');
    }

    public function iniciarBusqueda(Request $request)
    {
        $request->validate([
            'dni' => 'required|string|size:8|regex:/^\d{8}$/'
        ]);

        $dni = $request->input('dni');
        $csvPath = storage_path('app/temp_dni.csv');
        $vraOutput = storage_path('app/resultados_vra_temp.json');
        $vriOutput = storage_path('app/resultados_vri_temp.json');
        file_put_contents($csvPath, "dni\n{$dni}");

        // Ejecutar scripts en segundo plano (sin esperar) usando popen para máxima simultaneidad
        $python = 'python'; // O la ruta completa a python.exe si es necesario
        $vraCmd = "$python " . base_path('vra_updated.py') . " $csvPath $vraOutput > nul 2>&1";
        $vriCmd = "$python " . base_path('vri_updated.py') . " $csvPath $vriOutput > nul 2>&1";
        popen($vraCmd, 'r');
        popen($vriCmd, 'r');

        return response()->json(['status' => 'busqueda_iniciada']);
    }

    public function progresoBusqueda(Request $request)
    {
        $dni = $request->input('dni');
        $vraOutput = storage_path('app/resultados_vra_temp.json');
        $vriOutput = storage_path('app/resultados_vri_temp.json');

        $vra = file_exists($vraOutput) ? json_decode(file_get_contents($vraOutput), true) : [];
        $vri = file_exists($vriOutput) ? json_decode(file_get_contents($vriOutput), true) : [];

        return response()->json([
            'vra' => $vra[0] ?? ['encontrado' => false, 'certificados' => []],
            'vri' => $vri[0] ?? ['encontrado' => false, 'certificados' => []]
        ]);
    }

    private function ejecutarScript($scriptName, $csvPath, $outputPath)
    {
        try {
            $scriptPath = base_path($scriptName);
            $process = new Process(['python', $scriptPath, $csvPath, $outputPath]);
            $process->setTimeout(60);
            $process->run();

            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            if (file_exists($outputPath)) {
                $result = json_decode(file_get_contents($outputPath), true);
                unlink($outputPath);
                return $result[0] ?? ['encontrado' => false, 'certificados' => []];
            }
            return ['encontrado' => false, 'certificados' => []];
        } catch (\Exception $e) {
            Log::error('Error ejecutando script ' . $scriptName . ': ' . $e->getMessage());
            return ['encontrado' => false, 'certificados' => []];
        }
    }
} 
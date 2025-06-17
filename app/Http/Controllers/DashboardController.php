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
        return view('inicio');
    }

    public function docentes()
    {
        return view('docentes');
    }

    public function cursos()
    {
        return view('cursos');
    }

    public function topicos()
    {
        return view('topicos');
    }

    public function otros()
    {
        return view('otros');
    }
}

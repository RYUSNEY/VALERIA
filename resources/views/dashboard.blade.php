<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Certificados</title>
  @vite(['resources/sass/app.scss', 'resources/js/app.js'])

</head>
<body>
    <div id="app">
        <v-app>
            <dashboard-component 
            :cursos="{{ $cursos }}"
            :usuarios="{{ $usuarios }}"
            :tipos="{{ json_encode([
                'tipo_1' => \App\Models\CertificadoOtorgadoVRI::where('id_tipo', 1)->count(),
                'tipo_2' => \App\Models\CertificadoOtorgadoVRI::where('id_tipo', 2)->count(),
                'tipo_3' => \App\Models\CertificadoOtorgadoVRI::where('id_tipo', 3)->count(),
                'tipo_4' => \App\Models\CertificadoOtorgadoVRI::where('id_tipo', 4)->count(),
                'tipo_5' => \App\Models\CertificadoOtorgadoVRI::where('id_tipo', 5)->count(),
            ]) }}"
            />
        </v-app>
    </div>
</body>
</html>

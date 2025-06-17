{{-- resources/views/layouts/app.blade.php --}}

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- El título puede ser dinámico --}}
    <title>@yield('title', 'RUAPAZAP Certificados')</title>
    @vite(['resources/js/app.js', 'resources/sass/app.scss'])
</head>
<body>
    <div id="app">
        <v-app>
            <nav-bar></nav-bar>
            <v-main> {{-- v-main es bueno para el contenido principal --}}
                @yield('content')
            </v-main>
        </v-app>
    </div>
</body>
</html>
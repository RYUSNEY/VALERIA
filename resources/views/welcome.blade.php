<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    @vite(['resources/js/app.js'])  {{-- Asegúrate de tener esto --}}
</head>
<body>
    <div id="app">
        <dashboard-component
            :cursos="{{ json_encode($cursos) }}"
            :usuarios="{{ json_encode($usuarios) }}"
            :tipos="{{ json_encode($tipos) }}"
        ></dashboard-component>
    </div>
</body>
</html>

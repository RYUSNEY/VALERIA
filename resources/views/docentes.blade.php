<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Docentes</title>
    @vite(['resources/js/app.js', 'resources/sass/app.scss'])
</head>
<body>
    <div id="app">
        <v-app>
            <nav-bar></nav-bar>
            <docentes-component></docentes-component>
        </v-app>
    </div>
</body>
</html>
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
            <v-main class="bg-grey-lighten-4">
                <div class="pa-4">
                    <!-- Aquí montas el componente Vue que desees -->
                    <docentes-component></docentes-component>
                </div>
            </v-main>
            
        </v-app>
    </div>
</body>
</html>
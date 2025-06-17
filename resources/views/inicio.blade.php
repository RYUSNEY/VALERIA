<!DOCTYPE html>
<html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Inicio</title>
        @vite(['resources/js/app.js'])
    </head>

    <body>
        <div id="app">
            <v-app>
                <nav-bar></nav-bar>
                <inicio-component></inicio-component>
            </v-app>
        </div>
    </body>

</html>

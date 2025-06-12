// resources/js/app.js

import './bootstrap'
import { createApp } from 'vue'
import vuetify from './plugins/vuetify'

// Componentes
import DashboardComponent from './components/DashboardComponent.vue'
import ExampleComponent from './components/ExampleComponent.vue'
import NavBar from './components/NavBar.vue' // Importa tu nuevo navbar

// Crear instancia principal
const app = createApp({})

// Registrar componentes globales
app.component('dashboard-component', DashboardComponent)
app.component('example-component', ExampleComponent)
app.component('nav-bar', NavBar)

// Usar Vuetify
app.use(vuetify)

// Montar en el DOM
app.mount('#app')

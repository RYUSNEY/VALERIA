// resources/js/app.js

import './bootstrap'
import { createApp } from 'vue'
import vuetify from './plugins/vuetify'

// Componentes Vue
import NavBar from './components/NavBar.vue'
import InicioComponent from './components/InicioComponent.vue'
import DocentesComponent from './components/DocentesComponent.vue'
//import CursosComponent from './components/CursosComponent.vue'
//import TopicosComponent from './components/TopicosComponent.vue'
//import OtrosComponent from './components/OtrosComponent.vue'

const app = createApp({})

// Registrar como componentes globales
app.component('nav-bar', NavBar)
app.component('inicio-component', InicioComponent)
app.component('docentes-component', DocentesComponent)
//app.component('cursos-component', CursosComponent)
//app.component('topicos-component', TopicosComponent)
//app.component('otros-component', OtrosComponent)

app.use(vuetify)

app.mount('#app')

<template>
  <!-- ✅ Navbar -->
  <nav-bar />

  <!-- ✅ Contenedor sin márgenes -->
  <v-container fluid class="pa-0 ma-0 fill-height d-flex align-center" style="background-color: #f9fbfd; overflow: hidden; width: 100%;">
    <v-row dense class="ma-0 pa-0 w-100">
      <!-- 📘 Cursos -->
      <v-col cols="12" sm="6" md="3">
        <v-card elevation="2" class="rounded-xl" height="100%">
          <v-card-title class="text-h6 font-weight-bold pa-4">📘 Cursos VRI</v-card-title>
          <v-divider/>
          <v-card-text class="scrollable-content">
            <v-list density="compact" lines="one">
              <v-list-item v-for="curso in cursos" :key="curso.id">
                <v-list-item-content>
                  <v-list-item-title class="text-body-2 font-weight-medium">
                    {{ curso.nombre }}
                  </v-list-item-title>
                  <v-list-item-subtitle class="text-caption text-grey">
                    {{ curso.total_certificados }} certificados
                  </v-list-item-subtitle>
                </v-list-item-content>
              </v-list-item>
            </v-list>
          </v-card-text>
        </v-card>
      </v-col>

      <!-- 👥 Usuarios -->
      <v-col cols="12" sm="6" md="3">
        <v-card elevation="2" class="rounded-xl pa-4" height="100%">
          <v-card-title class="text-h6 font-weight-bold">👥 Usuarios</v-card-title>
          <v-divider class="mb-2" />
          <v-card-text class="scrollable-content">
            <v-list density="compact" lines="one">
              <v-list-item v-for="user in usuarios" :key="user.dni">
                <v-list-item-content>
                  <v-list-item-title class="text-body-2 font-weight-medium">
                    {{ user.nombres }} {{ user.ap_paterno }} {{ user.ap_materno }}
                  </v-list-item-title>
                  <v-list-item-subtitle class="text-caption text-grey">
                    {{ user.total_cursos }} cursos
                  </v-list-item-subtitle>
                </v-list-item-content>
              </v-list-item>
            </v-list>
          </v-card-text>
        </v-card>
      </v-col>

      <!-- 📊 Distribución -->
      <v-col cols="12" sm="6" md="3">
        <v-card elevation="2" class="rounded-xl pa-4" height="100%">
          <v-card-title class="text-h6 font-weight-bold">📊 Distribución de Tipos</v-card-title>
          <v-divider class="mb-2" />
          <v-card-text class="scrollable-content">
            <v-list density="compact" lines="one">
              <v-list-item v-for="(value, key) in tipos" :key="key">
                <v-list-item-content>
                  <v-list-item-title class="text-body-2">
                    {{ formatTipo(key) }}
                  </v-list-item-title>
                  <v-list-item-subtitle class="text-caption text-grey">
                    {{ value }} certificados ({{ calcularPorcentaje(value) }}%)
                  </v-list-item-subtitle>
                </v-list-item-content>
              </v-list-item>
            </v-list>
          </v-card-text>
        </v-card>
      </v-col>

      <!-- 🧪 Evaluación -->
      <v-col cols="12" sm="6" md="3">
        <v-card elevation="2" class="rounded-xl pa-4" height="100%">
          <v-card-title class="text-h6 font-weight-bold">🧪 Evaluación</v-card-title>
          <v-divider class="mb-2" />
          <v-card-text>

            <!-- Campo para ingresar DNI -->
            <v-text-field v-model="dni" label="Ingrese DNI" outlined dense clearable></v-text-field>

            <!-- Botón para buscar -->
            <v-btn color="primary" @click="buscarCursos">Buscar</v-btn>

            <!-- Tabla de resultados -->
            <v-simple-table class="mt-4" v-if="resultados.length">
              <thead>
                <tr>
                  <th>Curso</th>
                  <th>Tipo</th>
                  <th>Código</th>
                  <th>Enlace</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="curso in resultados" :key="curso.codigo">
                  <td>{{ curso.nombre_curso }}</td>
                  <td>{{ curso.tipo }}</td>
                  <td>{{ curso.codigo }}</td>
                  <td>
                    <a :href="curso.enlace" target="_blank">Ver</a>
                  </td>
                </tr>
              </tbody>
            </v-simple-table>

          </v-card-text>
        </v-card>
      </v-col>


    </v-row>
  </v-container>
</template>

<style scoped>
html, body, #app {
  height: 100%;
  margin: 0;
  overflow: hidden;
}

.v-container {
  min-height: 100vh;
}

.scrollable-content {
  max-height: 60vh;
  overflow-y: auto;
  padding-right: 4px;
}
</style>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  cursos: Array,
  usuarios: Array,
  tipos: Object
})

const totalCertificados = computed(() => {
  return Object.values(props.tipos).reduce((a, b) => a + b, 0)
})

const calcularPorcentaje = (valor) => {
  if (totalCertificados.value === 0) return 0
  return ((valor / totalCertificados.value) * 100).toFixed(1)
}

const formatTipo = (tipo) => {
  switch (tipo) {
    case 'tipo_1': return 'Organizador'
    case 'tipo_2': return 'Ponente'
    case 'tipo_3': return 'Asistente'
    case 'tipo_4': return 'Aprobado'
    case 'tipo_5': return 'Asistente + Aprobado'
    default: return tipo
  }
}

// Ordenar usuarios y cursos
const usuarios = computed(() => [...props.usuarios].sort((a, b) => b.total_cursos - a.total_cursos))
const cursos = computed(() => [...props.cursos].sort((a, b) => b.total_certificados - a.total_certificados))
</script>


<script>
export default {
  data() {
    return {
      dni: '',
      mensaje: '',
      resultados: [],
      headers: [
        { text: 'Curso', value: 'nombre_curso' },
        { text: 'Tipo', value: 'tipo' },
        { text: 'Código', value: 'codigo' },
        { text: 'Enlace', value: 'enlace' }
      ]
    };
  },
  methods: {
    async buscarCursos() {
      if (!this.dni) {
        this.mensaje = 'Por favor, ingrese un DNI.';
        return;
      }
      this.mensaje = 'Buscando cursos...';

      try {
        const response = await fetch('http://127.0.0.1:5000/buscar-dni', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ dni: this.dni })
        });
        const data = await response.json();

        if (data.encontrado) {
          this.resultados = data.certificados;
          this.mensaje = `Se encontraron ${data.certificados.length} cursos.`;
        } else {
          this.resultados = [];
          this.mensaje = 'No se encontraron cursos para este DNI.';
        }
      } catch (err) {
        this.mensaje = 'Ocurrió un error al buscar.';
        console.error(err);
      }
    }
  }
};
</script>
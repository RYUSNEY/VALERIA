<template>
    <!-- ✅ AQUI va tu navbar -->
    <navbar />
  <v-container class="py-6 fill-height d-flex align-center" style="background-color: #f9fbfd; overflow: hidden;">
    <v-row dense class="w-100">

      <!-- 📘 Cursos -->
      <v-col cols="12" md="4">
        <v-card elevation="4" class="rounded-xl pa-4" height="100%">
          <v-card-title class="text-h6 font-weight-bold">
            📘 Cursos VRI
          </v-card-title>
          <v-divider class="mb-2" />
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
      <v-col cols="12" md="4">
        <v-card elevation="4" class="rounded-xl pa-4" height="100%">
          <v-card-title class="text-h6 font-weight-bold">
            👥 Usuarios
          </v-card-title>
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
      <v-col cols="12" md="4">
        <v-card elevation="4" class="rounded-xl pa-4" height="100%">
          <v-card-title class="text-h6 font-weight-bold">
            📊 Distribución de Tipos
          </v-card-title>
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
  height: 100vh;
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

// Ordenar usuarios por total_cursos descendente
const usuarios = computed(() => {
  return [...props.usuarios].sort((a, b) => b.total_cursos - a.total_cursos)
})

// Ordenar cursos por total_certificados descendente
const cursos = computed(() => {
  return [...props.cursos].sort((a, b) => b.total_certificados - a.total_certificados)
})
</script>


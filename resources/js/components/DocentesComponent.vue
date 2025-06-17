<template>
  <v-container fluid>
    <v-row>
      <v-col cols="12" md="6">
        <docente-lista @seleccionar="cargarDetalle" :docentes="docentes" />
      </v-col>
      <v-col cols="12" md="6">
        <docente-detalle :detalle="detalle" :filtros="filtros" @filtrar="filtrarDetalle" />
      </v-col>
    </v-row>
  </v-container>
</template>

<script setup>
    import { ref, onMounted } from 'vue'
    import DocenteLista from './DocenteLista.vue'
    import DocenteDetalle from './DocenteDetalle.vue'

    const docentes = ref([])
    const detalle = ref({})
    const filtros = ref({ topicos: [], años: [] })

    onMounted(async () => {
        const res = await fetch('/api/docentes')
        docentes.value = await res.json()

        const f = await fetch('/api/docentes-filtros')
        filtros.value = await f.json()
    })

    async function cargarDetalle(dni) {
        const res = await fetch(`/api/docentes/${dni}`)
        detalle.value = await res.json()
    }

    async function filtrarDetalle({ dni, año, topico }) {
        const res = await fetch(`/api/docentes/${dni}?año=${año}&topico=${topico}`)
        detalle.value = await res.json()
    }
</script>

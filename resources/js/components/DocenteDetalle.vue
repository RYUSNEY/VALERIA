<template>
  <div>
    <v-select :items="filtros.topicos" item-title="nombre" item-value="id" label="Filtrar por tópico" v-model="topico" @change="filtrar" />
    <v-select :items="filtros.años" label="Filtrar por año" v-model="año" @change="filtrar" />

    <v-card class="mt-4">
      <v-card-title>Cursos del VRI</v-card-title>
      <v-list>
        <v-list-item
          v-for="item in detalle.vri"
          :key="item.id_cert"
        >
          <v-list-item-title>
            <a :href="item.codigo" target="_blank">{{ item.curso?.nombre }}</a>
          </v-list-item-title>
        </v-list-item>
      </v-list>
    </v-card>

    <v-card class="mt-4">
      <v-card-title>Cursos del VRA</v-card-title>
      <v-list>
        <v-list-item
          v-for="item in detalle.vra"
          :key="item.id_cert"
        >
          <v-list-item-title>
            <a :href="item.enlace" target="_blank">{{ item.curso?.nombre }}</a>
          </v-list-item-title>
        </v-list-item>
      </v-list>
    </v-card>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'

const props = defineProps(['detalle', 'filtros'])
const emit = defineEmits(['filtrar'])
const topico = ref(null)
const año = ref(null)
const dni = ref(null)

watch(() => props.detalle, val => {
  if (val && val.vra?.[0]) dni.value = val.vra[0].dni_usuario
  if (val && val.vri?.[0]) dni.value = val.vri[0].dni_usuario
})

function filtrar() {
  if (dni.value) {
    emit('filtrar', { dni: dni.value, año: año.value, topico: topico.value })
  }
}
</script>

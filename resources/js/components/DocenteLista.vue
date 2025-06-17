<template>
  <v-list>
    <v-list-item
      v-for="d in docentes"
      :key="d.id"
      @click="$emit('seleccionar', d.dni)"
    >
      <v-list-item-title>{{ d.nombres }} {{ d.ap_paterno }} ({{ d.certificados_vra_count + d.certificados_vri_count }})</v-list-item-title>
    </v-list-item>
  </v-list>
</template>

<script setup>
defineProps(['docentes'])
</script>
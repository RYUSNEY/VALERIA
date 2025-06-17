<template>
  <v-app-bar app flat color="primary" class="px-4">
    <!-- Ícono hamburguesa en móvil -->
    <v-app-bar-nav-icon
      class="d-sm-none"
      @click="drawer = !drawer"
    ></v-app-bar-nav-icon>

    <!-- Logo + Nombre -->
    <v-toolbar-title class="d-flex align-center gap-2">
      <v-img
        src="/logo.png"
        alt="Logo"
        width="32"
        height="32"
        contain
      ></v-img>
      <span class="font-weight-bold text-white">RUAPAZAP CERTIFICADOS</span>
    </v-toolbar-title>

    <!-- Menú en escritorio -->
    <v-spacer></v-spacer>
    <div class="d-none d-sm-flex gap-4">
      <v-btn text class="text-white" to="/">Inicio</v-btn>
      <v-btn text class="text-white" to="/docentes">Docentes</v-btn>
      <v-btn text class="text-white" to="/cursos">Cursos</v-btn>
      <v-btn text class="text-white" to="/topicos">Tópicos</v-btn>
      <v-btn text class="text-white" to="/otros">Otros</v-btn>
    </div>
  </v-app-bar>

  <!-- Drawer lateral en móvil -->
  <v-navigation-drawer
    v-model="drawer"
    temporary
    class="d-sm-none"
    location="left"
  >
    <v-list nav dense>
      <v-list-item to="/" title="Inicio" prepend-icon="mdi-home" />
      <v-list-item to="/docentes" title="Docentes" prepend-icon="mdi-account" />
      <v-list-item to="/cursos" title="Cursos" prepend-icon="mdi-book" />
      <v-list-item to="/topicos" title="Tópicos" prepend-icon="mdi-tag" />
      <v-list-item to="/otros" title="Otros" prepend-icon="mdi-dots-horizontal" />
    </v-list>
  </v-navigation-drawer>
</template>

<script setup>
import { ref } from 'vue'

const drawer = ref(false)
</script>

<style scoped>
.gap-2 {
  gap: 0.5rem;
}
.gap-4 {
  gap: 1rem;
}
</style>

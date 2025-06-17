<template>
  <v-app-bar
    app
    fixed
    color="primary"
    height="64"
    class="px-4"
  >
    <!-- Ícono hamburguesa en móvil -->
    <v-app-bar-nav-icon class="d-sm-none" @click="drawer = !drawer"></v-app-bar-nav-icon>

    <!-- Logo + Nombre -->
    <v-toolbar-title class="pa-0 ma-0">
      <div class="d-flex align-left justify-start ml-0">
        <v-img
          :src="logo"
          alt="Logo"
          width="32"
          height="32"
          class="mr-2"
          contain
        ></v-img>
        <span class="font-weight-bold text-white text-truncate">VALERIA</span>
      </div>
    </v-toolbar-title>

    <v-spacer></v-spacer>

    <!-- Menú escritorio -->
    <div class="d-none d-sm-flex gap-4">
      <v-btn text class="text-white" href="/">Inicio</v-btn>
      <v-btn text class="text-white" href="/docentes">Docentes</v-btn>
      <v-btn text class="text-white" href="/cursos">Cursos</v-btn>
      <v-btn text class="text-white" href="/otros">Información Estadistica</v-btn>
    </div>
  </v-app-bar>

  <!-- Drawer móvil -->
  <v-navigation-drawer
    v-model="drawer"
    temporary
    class="d-sm-none"
    location="left"
  >
    <v-list nav dense>
      <v-list-item href="/" title="Inicio" prepend-icon="mdi-home" />
      <v-list-item href="/docentes" title="Docentes" prepend-icon="mdi-account" />
      <v-list-item href="/cursos" title="Cursos" prepend-icon="mdi-book" />
      <v-list-item href="/otros" title="Información Estadistica" prepend-icon="mdi-dots-horizontal" />
    </v-list>
  </v-navigation-drawer>
</template>

<script setup>
import { ref } from 'vue'
import logo from '@/img/logo.png'

const drawer = ref(false)
</script>

<style scoped>
.gap-2 {
  gap: 1rem;
}
.gap-4 {
  gap: 1rem;
}
</style>

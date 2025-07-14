<template>
  <v-app-bar
    app
    fixed
    color="primary"
    height="64"
    class="navbar-app-bar"
  >
    <!-- Ícono hamburguesa en móvil -->
    <v-app-bar-nav-icon class="d-sm-none" @click="drawer = !drawer"></v-app-bar-nav-icon>

    <!-- Logo + Nombre -->
    <v-toolbar-title class="pa-0 ma-0" style="min-width:0;">
      <div class="d-flex align-center" style="gap: 6px;">
        <v-img
          :src="logo"
          alt="Logo"
          width="32"
          height="32"
          contain
        ></v-img>
        <span class="font-weight-bold text-white text-truncate">VALERIA</span>
      </div>
    </v-toolbar-title>

    <!-- Espaciadores: cuatro en escritorio, uno en móvil -->
    <v-spacer class="d-none d-sm-flex"></v-spacer>
    <v-spacer class="d-none d-sm-flex"></v-spacer>
    <v-spacer class="d-none d-sm-flex"></v-spacer>
    <v-spacer class="d-none d-sm-flex"></v-spacer>
    <v-spacer class="d-flex d-sm-none"></v-spacer>

    <!-- Menú escritorio -->
    <div class="d-none d-sm-flex gap-4">
      <v-btn text class="text-white" href="/">Inicio</v-btn>
      <v-btn text class="text-white" href="/docentes">Docentes</v-btn>
      <v-btn text class="text-white" href="/cursos">Cursos</v-btn>
      <v-btn text class="text-white" href="/otros">Información Estadistica</v-btn>
      <v-btn text class="text-white" href="/busqueda" prepend-icon="mdi-magnify">Búsqueda</v-btn>
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
      <v-list-item href="/busqueda" title="Búsqueda" prepend-icon="mdi-magnify" />
    </v-list>
  </v-navigation-drawer>
</template>

<script setup>
import { ref } from 'vue'
import logo from '@/img/logo.png'

const drawer = ref(false)
</script>

<style scoped>
.navbar-app-bar {
  padding-left: 0 !important;
}
@media (min-width: 600px) {
  .navbar-app-bar {
    padding-left: 24px !important;
  }
}
.gap-4 {
  gap: 1rem;
}
</style>

<template>
  <v-container fluid>
    <h1 class="mb-2">Dashboard de Estadísticas</h1>
    <p class="text-medium-emphasis mb-6">Visualización de datos clave sobre cursos y docentes.</p>

    <v-row v-if="cargando">
      <v-col cols="12" md="6"><v-skeleton-loader type="image"></v-skeleton-loader></v-col>
      <v-col cols="12" md="6"><v-skeleton-loader type="image"></v-skeleton-loader></v-col>
    </v-row>

    <v-row v-if="!cargando && estadisticas">
      <v-col cols="12">
        <v-card elevation="2">
          <v-card-title>Top 10: Docentes con Más Cursos</v-card-title>
          <v-card-text>
            <apexchart
              type="bar"
              height="350"
              :options="chartOptionsDocentes"
              :series="seriesDocentes"
            ></apexchart>
          </v-card-text>
        </v-card>
      </v-col>

      <v-col cols="12" md="6">
        <v-card elevation="2">
          <v-card-title>Distribución de Cursos por Tópico</v-card-title>
          <v-card-text>
             <apexchart
              type="donut"
              height="350"
              :options="chartOptionsTopicos"
              :series="seriesTopicos"
            ></apexchart>
          </v-card-text>
        </v-card>
      </v-col>

      <v-col cols="12" md="6">
        <v-card elevation="2">
          <v-card-title>Cantidad de Cursos por Año</v-card-title>
           <v-card-text>
             <apexchart
              type="bar"
              height="350"
              :options="chartOptionsAnios"
              :series="seriesAnios"
            ></apexchart>
          </v-card-text>
        </v-card>
      </v-col>

    </v-row>

  </v-container>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import VueApexCharts from 'vue3-apexcharts';

const apexchart = VueApexCharts;

const cargando = ref(true);
const estadisticas = ref(null);
const API_URL = '/api';

onMounted(async () => {
  try {
    const response = await axios.get(`${API_URL}/estadisticas`);
    estadisticas.value = response.data;
  } catch (err) {
    console.error("Error al cargar las estadísticas:", err);
  } finally {
    cargando.value = false;
  }
});

// --- Configuración para Gráfico 1: Top Docentes ---
const seriesDocentes = computed(() => {
  if (!estadisticas.value) return [];
  return [{
    name: 'Cursos Totales',
    data: estadisticas.value.topDocentes.map(d => d.total)
  }];
});
const chartOptionsDocentes = computed(() => {
  if (!estadisticas.value) return {};
  return {
    chart: { type: 'bar', height: 350 },
    plotOptions: { bar: { borderRadius: 4, horizontal: true } },
    dataLabels: { enabled: false },
    xaxis: {
      categories: estadisticas.value.topDocentes.map(d => d.nombre),
    },
    tooltip: { y: { formatter: (val) => `${val} cursos` } }
  };
});


// --- Configuración para Gráfico 2: Top Tópicos ---
const seriesTopicos = computed(() => {
  if (!estadisticas.value) return [];
  return estadisticas.value.topTopicos.map(t => t.total);
});

const chartOptionsTopicos = computed(() => {
  if (!estadisticas.value) return {};
  return {
    chart: { type: 'donut', height: 350 },
    labels: estadisticas.value.topTopicos.map(t => t.nombre),

    // --- SECCIÓN MODIFICADA ---
    dataLabels: {
      enabled: true,
      formatter: function (val, opts) {
        const rawValue = opts.w.globals.series[opts.seriesIndex];
        return `${rawValue} (${val.toFixed(1)}%)`;
      }
    },
    // -------------------------

    responsive: [{
      breakpoint: 480,
      options: { chart: { width: 200 }, legend: { position: 'bottom' } }
    }],
    tooltip: { y: { formatter: (val) => `${val} cursos` } }
  };
});


// --- Configuración para Gráfico 3: Cursos por Año ---
const seriesAnios = computed(() => {
  if (!estadisticas.value) return [];
  return [{
    name: 'Cantidad de Cursos',
    data: estadisticas.value.cursosPorAnio.map(a => a.total)
  }];
});
const chartOptionsAnios = computed(() => {
  if (!estadisticas.value) return {};
  return {
    chart: { type: 'bar', height: 350 },
    xaxis: {
      categories: estadisticas.value.cursosPorAnio.map(a => a.año),
    },
    dataLabels: { enabled: false },
    tooltip: { y: { formatter: (val) => `${val} cursos` } }
  };
});
</script>
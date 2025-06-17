<template>
  <v-container>
    <h1 class="mb-2">Explorar Cursos</h1>
    <p class="text-medium-emphasis mb-6">Busca, filtra y ordena todos los cursos ofrecidos.</p>

    <v-card class="mb-6" color="white" elevation="2">
      <v-card-text>
        <v-row align="center">
          <v-col cols="12" md="4">
            <v-text-field
              v-model="filtros.busqueda"
              label="Buscar por nombre de curso..."
              prepend-inner-icon="mdi-magnify"
              variant="solo-filled"
              clearable
              hide-details
              density="compact"
            ></v-text-field>
          </v-col>
          <v-col cols="12" md="2">
            <v-select
              v-model="filtros.topico"
              :items="datosFiltros.topicos"
              item-title="nombre"
              item-value="id"
              label="Tópico"
              variant="solo-filled"
              clearable
              hide-details
              density="compact"
            ></v-select>
          </v-col>
          <v-col cols="12" md="2">
            <v-select
              v-model="filtros.anio"
              :items="datosFiltros.anios"
              label="Año"
              variant="solo-filled"
              clearable
              hide-details
              density="compact"
            ></v-select>
          </v-col>
          <v-col cols="12" md="3">
            <v-select
              v-model="filtros.orden"
              :items="opcionesOrden"
              item-title="texto"
              item-value="valor"
              label="Ordenar por"
              variant="solo-filled"
              hide-details
              density="compact"
            ></v-select>
          </v-col>
          <v-col cols="12" md="1">
              <v-btn @click="limpiarFiltros" variant="text" color="grey">Limpiar</v-btn>
          </v-col>
        </v-row>
      </v-card-text>
    </v-card>

    <div class="cursos-list-container">
      <v-skeleton-loader v-if="cargando" type="list-item-two-line@6"></v-skeleton-loader>

      <v-alert v-if="error" type="error" prominent border="start" class="mb-4">
        {{ error }}
      </v-alert>

      <div v-if="!cargando && !error">
        <v-card>
          <v-list lines="two">
            <v-list-item
              v-for="curso in cursosFiltrados"
              :key="`${curso.origen}-${curso.id}`"
              @click="verParticipantes(curso)"
            >
              <v-list-item-title class="font-weight-bold">{{ curso.nombre }}</v-list-item-title>
              <v-list-item-subtitle>
                <v-chip size="small" :color="curso.origen === 'vri' ? 'blue' : 'green'" class="mr-2">{{ curso.origen.toUpperCase() }}</v-chip>
                <span v-if="curso.año">Año: {{ curso.año }}</span>
              </v-list-item-subtitle>
              <template v-slot:append>
                <v-chip color="primary" variant="tonal" label>
                  <v-icon start icon="mdi-account-group"></v-icon>
                  {{ curso.participantes_count }} Participantes
                </v-chip>
              </template>
            </v-list-item>
          </v-list>
        </v-card>
        
        <v-alert v-if="!cargando && cursosFiltrados.length === 0" type="info" variant="tonal" class="mt-4">
            No se encontraron cursos que coincidan con los filtros seleccionados.
        </v-alert>
      </div>
    </div>
    
    <v-dialog v-model="dialog" max-width="600">
      <v-card v-if="cursoSeleccionado" :title="`Participantes de: ${cursoSeleccionado.nombre}`">
          <v-card-text>
              <div v-if="cargandoParticipantes" class="text-center pa-4"><v-progress-circular indeterminate color="primary"></v-progress-circular></div>
              <v-list v-else-if="participantes.length > 0"><v-list-item v-for="(nombre, index) in participantes" :key="index" :title="nombre"></v-list-item></v-list>
              <p v-else>No hay participantes registrados para este curso.</p>
          </v-card-text>
          <v-card-actions><v-spacer></v-spacer><v-btn text="Cerrar" @click="dialog = false"></v-btn></v-card-actions>
      </v-card>
    </v-dialog>

  </v-container>
</template>

<script setup>
// El script no necesita cambios, es igual al anterior
import { ref, onMounted, computed, reactive } from 'vue';
import axios from 'axios';

const cursos = ref([]);
const cargando = ref(true);
const error = ref(null);
const datosFiltros = ref({ anios: [], topicos: [] });
const filtros = reactive({
    busqueda: '',
    topico: null,
    anio: null,
    orden: 'participantes_desc',
});
const opcionesOrden = [
    { texto: 'Más participantes', valor: 'participantes_desc' },
    { texto: 'Menos participantes', valor: 'participantes_asc' },
    { texto: 'Nombre (A-Z)', valor: 'nombre_asc' },
    { texto: 'Nombre (Z-A)', valor: 'nombre_desc' },
];
const dialog = ref(false);
const cursoSeleccionado = ref(null);
const participantes = ref([]);
const cargandoParticipantes = ref(false);
const API_URL = '/api';

onMounted(async () => {
    try {
        const [resCursos, resFiltros] = await Promise.all([
            axios.get(`${API_URL}/cursos`),
            axios.get(`${API_URL}/cursos-filtros`)
        ]);
        cursos.value = resCursos.data;
        datosFiltros.value = resFiltros.data;
    } catch (err) {
        console.error("Error al cargar datos:", err);
        error.value = "No se pudieron cargar los datos de los cursos. Inténtelo más tarde.";
    } finally {
        cargando.value = false;
    }
});

const cursosFiltrados = computed(() => {
    let cursosTemp = [...cursos.value];
    if (filtros.busqueda) {
        cursosTemp = cursosTemp.filter(curso =>
            curso.nombre.toLowerCase().includes(filtros.busqueda.toLowerCase())
        );
    }
    if (filtros.topico) {
        cursosTemp = cursosTemp.filter(curso => curso.id_topico === filtros.topico);
    }
    if (filtros.anio) {
        cursosTemp = cursosTemp.filter(curso => curso.año === filtros.anio);
    }
    switch(filtros.orden) {
        case 'participantes_desc':
            cursosTemp.sort((a, b) => b.participantes_count - a.participantes_count);
            break;
        case 'participantes_asc':
            cursosTemp.sort((a, b) => a.participantes_count - b.participantes_count);
            break;
        case 'nombre_asc':
            cursosTemp.sort((a, b) => a.nombre.localeCompare(b.nombre));
            break;
        case 'nombre_desc':
            cursosTemp.sort((a, b) => b.nombre.localeCompare(a.nombre));
            break;
    }
    return cursosTemp;
});

function limpiarFiltros() {
    filtros.busqueda = '';
    filtros.topico = null;
    filtros.anio = null;
    filtros.orden = 'participantes_desc';
}

const verParticipantes = async (curso) => {
    cursoSeleccionado.value = curso;
    dialog.value = true;
    cargandoParticipantes.value = true;
    participantes.value = [];
    try {
        const response = await axios.get(`${API_URL}/cursos/${curso.origen}/${curso.id}/participantes`);
        participantes.value = response.data;
    } catch (err) {
        console.error("Error al cargar participantes:", err);
    } finally {
        cargandoParticipantes.value = false;
    }
};
</script>

<style scoped>
.cursos-list-container {
  /* Le damos una altura máxima que es el 65% de la altura visible del navegador.
     Puedes ajustar este valor (ej. 60vh, 70vh) según tu preferencia. */
  max-height: 65vh;
  /* Si el contenido excede esta altura, muestra una barra de scroll vertical. */
  overflow-y: auto;
  /* Un pequeño padding a la derecha para que el scroll no se pegue al contenido. */
  padding: 4px;
}

.v-list-item {
  cursor: pointer;
}
.v-list-item:not(:last-child) {
    border-bottom: 1px solid #f0f0f0;
}
.v-list-item:hover {
  background-color: #f9f9f9;
}
</style>
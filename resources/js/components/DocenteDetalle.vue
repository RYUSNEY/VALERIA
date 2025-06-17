<template>
    <v-container fluid class="pa-2">
        <v-card class="mb-4 pt-4 px-4 elevation-2">
            <v-row dense no-gutters>
                <!-- Select de Tópico -->
                <v-col cols="12" sm="5" class="pr-2">
                <v-select
                    :items="filtros.topicos"
                    item-title="nombre"
                    item-value="id"
                    label="Filtrar por tópico"
                    v-model="topico"
                    @update:modelValue="filtrar"
                    clearable
                    density="comfortable"
                    variant="outlined"
                    class="rounded"
                />
                </v-col>

                <!-- Select de Año -->
                <v-col cols="12" sm="5" class="px-2">
                <v-select
                    :items="filtros.años"
                    label="Filtrar por año"
                    v-model="año"
                    @update:modelValue="filtrar"
                    clearable
                    density="comfortable"
                    variant="outlined"
                    class="rounded"
                />
                </v-col>

                <!-- Botón Limpiar -->
                <v-col cols="12" sm="2">
                <v-btn
                    @click="limpiarFiltros"
                    color="red-darken-2"
                    block
                    class="rounded"
                    prepend-icon="mdi-broom"
                >
                    Limpiar
                </v-btn>
                </v-col>
            </v-row>
        </v-card>



        <v-row class="mb-4" dense>
            <!-- Cursos del VRI -->
            <v-col cols="12" sm="6">
                <v-card class="scrollable-card">
                    <v-card-title class="text-h6 text-center font-weight-bold">
                                        📘 VRI - {{ detalle.nombre_completo || 'Selecciona un docente' }}
                                    </v-card-title>
                    <v-divider />
                    <v-card-text class="scrollable-content">
                        <v-list density="compact" lines="one">
                        <v-list-item
                            v-for="item in detalle.vri"
                            :key="item.id_cert"
                        >
                            <v-list-item-title>
                            <a :href="`https://vriunap.pe/cursos/verifica/${item.codigo}`" class="text-body-2 font-weight-medium" target="_blank">{{ item.curso?.nombre }}</a>
                            </v-list-item-title>
                        </v-list-item>
                        </v-list>
                    </v-card-text>
                </v-card>
            </v-col>

            <!-- Cursos del VRA -->
            <v-col cols="12" sm="6">
                <v-card class="scrollable-card">
                    <v-card-title class="text-h6 text-center font-weight-bold">
                        📗 VRA - {{ detalle.nombre_completo || 'Selecciona un docente' }}
                    </v-card-title>
                    <v-divider />
                    <v-card-text class="scrollable-content">
                        <v-list density="compact" lines="one">
                            <v-list-item
                                v-for="item in detalle.vra"
                                :key="item.id_cert"
                            >
                                <v-list-item-title>
                                <a :href="item.enlace" class="text-body-2 font-weight-medium"  target="_blank">{{ item.curso?.nombre }}</a>
                                </v-list-item-title>
                            </v-list-item>
                        </v-list>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script setup>
    import { ref, watch } from 'vue'

    const props = defineProps(['detalle', 'filtros'])
    const emit = defineEmits(['filtrar'])

    const topico = ref(null)
    const año = ref(null)
    const dni = ref(null)

    watch(() => props.detalle, (val) => {
    if (val?.vra?.[0]) dni.value = val.vra[0].dni_usuario
    if (val?.vri?.[0]) dni.value = val.vri[0].dni_usuario
    })

    // Emitir filtros en tiempo real
    function filtrar() {
    if (dni.value) {
        emit('filtrar', {
        dni: dni.value,
        año: año.value || null,
        topico: topico.value || null
        })
    }
    }

    // Limpiar filtros
    function limpiarFiltros() {
    topico.value = null
    año.value = null
    filtrar()
    }
</script>

<style scoped>
.scrollable-card {
    height: 100%;
    display: flex;
    flex-direction: column;
}
.scrollable-content {
    max-height: 45vh;
    overflow-y: auto;
    padding-right: 4px;
}
</style>


<template>
  <v-container fluid>
    <v-row justify="center">
      <v-col cols="12" md="8" lg="6">
        <v-card class="pa-6">
          <v-card-title class="text-h4 text-center mb-6">
            <v-icon size="large" class="mr-2">mdi-magnify</v-icon>
            Búsqueda de Certificados
          </v-card-title>
          
          <v-card-text>
            <v-form @submit.prevent="iniciarBusqueda">
              <v-text-field
                v-model="dni"
                label="DNI"
                placeholder="Ingrese el DNI a buscar"
                variant="outlined"
                :rules="[v => !!v || 'El DNI es requerido', v => /^\d{8}$/.test(v) || 'El DNI debe tener 8 dígitos']"
                clearable
                prepend-inner-icon="mdi-account"
                class="mb-4"
              ></v-text-field>
              
              <v-btn
                type="submit"
                color="primary"
                size="large"
                block
                :loading="polling"
                :disabled="!dni || polling"
                prepend-icon="mdi-magnify"
              >
                {{ polling ? 'Buscando...' : 'Buscar Certificados' }}
              </v-btn>
              <v-progress-circular v-if="polling" indeterminate color="primary" class="mt-4" />
            </v-form>
          </v-card-text>
        </v-card>

        <!-- Resultados -->
        <v-card v-if="resultados" class="mt-6">
          <v-card-title class="text-h5">
            <v-icon class="mr-2">mdi-file-certificate</v-icon>
            Resultados para DNI: {{ dni }}
          </v-card-title>
          
          <v-card-text>
            <!-- Certificados VRA -->
            <v-expansion-panels class="mb-4">
              <v-expansion-panel>
                <v-expansion-panel-title>
                  <v-icon class="mr-2">mdi-school</v-icon>
                  Certificados VRA ({{ resultados.vra?.certificados?.length || 0 }})
                </v-expansion-panel-title>
                <v-expansion-panel-text>
                  <div v-if="resultados.vra?.encontrado && resultados.vra?.certificados?.length > 0">
                    <v-list>
                      <v-list-item
                        v-for="(certificado, index) in resultados.vra.certificados"
                        :key="index"
                        class="mb-2"
                      >
                        <v-list-item-title class="font-weight-bold">
                          {{ certificado.nombre }}
                        </v-list-item-title>
                        <v-list-item-subtitle>
                          <v-chip size="small" class="mr-2">
                            Código: {{ certificado.codigo }}
                          </v-chip>
                          <v-chip 
                            size="small" 
                            :color="certificado.estado === 'Aprobado' ? 'success' : 'warning'"
                          >
                            {{ certificado.estado }}
                          </v-chip>
                        </v-list-item-subtitle>
                        <template v-slot:append>
                          <v-btn
                            v-if="certificado.enlace && certificado.enlace !== 'No disponible'"
                            size="small"
                            color="info"
                            :href="certificado.enlace"
                            target="_blank"
                            prepend-icon="mdi-eye"
                          >
                            Ver
                          </v-btn>
                        </template>
                      </v-list-item>
                    </v-list>
                  </div>
                  <div v-else class="text-center pa-4">
                    <v-icon size="large" color="grey">mdi-file-remove</v-icon>
                    <p class="text-grey mt-2">No se encontraron certificados VRA</p>
                  </div>
                </v-expansion-panel-text>
              </v-expansion-panel>
            </v-expansion-panels>

            <!-- Certificados VRI -->
            <v-expansion-panels>
              <v-expansion-panel>
                <v-expansion-panel-title>
                  <v-icon class="mr-2">mdi-book-open-variant</v-icon>
                  Certificados VRI ({{ resultados.vri?.certificados?.length || 0 }})
                </v-expansion-panel-title>
                <v-expansion-panel-text>
                  <div v-if="resultados.vri?.encontrado && resultados.vri?.certificados?.length > 0">
                    <v-list>
                      <v-list-item
                        v-for="(certificado, index) in resultados.vri.certificados"
                        :key="index"
                        class="mb-2"
                      >
                        <v-list-item-title class="font-weight-bold">
                          {{ certificado.nombre_curso }}
                        </v-list-item-title>
                        <v-list-item-subtitle>
                          <v-chip size="small" class="mr-2">
                            {{ certificado.tipo }}
                          </v-chip>
                          <v-chip size="small" color="primary">
                            Código: {{ certificado.codigo }}
                          </v-chip>
                        </v-list-item-subtitle>
                        <template v-slot:append>
                          <v-btn
                            v-if="certificado.enlace && certificado.enlace !== 'No disponible'"
                            size="small"
                            color="info"
                            :href="certificado.enlace"
                            target="_blank"
                            prepend-icon="mdi-eye"
                          >
                            Ver
                          </v-btn>
                        </template>
                      </v-list-item>
                    </v-list>
                  </div>
                  <div v-else class="text-center pa-4">
                    <v-icon size="large" color="grey">mdi-file-remove</v-icon>
                    <p class="text-grey mt-2">No se encontraron certificados VRI</p>
                  </div>
                </v-expansion-panel-text>
              </v-expansion-panel>
            </v-expansion-panels>
          </v-card-text>
        </v-card>

        <!-- Error -->
        <v-alert
          v-if="error"
          type="error"
          class="mt-4"
          closable
          @click:close="error = null"
        >
          {{ error }}
        </v-alert>
      </v-col>
    </v-row>
  </v-container>
</template>

<script setup>
import { ref, onUnmounted } from 'vue'
import axios from 'axios'

const dni = ref('')
const resultados = ref(null)
const polling = ref(false)
let pollingInterval = null

const iniciarBusqueda = async () => {
  resultados.value = null;   // Limpia resultados anteriores
  error.value = null;        // Limpia errores anteriores si tienes
  polling.value = true;      // Muestra el loader
  await axios.post('/api/iniciar-busqueda', { dni: dni.value });
  startPolling();
};

const startPolling = () => {
  pollingInterval = setInterval(async () => {
    const response = await axios.get('/api/progreso-busqueda', { params: { dni: dni.value } })
    resultados.value = response.data
    // Detener polling si ambos resultados están listos
    if (
      resultados.value &&
      resultados.value.vra && resultados.value.vri &&
      (resultados.value.vra.encontrado !== undefined && resultados.value.vri.encontrado !== undefined)
    ) {
      stopPolling()
    }
  }, 400)
}

const stopPolling = () => {
  clearInterval(pollingInterval)
  polling.value = false
}

onUnmounted(() => {
  stopPolling()
})
</script>

<style scoped>
.v-expansion-panels {
  border: 1px solid #e0e0e0;
  border-radius: 8px;
}
</style> 
<template>
    <v-container fluid class="pa-4">
        <v-row align="stretch">
            <!-- Card grande a la izquierda -->
            <v-col cols="12" md="7">
                <v-card class="pa-6 rounded-xl elevation-8 main-card-bg" height="100%">
                    <div class="d-flex align-center mb-4">
                        <v-avatar size="64" class="mr-4" color="deep-purple-lighten-4">
                            <v-icon size="48" color="deep-purple">mdi-school</v-icon>
                        </v-avatar>
                        <div>
                            <div class="text-h5 font-weight-bold">¡Bienvenido/a a VALERIA!</div>
                            <div class="text-subtitle-2 text-grey-darken-1">Tu espacio para la innovación educativa</div>
                        </div>
                    </div>
                    <v-divider class="mb-4"></v-divider>
                    <v-card-text class="text-body-1 mb-4">
                        Verificación de Aprendizaje y Logros Educativos para la Ruta de la Innovación y Acreditación<br><br>
                        VALERIA es una plataforma desarrollada con el objetivo de brindar una visión clara y organizada del nivel de capacitación del cuerpo docente, permitiendo identificar rápidamente quiénes cuentan con certificaciones en áreas clave y quiénes aún no han participado en dichas formaciones.<br><br>
                        A través de filtros por año y tópico, gráficos estadísticos y listados detallados, esta herramienta facilita la toma de decisiones estratégicas en el proceso de acreditación institucional, promoviendo la mejora continua en la calidad educativa.<br><br>
                        Además, VALERIA permite reconocer a los docentes más activos en su desarrollo profesional y detectar oportunidades de capacitación para fortalecer las competencias del equipo académico.
                    </v-card-text>
                    <div class="mb-4">
                        <v-chip class="ma-1" color="indigo" text-color="white">Innovación</v-chip>
                        <v-chip class="ma-1" color="green" text-color="white">Acreditación</v-chip>
                        <v-chip class="ma-1" color="deep-orange" text-color="white">Docentes</v-chip>
                        <v-chip class="ma-1" color="blue" text-color="white">Calidad Educativa</v-chip>
                    </div>
                    <v-divider class="my-4"></v-divider>
                    <div class="text-subtitle-1 font-italic text-center text-grey-darken-2">
                        "La educación es el arma más poderosa que puedes usar para cambiar el mundo."<br>
                        <span class="text-caption">- Nelson Mandela</span>
                    </div>
                </v-card>
            </v-col>

            <!-- Cards pequeños a la derecha -->
            <v-col cols="12" md="5">
                <v-row class="h-100" align="stretch">
                    <v-col cols="12" sm="6" class="d-flex">
                        <v-card class="rounded-xl summary-card flex-grow-1 d-flex flex-column justify-center align-center mb-4" height="100%">
                            <v-card-text class="d-flex flex-column align-center py-8">
                                <v-icon size="56" color="primary" class="mb-3">mdi-certificate-star</v-icon>
                                <div class="text-h3 font-weight-bold">{{ resumen.total_certificados }}</div>
                                <div class="text-subtitle-1 text-grey mt-1">Total Certificados</div>
                            </v-card-text>
                        </v-card>
                    </v-col>
                    <v-col cols="12" sm="6" class="d-flex">
                        <v-card class="rounded-xl summary-card flex-grow-1 d-flex flex-column justify-center align-center mb-4" height="100%">
                            <v-card-text class="d-flex flex-column align-center py-8">
                                <v-icon size="56" color="success" class="mb-3">mdi-account-group</v-icon>
                                <div class="text-h3 font-weight-bold">{{ resumen.total_docentes }}</div>
                                <div class="text-subtitle-1 text-grey mt-1">Docentes Registrados</div>
                            </v-card-text>
                        </v-card>
                    </v-col>
                    <v-col cols="12" sm="6" class="d-flex">
                        <v-card class="rounded-xl summary-card flex-grow-1 d-flex flex-column justify-center align-center" height="100%">
                            <v-card-text class="d-flex flex-column align-center py-8">
                                <v-icon size="56" color="info" class="mb-3">mdi-book-open-variant</v-icon>
                                <div class="text-h3 font-weight-bold">{{ resumen.total_cursos }}</div>
                                <div class="text-subtitle-1 text-grey mt-1">Total de Capacitaciones</div>
                            </v-card-text>
                        </v-card>
                    </v-col>
                    <v-col cols="12" sm="6" class="d-flex">
                        <v-card class="rounded-xl summary-card flex-grow-1 d-flex flex-column justify-center align-center" height="100%">
                            <v-card-text class="d-flex flex-column align-center py-8">
                                <v-icon size="56" color="warning" class="mb-3">mdi-chart-line</v-icon>
                                <div class="text-h3 font-weight-bold">{{ resumen.promedio_por_docente }}</div>
                                <div class="text-subtitle-1 text-grey mt-1">Promedio por Docente</div>
                            </v-card-text>
                        </v-card>
                    </v-col>
                </v-row>
            </v-col>
        </v-row>
    </v-container>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const resumen = ref({
  total_certificados: 0,
  total_docentes: 0,
  total_cursos: 0,
  promedio_por_docente: 0
});

onMounted(async () => {
  const response = await fetch('/api/inicio/resumen');
  if (response.ok) {
    resumen.value = await response.json();
  }
});
</script>

<style scoped>
.v-card {
    transition: transform 0.2s;
}

.v-card:hover {
    transform: translateY(-5px);
}

.v-list-item {
    border-bottom: 1px solid rgba(0, 0, 0, 0.12);
}

.v-list-item:last-child {
    border-bottom: none;
}

.v-btn {
    text-transform: none;
}

.main-card-bg {
    background: linear-gradient(135deg,rgb(255, 255, 255) 0%, #e3f2fd 100%);
}

.summary-card {
    min-height: 180px;
    box-shadow: 0 2px 8px rgba(60,60,60,0.07);
}

@media (min-width: 960px) {
    .summary-card {
        min-height: 200px;
    }
}
</style>

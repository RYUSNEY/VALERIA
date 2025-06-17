<template>
    <v-card elevation="2" class="fill-height scrollable-content p-4">
        <v-list density="compact" lines="one">
            <v-list-item
                v-for="d in docentes"
                :key="d.id"
                @click="$emit('seleccionar', d.dni)"
                class="cursor-pointer"
            >
                <v-list-item-content>
                    <v-list-item-title class="text-body-2 font-weight-medium">
                    {{ d.nombres }} {{ d.ap_paterno }} {{ d.ap_materno }}
                    </v-list-item-title>
                    <v-list-item-subtitle class="text-caption text-grey">
                    {{ d.certificados_vra_count + d.certificados_vri_count }} certificados
                    </v-list-item-subtitle>
                </v-list-item-content>
            </v-list-item>
        </v-list>
    </v-card>
</template>

<script setup>
    defineProps(['docentes'])
</script>

<style scoped>
    .scrollable-content {
        max-height: 80vh;
        overflow-y: auto;
        padding-right: 4px;
    }
    .cursor-pointer {
        cursor: pointer;
    }
</style>

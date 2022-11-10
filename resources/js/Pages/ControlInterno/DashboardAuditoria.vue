<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import ButtonSeccion from '../../Components/ButtonSeccion.vue';
import Title from '../../Components/Title.vue';
import GraficaSection from './Partials/GraficaSection.vue';
let props = defineProps({
    departamentosAuditoria: {
        type: Object,
        required: true,
    }
});

</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <Title>Control Interno</Title>
        </template>
        <div class="py-6 max-w-7xl sm:px-6 md:px-8">
            <!-- Departamentos Section -->

            <div class="flex flex-wrap justify-center gap-2 py-6 lg:px-40">
                <ButtonSeccion @click="$emit('selectDepartamento',)" class="h-24 w-36"
                    v-for="dep in departamentosAuditoria" :key="'dep' + dep.id">
                    <div class="flex flex-col items-center justify-between w-full" style="justify-items:center;">
                        <img :src="dep.logo" class="h-14" />
                        <p class="mt-1">{{ dep.nombre }}</p>
                    </div>
                </ButtonSeccion>
            </div>
            <!-- End Departamentos -->
            <GraficaSection class="sm:px-6" />
        </div>



    </AppLayout>
</template>

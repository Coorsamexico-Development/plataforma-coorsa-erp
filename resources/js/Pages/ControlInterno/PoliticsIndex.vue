<script setup>

import AppLayout from '@/Layouts/AppLayout.vue';
import Title from '../../Components/Title.vue';

let props = defineProps({

});





</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <div class="md:flex">
                <div class="lg:w-1/4">
                    <h1 class="text-2xl font-bold text-[#CB4060]">DOCUMENTOS</h1>
                </div>
                <div class="flex flex-col items-center lg:w-2/4 md:pt-6">

                    <Title class="mb-2">¿Cual es nuestro objetivo como área?</Title>
                    <p class="text-center text-gray-500">
                        Dentro de Control Interno, nos encargamos de la creación y seguimiento del cumplimiento de los
                        procesos, políticas, manuales, normas, y métodos estratégicos, de la empresa todo con la
                        finalidad de llegar al plan estratégico de esta
                    </p>
                </div>
            </div>
        </template>
        <div class="py-6 max-w-7xl sm:px-6 md:px-8">
            <!-- Departamentos Section -->

            <div class="flex flex-wrap justify-center gap-2 py-6 lg:px-40">


                <!-- <ButtonSeccion v-for="dep in departamentosAuditoria" :key="'dep' + dep.id" class="h-24 w-36"
                    @click="params.departamento_auditoria_id = dep.id">
                    <div class="flex flex-col items-center justify-between w-full" style="justify-items:center;">
                        <img :src="dep.logo" class="h-14" />
                        <p class="mt-1">{{ dep.nombre }}</p>
                    </div>
                </ButtonSeccion> -->
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed, reactive, watch } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import ButtonAdd from '../../Components/ButtonAdd.vue';
import ButtonSeccion from '../../Components/ButtonSeccion.vue';
import Title from '../../Components/Title.vue';
import GraficaSection from './Partials/GraficaSection.vue';
import FormCalificacionModal from './Modals/FormCalificacionModal.vue';
import { Link } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';
let props = defineProps({
    departamentosAuditoria: {
        type: Object,
        required: true,
    },
    filters: {
        type: Object,
        required: true,
    }
});


const showingFormCalificacion = ref(false);
const params = reactive({
    departamento_auditoria_id: props.filters.departamento_auditoria_id
})


const departamento = computed(() => {
    return props.departamentosAuditoria.find((dep) => dep.id == params.departamento_auditoria_id)
})

watch(params, (newParams) => {

    Inertia.visit(route("departamentos-aditorias.index"),
        {
            data: newParams,
            replace: true,
            only: ['archivos'],
            preserveScroll: true,
            preserveState: true,
        });
})


</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <Title>Control Interno</Title>
        </template>
        <div class="py-6 max-w-7xl sm:px-6 md:px-8">
            <!-- Departamentos Section -->

            <div class="flex flex-wrap justify-center gap-2 py-6 lg:px-40">


                <ButtonSeccion v-for="dep in departamentosAuditoria" :key="'dep' + dep.id" class="h-24 w-36"
                    @click="params.departamento_auditoria_id = dep.id">
                    <div class="flex flex-col items-center justify-between w-full" style="justify-items:center;">
                        <img :src="dep.logo" class="h-14" />
                        <p class="mt-1">{{ dep.nombre }}</p>
                    </div>
                </ButtonSeccion>
            </div>
            <!-- End Departamentos -->
            <GraficaSection class="sm:px-6">
                <ButtonAdd v-if="departamento != undefined" @click="showingFormCalificacion = true">
                    + AGREGAR
                </ButtonAdd>
            </GraficaSection>
        </div>
        <!-- Modal Form Calificaciones -->
        <FormCalificacionModal v-if="departamento != undefined" :departamento="departamento"
            :show="showingFormCalificacion" @close="showingFormCalificacion = false" />


    </AppLayout>
</template>

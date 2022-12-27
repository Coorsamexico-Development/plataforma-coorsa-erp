<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import DataTable from '../../Components/DataTable.vue';
import PlantillaAutorizadaRow from './Partials/PlantillaAutorizadaRow.vue';

var props = defineProps({
    puestos: {
        type: Object,
        required: true,
    },
    ubicaciones: {
        type: Object,
        required: true,
    }
});



</script>

<template>
    <AppLayout title="Plantillas">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Plantillas Autorizadas
            </h2>
        </template>
        <div class="py-6">
            <div class="mx-auto sm:px-3 lg:px-8 ">
                <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg">
                    <DataTable>
                        <template #table-header>

                            <tr class="text-center">
                                <th scope="col"
                                    class="px-2 py-1 text-xs font-semibold tracking-wider uppercase cursor-pointer ">
                                </th>
                                <th scope="col" v-for="puesto in puestos" :key="puesto.id"
                                    class="px-2 py-1 text-xs font-semibold tracking-wider uppercase cursor-pointer ">
                                    {{ puesto.name }}
                                </th>
                            </tr>
                        </template>
                        <template #table-body>
                            <PlantillaAutorizadaRow v-for="ubicacion in ubicaciones" :key="'u' + ubicacion.id"
                                :ubicacion="ubicacion" :puestos="puestos" />
                        </template>
                    </DataTable>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import DataTable from '@/Components/DataTable.vue';
import ButtonInfo from '@/Components/Buttoninfo.vue';
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
var props = defineProps(
    { empleados: Object }
);


</script>

<template>
    <DataTable>
        <template #table-header>
            <tr class="text-center">
                <th scope="col" class="w-1/12 px-6 py-3 text-xs font-semibold tracking-wider uppercase cursor-pointer ">
                    <span class="">
                        EXPEDIENTE
                    </span>
                </th>
                <th scope="col" class="w-1/12 px-6 py-3 text-xs font-semibold tracking-wider uppercase cursor-pointer ">
                    <span class="">
                        NO. EMPLEADO
                    </span>
                </th>
                <th scope="col" class="w-1/12 px-6 py-3 text-xs font-semibold tracking-wider uppercase cursor-pointer ">
                    <span class="">
                        NOMBRE
                    </span>
                </th>
                <th scope="col" class="w-1/12 px-6 py-3 text-xs font-semibold tracking-wider uppercase cursor-pointer ">
                    <span class="">
                        APELLIDO PATERNO
                    </span>
                </th>
                <th scope="col" class="w-1/12 px-6 py-3 text-xs font-semibold tracking-wider uppercase cursor-pointer ">
                    <span class="">
                        APELLIDO MATERNO
                    </span>
                </th>
                <th scope="col" class="w-1/12 px-6 py-3 text-xs font-semibold tracking-wider uppercase cursor-pointer ">
                    <span class="">
                        TELÉFONO
                    </span>
                </th>
                <th scope="col" class="w-1/12 px-6 py-3 text-xs font-semibold tracking-wider uppercase cursor-pointer ">
                    <span class="">
                        ACCIONES
                    </span>
                </th>
            </tr>
        </template>
        <template #table-body>
            <tr v-for="empleado in empleados" :key="empleado.id">
                <td class="px-2 whitespace-nowrap"></td>
                <td class="px-2 whitespace-nowrap">{{ empleado.numero_empleado }}</td>
                <td class="px-2 whitespace-nowrap">{{ empleado.name }}</td>
                <td class="px-2 whitespace-nowrap">{{ empleado.apellido_paterno }}</td>
                <td class="px-2 whitespace-nowrap">{{ empleado.apellido_materno }}</td>
                <td class="px-2 whitespace-nowrap">{{ empleado.telefono }}</td>
                <td class="px-2 whitespace-nowrap">
                    <Link :href="route('empleado.edit', empleado.id)">
                    <ButtonInfo class="w-6 h-6 px-1" style="width:3rem; align-items: center; justify-content: center;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="white" class="w-4 h-4" viewBox="0 0 16 16">
                            <path
                                d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                            <path fill-rule="evenodd"
                                d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z" />
                        </svg>
                    </ButtonInfo>
                    </Link>
                </td>
            </tr>
        </template>
    </DataTable>
</template>

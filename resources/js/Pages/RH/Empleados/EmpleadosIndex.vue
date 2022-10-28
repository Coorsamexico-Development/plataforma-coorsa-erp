<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Inertia } from '@inertiajs/inertia';
import { reactive, watch } from 'vue';
import { throttle, pickBy } from 'lodash'
import TableEmpleados from './Partials/TableEmpleados.vue';
import InputSearch from '@/Components/InputSearch.vue';
import ButtonAdd from '../../../Components/ButtonAdd.vue';
import ButtonInfo from '@/Components/ButtonInfo.vue';

var props = defineProps(
    {empleados:Object,
     activo:String,
     filters:Object
    }
);

const params = reactive({
    search: props.filters.search
})

watch(params, (newValue) => 
{
   console.log(newValue)
   Inertia.get(route("empleado.indexmanual",{activo:props.activo}), newValue, 
   {
        replace: true,
        preserveScroll: true,
        preserveState: true,
    });
})

</script>

<template>
    <app-layout title="Dashboard">
        <template #header>
            {{filters}}
            <h2 v-if="activo === 'activo'"  class="text-xl font-semibold leading-tight text-gray-800">
              Empleados activos
            </h2>
            <h2 v-if="activo === 'inactivo'" class="text-xl font-semibold leading-tight text-gray-800">
              Empleados inactivos
            </h2>
            <ButtonInfo style="float:right; margin-top: -1.5rem; ">REPORTE</ButtonInfo>
            <ButtonAdd style="float:right; margin-top: -1.5rem; margin-right: 2rem; " >
                + AGREGAR
            </ButtonAdd>
            <InputSearch v-model="params.search" style="float:right; margin-top: -2rem; margin-right: 12rem;" ></InputSearch>
          
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg">
                   <TableEmpleados :empleados="empleados"></TableEmpleados>
                </div>
            </div>
        </div>
    </app-layout>
</template>


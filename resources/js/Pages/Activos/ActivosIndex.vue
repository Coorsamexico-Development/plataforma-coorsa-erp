<script setup>
import { computed, reactive, watch } from 'vue';
import { ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Inertia } from '@inertiajs/inertia';
import { throttle, pickBy } from 'lodash'
import InputSearch from '@/Components/InputSearch.vue';
import ButtonInfo from '@/Components/Buttoninfo.vue';
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import ButtonAdd from '../../Components/ButtonAdd.vue';
import ActivoConteo from '../Activos/Partials/ActivoConteo.vue';
import ModalAddCategoria from './Partials/ModalAddCategoria.vue';
import { prop } from 'dom7';

var props = defineProps(
    {
        tipo_activos:Object,
        tipo_inputs:Object,
        tipo_evidencias:Object,
        filters: {
           type: Object,
           required: true,
       },
    }
);

/*Comentario*/
const params = reactive({
    search: '',
})


watch(params, (newParams) => {
    Inertia.visit(route("activo.index"),
        {
            data: pickBy(newParams),
            replace: true,
            only: ['tipo_activos'],
            preserveScroll: true,
            preserveState: true,
        });
});

const modalNewCategory = ref(false);

const openModalNewCategory = () => 
{
   modalNewCategory.value = true;
}

const closeModalNewCategory = () => 
{
    modalNewCategory.value = false;
}


</script>

<template>
    <AppLayout title="Activos">
       <div class="grid w-full grid-cols-5 grid-rows-3 gap-4 activo_banner h-96"> 
          <div class="col-end-3 mt-4">
            <h1 class="mt-2 text-3xl text-white" style="font-family: 'Montserrat';">Empresa</h1>
            <span class="absolute w-16 h-1 bg-[#EC2944] mt-4"></span>
         </div>
         <div class="col-start-2 col-end-5 pt-8">
            <p class="text-xl text-white" style="font-family: 'Montserrat';">En esta sección tendrás acceso a la información de los activos asignados.</p>
         </div>
       </div>
       <div class="grid grid-cols-5 mt-8"> <!--Seccion buscador-->
          <div class="flex justify-center col-start-5">
            <InputSearch class="" v-model="params.search" ></InputSearch>
          </div>
       </div>

       <div class="grid grid-cols-5 mt-8 mb-8">
          <h1 class="col-start-2 text-2xl font-semibold uppercase tracking-wide" style="font-family: 'Montserrat';">Activos</h1>
          <ButtonAdd class="flex justify-center col-start-4 rounded-none" @click="openModalNewCategory">
            <span class="text-base" style="letter-spacing:0.25rem" >Agregar categoría</span>
          </ButtonAdd>
          <ModalAddCategoria :tipo_inputs="tipo_inputs" :show="modalNewCategory" @close="closeModalNewCategory"></ModalAddCategoria>
       </div>
       <div class="grid grid-cols-7">
          <ActivoConteo v-for="tipoActivo in props.tipo_activos" :key="tipoActivo.id" :tipoActivo="tipoActivo" :tipo_evidencias="tipo_evidencias" :tipo_inputs="tipo_inputs"></ActivoConteo>
       </div>
    </AppLayout>
</template>


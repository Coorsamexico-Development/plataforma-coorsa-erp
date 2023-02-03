<script setup>
import { Inertia } from '@inertiajs/inertia';
import { computed, reactive, watch, ref } from 'vue';
import axios from 'axios';
import TableActivos from '../Partials/TableActivos.vue';
import ModalAddItem from '../Partials/ModalAddItem.vue';
import { value } from 'dom7';

var props = defineProps(
    {
        tipoActivo:Object,
        tipo_evidencias:Object,
    }
);

const visible = ref(false);
const activos = ref([]);


const emit = defineEmits(["axios" ])
const open = (id) => 
{
    visible.value = !visible.value ;
    
    emit('axios',id);
/*

    axios.get('/activosxtipo/'+id).then((response)=> 
    {
      //console.log(response);
      activos.value = response.data;
    });
    //console.log(visible.value);
    //console.log(props.tipoActivo.id);
    /*
    Inertia.visit(route("activo.index"),
        {
            data: {
                id:props.tipoActivo.id
            },
            replace: true,
            only: ['activos'],
            preserveScroll: true,
            preserveState: true,
        });}
    */
}
/*Funciones modal*/
const itemModal = ref(false);

const openItemModal = () => 
{
   itemModal.value = true;
}

const closeItemModal = () =>
{
    itemModal.value = false;
}

const axiosOpen = (id) =>
{
    visible.value = false;
    changeStatus(id);
}

const changeStatus = (id)  =>
{
    Inertia.post(route('changeStatusActivoItemLibre', id),{},{
      preserveScroll:true,
      preserveState:true,
      onSuccess: close()
    });
}
</script>
<template>
    <div class="col-start-2 col-end-7">
         <div @click="open(tipoActivo.id)" class="flex w-full p-4 space-x-8 overflow-hidden bg-white drop-shadow-xl sm:rounded-lg">
            <div class="flex p-4 space-x-8 w-96">
                <img alt="pc" :src="tipoActivo.imagen">
                <h1 class="text-xl" style="font-family: 'Montserrat';"> {{ tipoActivo.nombre }}</h1>
            </div>
            <div class="w-64">
            </div>
            <div class="w-64">
                <table class="" style="font-family: 'Montserrat';">
                    <thead>
                      <tr class="">
                        <th class="uppercase font-extralight">Uso</th>
                        <th class="uppercase font-extralight">Libres</th>
                        <th class="uppercase font-extralight">Baja</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="pr-4 text-center text-4xl border-r-8 border-[#EC2944]">{{ tipoActivo.totalUso[0].Uso }}</td>
                            <td class="pr-4 pl-4 text-center text-4xl border-r-8 border-[#EC2944]">{{ tipoActivo.totalLibre[0].Libre }}</td>
                            <td class="pl-4 text-4xl text-center">{{ tipoActivo.totalBaja[0].Baja }}</td>
                            <td class="pl-4">
                                <button @click="openItemModal" class="bg-[#EC2944] text-white rounded-full w-10">+</button>
                                <ModalAddItem :show="itemModal" :tipoActivo="tipoActivo" :campos="tipoActivo.camposAllInput" @close="closeItemModal"></ModalAddItem>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
         </div>
         <div class="w-full mt-2 mb-8"  :class="{ 'visible': visible, 'invisible': !visible }">
           <TableActivos :tipo_evidencias="tipo_evidencias" :activos="tipoActivo.activos_items" :campos="tipoActivo.camposInput" @axios="axiosOpen"></TableActivos>
        </div>
    </div>
</template>
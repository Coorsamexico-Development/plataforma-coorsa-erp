<script setup>
import { Link } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';
import { ref, computed, reactive, watch } from 'vue';
import { useForm } from "@inertiajs/inertia-vue3";
import InputText from './InputText.vue';
import { value } from 'dom7';

const props = defineProps({
    procesoId:Number,
    rubros:Array,
    calificaciones:Array
});

const newParametro = () => 
{
    //console.log(props.procesoId);
    const formParam = useForm({
       nombre:null ,
       proceso_id: props.procesoId,
   });

   formParam.post(route('storeRubro'),{
    preserveScroll:true,
    preserveState:true
   })
}

const meses = [
  {
    numero: '1',
    mes: 'Ene'
  },
  {
    numero: '2',
    mes: 'Feb'
  },
  {
    numero: '3',
    mes: 'Mar'
  },
  {
    numero: '4',
    mes: 'Abr'
  },
  {
    numero: '5',
    mes: 'May'
  },
  {
    numero: '6',
    mes: 'Jun'
  },
  {
    numero: '7',
    mes: 'Jul'
  },
  {
    numero: '8',
    mes: 'Ago'
  },
  {
    numero: '9',
    mes: 'Sep'
  },
  {
    numero: '10',
    mes: 'Oct'
  },
  {
    numero: '11',
    mes: 'Nov'
  },
  {
    numero: '12',
    mes: 'Dic'
  }
];

    
const updateForm = (campo, rubro, mes) =>
{
   let fecha = new Date();
   let year = fecha.getFullYear();
   const formSetCalf = useForm({
        rubro_id: rubro.id,
        valor:campo.value,
        mes:mes.numero,
        año:year
   });

   formSetCalf.post(route('storeCalf'),{
      preserveScroll:true,
      preserveState:true
   });

} 

const updateRubroName = (campo, rubro) =>
{
   const formActualizarRubro = useForm({
       nombre: campo.value ,
       proceso_id: props.procesoId,
   });

   formActualizarRubro.post(route('updateRubro', rubro.id),{
      preserveScroll:true,
      preserveState:true
   });
}

</script>
<template>
   <table class="w-full">
      <thead class="border-b">
        <tr class="">
            <th class="flex mb-2  border-r pr-2">
              <h1 class="mr-4">Parametros</h1>
              <button @click="newParametro" class="bg-[#EC2944] text-white pl-2 pr-2 rounded-full">
                 +
              </button>
            </th>
            <th class="pl-2 uppercase text-center m-2" v-for="mes in meses" :key="mes.numero">
                {{ mes.mes }}
            </th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(rubro, index) in rubros" :key="index">
            <td class="border-r pr-2 mr-2">
               <div class="text-center">
                    <InputText :valor="rubro.nombre" :rubro="rubro" @updateCampo="updateRubroName"></InputText>
               </div>
            </td>
            <td class="pl-2 border-t text-center" v-for="mes in meses" :key="mes.numero">
                <div v-for="calificacion in calificaciones" :key="calificacion.id">
                    <div v-if="calificacion.mes == mes.numero && calificacion.rubro == rubro.id">
                         <InputText :valor="calificacion.valor" :mes="mes" :rubro="rubro" @updateCampo="updateForm"></InputText>
                    </div>
               </div>
            </td>
        </tr>
      </tbody>
   </table>
</template>
<script setup>
import { Link } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';
import { ref, computed, reactive, watch } from 'vue';
import { useForm } from "@inertiajs/inertia-vue3";
import InputText from './InputText.vue';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'

const props = defineProps({
    procesoId:Number,
    rubros:Array,
    calificaciones:Array
});

const emit = defineEmits(["close", "reConsultarCalif"]);

const newParametro = () => 
{
  
    //console.log(props.procesoId);

    const formParam = useForm({
       nombre:null ,
       proceso_id: props.procesoId,
   });

   formParam.post(route('storeRubro'),{
    preserveScroll:true,
    preserveState:true,
    onSuccess:() => {reConsultarCalif()}
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


let year = ref(new Date().getFullYear());

const updateForm = (campo, rubro, mes) =>
{
  const formSetCalf = useForm({
        rubro_id: rubro.id,
        valor:campo.value,
        mes:mes.numero,
        año:year.value
   });

   formSetCalf.post(route('storeCalf'),{
      preserveScroll:true,
      preserveState:true,
   });
} 

const reConsultarCalif = () => 
{
   emit('reConsultarCalif');
   nuevoAño(year.value);
}

const updateRubroName = (campo, rubro) =>
{
   const formActualizarRubro = useForm({
       nombre: campo.value ,
       proceso_id: props.procesoId,
   });

   formActualizarRubro.post(route('updateRubro', rubro.id),{
      preserveScroll:true,
      preserveState:true,
      onSuccess:() => reConsultarCalif()
   });
}

let rubros2 = ref([]);
let calificaciones2 = ref(props.calificaciones);
const nuevoAño = (modelData) => 
{
   year.value = modelData;
   rubros2.value = [];
   calificaciones2.value = [];
   axios.get('/getRubros/'+props.procesoId+'/'+modelData).then((response)=> 
       {
        rubros2.value = response.data;

          for (let index = 0; index < rubros2.value.length; index++) 
          {
            const rubro = rubros2.value[index];
           // console.log(rubro);
            for (let index2 = 0; index2 < meses.length; index2++) 
              {
                  const mes = meses[index2];
                  let newInterseccion = {};
                  //console.log(mes);
                  newInterseccion.mes = mes.numero;
                  newInterseccion.rubro = rubro.id;
                  newInterseccion.valor= null;

                  calificaciones2.value.push(newInterseccion);
              }  
          }

          for (let index3 = 0; index3 < calificaciones2.value.length; index3++) 
          {
            const interseccion = calificaciones2.value[index3];
            //console.log(interseccion);
            for (let index4 = 0; index4 < rubros2.value.length; index4++) 
            {
                const rubro = rubros2.value[index4];
                for (let index5 = 0; index5 < rubro.calificaciones.length; index5++) 
                {
                    const calificaion = rubro.calificaciones[index5];
                    //console.log(calificaion);
                    if(calificaion.mes == interseccion.mes && calificaion.rubro_id == interseccion.rubro)
                    {
                        interseccion.valor = calificaion.valor;
                    } 
                }
            }
          }      
       });
}

</script>
<template>
   <VueDatePicker v-model="year" @update:model-value="nuevoAño" year-picker :teleport="true" />
   <table class="w-full">
      <thead class="border-b">
        <tr class="">
            <th class="flex pr-2 mb-2 border-r">
              <h1 class="mr-4">Parametros</h1>
              <button @click="newParametro" class="bg-[#EC2944] text-white pl-2 pr-2 rounded-full">
                 +
              </button>
            </th>
            <th class="pl-2 m-2 text-center uppercase" v-for="mes in meses" :key="mes.numero">
                {{ mes.mes }}
            </th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(rubro, index) in rubros" :key="index">
            <td class="pr-2 mr-2 border-r">
               <div class="text-center">
                    <InputText :valor="rubro.nombre" :rubro="rubro" @updateCampo="updateRubroName"></InputText>
               </div>
            </td>
            <td class="pl-2 text-center border-t" v-for="mes in meses" :key="mes.numero">
                <div v-for="calificacion in calificaciones2" :key="calificacion.id">
                    <div v-if="calificacion.mes == mes.numero && calificacion.rubro == rubro.id">
                         <InputText :valor="calificacion.valor" :mes="mes" :rubro="rubro" @updateCampo="updateForm"></InputText>
                    </div>
               </div>
            </td>
        </tr>
      </tbody>
   </table>
</template>
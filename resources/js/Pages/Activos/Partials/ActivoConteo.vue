<script setup>
import { Inertia } from '@inertiajs/inertia';
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import { computed, reactive, watch, ref } from 'vue';
import axios from 'axios';
import TableActivos from '../Partials/TableActivos.vue';
import { value } from 'dom7';

var props = defineProps(
    {
        tipoActivo:Object,
        tipo_evidencias:Object,
        tipo_inputs:Object
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

const addNewItem = () => 
{
    const formatDate = new Date();
    let dia = formatDate.getDate();
    let mes = (formatDate.getMonth() + 1);
    const año = formatDate.getFullYear();
    mes = mes < 10 ? '0' + mes : mes;
    dia = dia < 10 ? '0' + dia : dia;
    let mesToString =mes.toString();
    let diaToString = dia.toString();
    
    let cero = "0"; 
    let newMes = "";
    let newDia = "";
    let fechaCompleta = "";
    
    if(mesToString.length < 2)
      {
        //console.log(cero+mesToString)
        newMes =  cero+mesToString;
        if(diaToString.length < 2)
          {
            newDia = cero+diaToString;
            fechaCompleta = año+ '-' + newMes + '-' +newDia
          }
          else
          {
            fechaCompleta = año + '-' + newMes + '-' + dia; 
          }
      }
      else
      {
        if(diaToString.length < 2)
          {
            newDia = cero+diaToString;
            fechaCompleta = año+ '-' + mes + '-' +newDia
          }
           else
          {
            fechaCompleta = año + '-' + mes + '-' + dia; 
          } 
      }
    
    const ItemForm = useForm({
       tipo_activo:props.tipoActivo.id,
       fecha: fechaCompleta,
       status:true,
       status_activo_id:2
    });

    ItemForm.post(route('storeItem'),
    {
       onFinish: () => ItemForm.reset(),
       preserveScroll:true,
       preserveState:true
    });
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
    <div class="col-start-2 col-end-7 mt-2">
         <div @click="open(tipoActivo.id)" class="flex w-full p-4 space-x-8 overflow-hidden bg-white drop-shadow-xl sm:rounded-lg hover:border border-[#EC2944]">
            <div class="flex items-center p-4 space-x-8 w-96">
                <img alt="pc" class="w-28" :src="tipoActivo.imagen">
                <h1 class="text-3xl" style="font-family: 'Montserrat';"> {{ tipoActivo.nombre }}</h1>
            </div>
            <div class="w-64">
            </div>
            <div class="w-64" style="margin-right: 9rem;" >
                <table class="" style="font-family: 'Montserrat';">
                    <thead>
                      <tr class="">
                        <th class="pr-8 text-center uppercase font-extralight" style="letter-spacing:0.25rem" >Uso</th>
                        <th class="pl-8 pr-8 text-center uppercase font-extralight " style="letter-spacing:0.25rem" >Libres</th>
                        <th class="pl-8 uppercase font-extralight" style="letter-spacing:0.25rem" >Baja</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="pr-8 text-center text-5xl border-r-8 border-[#EC2944] font-semibold" >{{ tipoActivo.totalUso[0].Uso }}</td>
                            <td class="pr-8 pl-8 text-center text-5xl border-r-8 border-[#EC2944] font-semibold">{{ tipoActivo.totalLibre[0].Libre }}</td>
                            <td class="pl-8 text-5xl font-semibold text-center">{{ tipoActivo.totalBaja[0].Baja }}</td>
                            <Transition name="slide-fade2">
                              <td class="pl-0" v-if="visible">
                                <button @click="addNewItem" class="bg-[#EC2944] text-white rounded-full" :class="{ 'transition ease-in-out delay-150 w-10 visible': visible, ' transition ease-in-out delay-150 invisible h-0 w-0': !visible }">+</button>
                              </td>
                           </Transition>
                        </tr>
                    </tbody>
                </table>
            </div>
         </div>
         <Transition name="slide-fade">
            <div v-if="visible" class="w-full mt-2 mb-8">
              <TableActivos :tipo_inputs="tipo_inputs" :tipoActivo="tipoActivo"
               :tipo_evidencias="tipo_evidencias"
               :activos="tipoActivo.activos_items"
               :allcampos="tipoActivo.camposAllInput" 
               :campos="tipoActivo.camposInput" @axios="axiosOpen"></TableActivos>
           </div>
        </Transition>
    </div>
</template>
<style>
.slide-fade-enter-active {
  transition: all 0.3s ease-out;
}

.slide-fade-leave-active {
  transition: all 0.3s cubic-bezier(1, 0.5, 0.8, 1);
}

.slide-fade-enter-from,
.slide-fade-leave-to {
  transform: translatey(-20px);
  opacity: 0;
}

/**/
.slide-fade2-enter-active {
  transition: all 0.3s ease-out;
}

.slide-fade2-leave-active {
  transition: all 0.3s cubic-bezier(1, 0.5, 0.8, 1);
}

.slide-fade2-enter-from,
.slide-fade2-leave-to {
  transform: translatex(20px);
  opacity: 0;
}
</style>
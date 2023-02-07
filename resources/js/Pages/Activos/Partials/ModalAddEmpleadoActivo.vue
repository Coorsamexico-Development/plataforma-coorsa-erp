<script setup>
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import axios from 'axios';
import DialogModal from '@/Components/DialogModal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { usePage } from '@inertiajs/inertia-vue3'
import Checkbox from '@/Components/Checkbox.vue';
import SelectComponent from '@/Components/SelectComponent.vue';
import ButtonAdd from '@/Components/ButtonAdd.vue';
import ModalAddTipoEvidencia from '../Partials/ModalAddTipoEvidencia.vue';
import UploadFile from './UploadFile.vue';


var props = defineProps(
    {
       activo_id:Number
    }
);

const emit = defineEmits(["close", "axios" ])
const close = () => 
{      
   emit('close');
};

const emitAxios = () =>
{
    emit('axios');
}

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


const EmpleadoActivoForm = useForm({
   empleado_id: null,
   activo_item_id: props.activo_id,
   fechaAlta: fechaCompleta,
   activo:1,
   imagenes:null
});

const saveEmpleadoActivo = () => 
{
     EmpleadoActivoForm.post(route('EvidenciaActivoUser'),
    {
       onFinish: () => 
       {
         EmpleadoActivoForm.reset(),
         emitAxios();
       },
       onSuccess: () =>  close() 
    });
    
    
}

const modalTipoEvidencia = ref (false);
const openModalTipoEvidencia = () =>
{
   modalTipoEvidencia.value = true
}

const closeModalTipoEvidencia = () =>
{
   modalTipoEvidencia.value = false
}


let imagesArray = ref([]);
const setPhotoFiles = (fieldName, fileList) => 
{
   imagesArray.value = fileList;
   EmpleadoActivoForm.imagenes = imagesArray.value;
   //console.log(imagesArray);
}

const retornarFile = (file)  =>
{
   //console.log(file);
   EmpleadoActivoForm.imagen = file;
   //console.log(file);
}

const empleados  = ref ([]);
axios.get('/empleadosData').then((response)=> 
  {
      //console.log(response);
      empleados.value = response.data;
   });

const tipoEvidencias  = ref ([]);
axios.get('/getTipoEvidencia').then((response)=> 
  {
      //console.log(response);
      tipoEvidencias.value = response.data;
   });


</script>
<template>
         <DialogModal  @close="close()">
           <template #title>
               <h2 style="font-weight:bolder">Agregar empleado a activo.</h2>
            </template>
            <template #content>
                <div class="flex mb-4">
                        <div class="flex-initial w-64 m-2 ">
                           <InputLabel>Empleado</InputLabel>
                           <input v-model="EmpleadoActivoForm.empleado_id" list="lisEmpleados" class="w-full py-2 text-sm text-black bg-white border-2 border-black rounded-md shadow-sm focus:border-black focus:ring focus:ring-gray-200 focus:ring-opacity-50 disabled:bg-gray-300" >
                             <datalist id="lisEmpleados">
                             <option v-for="empleado in empleados" :key="empleado.id" :value="empleado.id">
                               {{ empleado.name + ' ' + empleado.apellido_paterno + ' ' +   empleado.apellido_materno  }} 
                             </option>
                           </datalist>
                        </div>
                        <div class="flex-initial w-64 m-2 ">
                          <InputLabel>Fecha de asignación</InputLabel>
                          <TextInput class="w-full" type="date" :required="true"  v-model="EmpleadoActivoForm.fechaAlta"></TextInput>
                        </div>
                </div>
                <div class="flex mb-4">
                    <div class="w-7/12">
                      <!--
                      <UploadFile @retornar="retornarFile" />
                      -->
                       <InputLabel>Evidencias</InputLabel>
                       <input type="file" multiple  @change="setPhotoFiles($event.target.name, $event.target.files)" />
                    </div>
                </div>
                <div class="flex flex-row-reverse">
                        <button @click="saveEmpleadoActivo" style="" class="p-2 bg-blue-500 rounded-lg hover:opacity-50">
                            <svg style="" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="30" height="30" viewBox="0 0 30 30">
                                <defs>
                                  <clipPath id="clip-Icono-guardar">
                                    <rect width="30" height="30"/>
                                  </clipPath>
                                </defs>
                                <g id="Icono-guardar" clip-path="url(#clip-Icono-guardar)">
                                  <rect width="30" height="30" fill="rgba(255,255,255,0)"/>
                                  <g id="Grupo_515" data-name="Grupo 515" transform="translate(-7685 -2974)">
                                    <g id="Grupo_512" data-name="Grupo 512" transform="translate(7689.417 2978.855)">
                                      <g id="Grupo_491" data-name="Grupo 491">
                                        <path id="Trazado_316" data-name="Trazado 316" d="M18.974,0H2.6A2.6,2.6,0,0,0,0,2.6v16.97a2.6,2.6,0,0,0,2.6,2.6h16.97a2.6,2.6,0,0,0,2.6-2.6V3.191ZM5.195,1.732h9.957V6.84H5.195Zm11.775,18.7H5.195v-6.84H16.97Zm3.463-.866a.867.867,0,0,1-.866.866H18.7V11.862H3.463v8.572H2.6a.867.867,0,0,1-.866-.866V2.6A.867.867,0,0,1,2.6,1.732h.866v6.84h13.42V1.732h1.373l2.177,2.177Z" fill="#fff"/>
                                      </g>
                                    </g>
                                  </g>
                                </g>
                            </svg>
                        </button>
                    </div>
            </template>
            
            <template #footer>
                <SecondaryButton  @click="close()" class="closeModal1" style="float:right">
                      Cerrar
                </SecondaryButton>
            </template>
        </DialogModal>
</template>

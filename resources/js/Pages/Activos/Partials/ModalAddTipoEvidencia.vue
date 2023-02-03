<script setup>
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import DialogModal from '@/Components/DialogModal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { usePage } from '@inertiajs/inertia-vue3'
import SelectComponent from '@/Components/SelectComponent.vue';
import Checkbox from '@/Components/Checkbox.vue';

var props = defineProps(
    {
       
    }
);

const emit = defineEmits(["close"])
const close = () => {
        
        emit('close');
    };



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


const tipoEvidenciaForm = useForm({
   nombre:null,
   fecha: fechaCompleta
});

const saveTipoEvidencia = () => 
{
    tipoEvidenciaForm.post(route('storeTipoEvidencia'),
    {
       onFinish: () => tipoEvidenciaForm.reset(),
       onSuccess: () =>  close() 
    });
}

</script>
<template>
         <DialogModal  @close="close()">
           <template #title>
               <h2 style="font-weight:bolder">Nuevo tipo de evidencia.</h2>
            </template>
            <template #content>
                <div>
                    <InputLabel>Nombre de tipo de evidencia.</InputLabel>
                    <TextInput type="text" v-model="tipoEvidenciaForm.nombre"></TextInput>
                </div>
                <div class="flex flex-row-reverse">
                        <button @click="saveTipoEvidencia" style="" class="p-2 bg-blue-500 rounded-lg hover:opacity-50">
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

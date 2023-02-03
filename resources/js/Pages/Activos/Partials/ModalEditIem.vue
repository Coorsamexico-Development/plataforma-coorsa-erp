<script setup>
import { ref, computed } from 'vue';
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import DialogModal from '@/Components/DialogModal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { usePage } from '@inertiajs/inertia-vue3'
import SelectComponent from '@/Components/SelectComponent.vue';
import Checkbox from '@/Components/Checkbox.vue';
import ButtonAdd from '@/Components/ButtonAdd.vue';
import { Inertia } from "@inertiajs/inertia";
import UploadFile from '../Partials/UploadFile.vue';
import ModalAddTipoEvidencia from '../Partials/ModalAddTipoEvidencia.vue';
import axios from 'axios';

const emit = defineEmits(["close"]);

var props = defineProps(
    {
        activo:Object,
        tipo_evidencias:Object
    }
);

const close = () => 
{
    emit('close');
};

const user = usePage().props.value.user;
const campos = ref([]);
const valores= ref([]);
const activoEditForm = useForm(
    {
        valores:valores.value,//valores para actualizar
        //valores evidencias_activos
        evidencia:null,
        nombre_evidencia:null,
        tipo_evidencia_id:null,
        user:user.id
    }
);


const totalEvidencias = ref([]);

const consultarInfo = () =>
{
    axios.get('/getAllCampos/'+props.activo.id).then((response)=> 
    {
      totalEvidencias.value = response.data[1];
      //console.log(response.data);
      for (let index = 0; index < response.data[0].length; index++) 
      {
        const element = response.data[0][index]; //tenemos todo el obj
        campos.value.push(
             {
                label: element.labelName,
                tipo_input: element.tipo_input,
                id_input: element.tipo_activo_campo_id
             }
        );

        valores.value.push(
           {
            valor:element.valor,
            tipoInputId: element.tipoCampoValor
           }
        );
      }
      
    });
}

const show = ref(false);
const showForm = () => 
{
    show.value = !show.value;
}

const retornarFile = (file) =>
{ 
   activoEditForm.evidencia = file;
}

const createOrUpdate = () => 
{
   //console.log("hola");
   activoEditForm.post(route('saveEditCampos', props.activo.id),
   {
       onFinish: () => activoEditForm.reset(),
       onSuccess: () =>  close() 
   });
}

const modalTipoEvidencia = ref(false);
const addTipoEvidencia = () => 
{
   modalTipoEvidencia.value = true;
}

const closeTipoEvidencia = () => 
{
    modalTipoEvidencia.value = false;
}

</script>
<template>
         <DialogModal  @close="close()">
           <template #title>
               <h2 style="font-weight:bolder">Editar Activo</h2>
            </template>
            <template #content>
                <ButtonAdd @click="consultarInfo">Consultar información</ButtonAdd>
                <div class="grid grid-cols-2 mt-4 gap-4 w-82" v-if="campos.length>0">
                    <div class="mr-4 row-start-auto row-end-auto" v-for="campo in campos" :key="campo.id">
                        <InputLabel>{{ campo.label }}</InputLabel>
                        <div v-for="valor in valores" :key="valor.id">
                             <div v-if="valor.tipoInputId == campo.id_input">
                                 <TextInput :type="campo.tipo_input" v-model="valor.valor" :value="valor.valor"></TextInput>
                             </div>
                        </div>
                    </div>
                </div>
               <div class="mt-8">
                  <div>
                     <h1>Evidencias</h1>
                     <ButtonAdd @click="showForm">Subir Evidencias</ButtonAdd>
                  </div>
                  <div v-if="show">
                     <div class="mt-2 flex justify-center" >
                        <UploadFile @retornar="retornarFile"/>
                     </div>
                     <div class="flex flex-row justify-center">
                       <div class="mr-4">
                           <InputLabel>Nombre de evidencia:</InputLabel>
                           <TextInput type="text" v-model="activoEditForm.nombre_evidencia"></TextInput>
                       </div>
                       <div>
                           <InputLabel class="">Tipo de evidencia:</InputLabel>
                           <SelectComponent v-model="activoEditForm.tipo_evidencia_id">
                               <option :selected="true" disabled>Selecciona un tipo de evidencia</option>
                               <option v-for="tipoevidencia in totalEvidencias" :value="tipoevidencia.id" :key="tipoevidencia.id">
                                   {{ tipoevidencia.nombre }}
                               </option>
                           </SelectComponent>
                           <ButtonAdd @click="addTipoEvidencia" class="ml-2">+</ButtonAdd>
                           <ModalAddTipoEvidencia :show="modalTipoEvidencia" @close="closeTipoEvidencia" />
                       </div>
                     </div>
                  </div>
               </div>
               <div class="flex justify-end mt-4">
                   <button @click="createOrUpdate" class="bg-[#00CB83] pl-2 pr-2 rounded-full" v-if="campos.length>0">
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

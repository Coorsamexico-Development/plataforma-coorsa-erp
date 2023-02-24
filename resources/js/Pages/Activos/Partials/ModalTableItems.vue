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
import ButtonAdd from '@/Components/ButtonAdd.vue';
import TableCampos from '../Partials/TableCampos.vue'
import { Inertia } from "@inertiajs/inertia";
import axios from 'axios';

const emit = defineEmits(["close", "axios"]);

var props = defineProps(
    {
        tipo_inputs:Object,
        tipoActivo:Number,
        campoName:String,
        idCampo:Number,
        activo_id:Number,
    }
);

const close = () => 
{
    emit('close');
};

const idCampoReactive = ref(-1);

const newColumn = useForm(
    {
        id:null,
        tipo_activo_id:props.tipoActivo,
        campo:null,
        principal:0,
        tipo_input_id:-1,
        tabla_id: props.idCampo
    }
)

const saveColum = (typeForm) => 
{
  if(typeForm == 'create')
  {
    newColumn.post(route('storeColum'),{
        preserveScroll:true,
        preserveState:true,
        onSuccess:() => newColumn.reset(),
    })
  }
  if(typeForm == 'update')
  {
    newColumn.post(route('updateColum'),{
        preserveScroll:true,
        preserveState:true,
        onSuccess:() => newColumn.reset(),
    })
  }
}

const colums = ref([]);
axios.get('/getCampos/'+props.idCampo+'/'+props.tipoActivo).then((response)=> 
     {
        //console.log(response);
        colums.value = response.data;
     });

//console.log(props);

const typeForm = ref('create');

const editarColum = (columna) => 
{
   //console.log(columna)
   newColumn.id = columna.id
   newColumn.campo = columna.campo;
   newColumn.tipo_input_id = columna.input_id;
   typeForm.value = 'update';
}

</script>
<template>
         <DialogModal :maxWidth="'5xl'"  @close="close()">
           <template #title>
               <h2 class="text-2xl text-center" style="font-weight:bolder">Campos en <span class="bg-[#EC2944] text-white p-1 rounded-xl">{{campoName}}</span></h2>
            </template>
            <template #content>
              <div class="grid grid-cols-5">
                 <div class="col-start-1 col-end-1">
                    Nueva columna
                        <div>
                            <InputLabel>Columna</InputLabel>
                            <TextInput v-model="newColumn.campo" />
                        </div>
                        <div>
                            <InputLabel>Tipo de columna</InputLabel>
                            <SelectComponent v-model="newColumn.tipo_input_id">
                                <option :disabled="true">Seleccina un tipo de dato</option>
                                <option v-for="tipoInput in tipo_inputs" :value="tipoInput.id" :key="tipoInput.id">
                                     {{ tipoInput.nombre }}
                                </option>
                            </SelectComponent>
                        </div>
                        <div>
                          <button v-if="typeForm ==='update'" @click="newColumn.reset(), typeForm='create'">Atrás</button>
                          <button @click="saveColum(typeForm)" style="" class="p-2 mt-2 ml-2 bg-blue-500 rounded-lg hover:opacity-50">
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
                 </div>
                 
                 <div class="col-start-3 col-end-6 border-l-2">
                    <TableCampos :tipo_inputs="tipo_inputs" :tipoActivo="tipoActivo" :columns="colums" :activoId="activo_id" @editColum="editarColum" />
                 </div>
              </div>
            </template>
            <template #footer>
                <SecondaryButton  @click="close()" class="closeModal1" style="float:right">
                      Cerrar
                </SecondaryButton>
            </template>
        </DialogModal>
</template>

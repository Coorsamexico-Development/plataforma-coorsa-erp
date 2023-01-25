<script setup>
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import DialogModal from '@/Components/DialogModal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { usePage } from '@inertiajs/inertia-vue3'
import ButtonAdd from '../../../Components/ButtonAdd.vue';
import SelectComponent from '@/Components/SelectComponent.vue';
import Checkbox from '@/Components/Checkbox.vue';
import UploadFile from '../Partials/UploadFile.vue'


var props = defineProps(
    {
        tipo_inputs:Object
    }
);


const emit = defineEmits(["close"])
const close = () => {
        
        emit('close');
    };

const CantidadCampos = ref([]);

const addCampo = () => 
{
    CantidadCampos.value.push({
        nombre:null,
        tipo_input_id:-1
    });
}

const CategoryForm = useForm({
   nombre:null,
   imagen:null,
   principal:null,
   arregloCampos: CantidadCampos.value
});

const saveCategory = () => 
{
    CategoryForm.post(route('storeCategory'),
    {
       onFinish: () => CategoryForm.reset(),
       onSuccess: () =>  close() 
    });
}

const retornarFile = (file)  =>
{
   //console.log(file);
   CategoryForm.imagen = file;
   //console.log(file);
}

</script>
<template>
         <DialogModal  @close="close()">
           <template #title>
               <h2 style="font-weight:bolder">Nueva categoria</h2>
            </template>
            <template #content>
                <form class="flex flex-wrap justify-center">
                    <div class="grid grid-cols-3 grid-rows-1">
                        <div class="">
                          <InputLabel>Nombre de categoría</InputLabel>
                          <TextInput v-model="CategoryForm.nombre" class="mr-2 border border-slate-400"></TextInput>
                        </div>
                        <div class="col-start-2 col-end-4">
                          <UploadFile @retornar="retornarFile"></UploadFile>
                        </div>
                        <!-- <TextInput type="file" v-model="CategoryForm.imagen" class="mr-2"></TextInput> -->
                    </div>
                    <div class="w-full mt-4 mb-4">
                      <ButtonAdd @click="addCampo">+</ButtonAdd>
                    </div>
                    <div v-for="campo in CantidadCampos" :key="campo.id" class="flex mt-2">
                       <div class="mr-2">
                         <InputLabel>Campo</InputLabel>
                         <TextInput v-model="campo.nombre"></TextInput>
                       </div>
                       <div class="mr-2">
                         <InputLabel>Tipo de campo</InputLabel>
                         <SelectComponent v-model="campo.tipo_input_id">
                           <option selected  disabled>Selecciona una opción</option>
                           <option v-for="tipoInput in tipo_inputs" :key="tipoInput.id" :value="tipoInput.id">{{ tipoInput.nombre }}</option>
                         </SelectComponent>
                       </div>
                       <div class="mr-2">
                         <InputLabel>Campo principal</InputLabel>
                         <Checkbox v-model="campo.principal"></Checkbox>
                       </div>
                    </div>
                </form>
                <div class="flex flex-row-reverse">
                        <button @click="saveCategory" style="" class="p-2 bg-blue-500 rounded-lg hover:opacity-50">
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

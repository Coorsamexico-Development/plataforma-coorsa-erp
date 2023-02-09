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
        tipo_evidencias:Object,
    }
);

const components = 
{
   UploadFile: UploadFile,
   TextInput:TextInput
}

const setComponent = (campoType) =>
{
  switch (campoType) {
      case "tabla":
        
        break;
      case "file":
           return components.UploadFile
        break;

      default:
          return components.TextInput
        break;
     }
}

const retornar = (file) => 
{
    //console.log(file);
}

//Modals
const close = () => 
{
    emit('close');
};

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
               <div class="mt-8 border-b-2" v-if="activo.valor_campos_activos.length > 0">
                  <div v-for="campo in activo.valor_campos_activos" :key="campo">
                    <component v-for="campo in campos" :key="campo.id" :is="setComponent(campo.input)"  @retornar="retornar"/>  
                  </div>
               </div>
               <div class="flex mt-4">
                   <div class="flex">
                     <h2 class="mr-4">Evidencias</h2>
                     <ButtonAdd @click="addTipoEvidencia">+</ButtonAdd>
                     <ModalAddTipoEvidencia :show="modalTipoEvidencia" @close="closeTipoEvidencia" />
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

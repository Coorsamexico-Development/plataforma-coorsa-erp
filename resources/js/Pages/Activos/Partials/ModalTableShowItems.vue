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
import TableButton from "./TableButton.vue";
import ColumText from "./ColumText.vue";
import ColumFile from "./ColumFile.vue";
import axios from 'axios';

const emit = defineEmits(["close", "axios"]);

var props = defineProps(
    {
        campo:Object,
        campos:Object
    }
);

const close = () => 
{
    emit('close');
};

const components = 
{
    TableButton:TableButton,
    ColumText:ColumText,
    ColumFile:ColumFile
}

const setComponent = (campoType) =>
{
  switch (campoType) {
      case "table":
          return components.TableButton
        break;

      case "file":
           return components.ColumFile
        break;

      case "text":
          return components.ColumText
        break

      case "number":
          return components.ColumText
        break

      default:
          return components.ColumText
        break;
    
     }
}

const openShow = ref(false);

const openModalShowCampos = (campo_id) =>
{
   console.log(campo_id);
   openShow.value = true;
}

const closeModalShowCampos = () => 
{
  openShow.value = false;
}

</script>
<template>
         <DialogModal :maxWidth="'3xl'"  @close="close()">
           <template #title>
                 <h1>Campos de {{ campo.campo }}</h1>
            </template>
            <template #content>
              <div class="flex justify-center w-full">
                  <table class="w-full">
                     <thead>
                        <tr class="border-b ">
                           <th class="text-center p-8 pt-0 pb-1" v-for="campose in campos" :key="campose.id">
                             {{ campose.campo }}
                           </th>
                        </tr>
                     </thead>
                     <tbody>
                        <tr>
                          <td class="text-center p-8 pt-0 pb-1" v-for="valore in campos" :key="valore.id">
                             <component :is="setComponent(valore.tipo_input)" :valore="valore" @openModalTableCampos="openModalShowCampos(valore.id)" />
                          </td>
                        </tr>
                     </tbody>
                  </table>
              </div>
            </template>
            <template #footer>
                <SecondaryButton  @click="close()" class="closeModal1" style="float:right">
                      Cerrar
                </SecondaryButton>
            </template>
        </DialogModal>
</template>

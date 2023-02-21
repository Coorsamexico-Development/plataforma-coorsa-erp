<script setup>
import FileUpload from '../Partials/FileUpload.vue';
import InputText from '../Partials/InputText.vue';
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import TableButton from './TableButton.vue';
import ModalTableItems from './ModalTableItems.vue'
import { ref } from 'vue';
import { colorInterpolate } from '@amcharts/amcharts5/.internal/core/util/Animation';

var props = defineProps(
    {
        columns:Object,
        activoId:Number,

        tipo_inputs:Object,
        tipoActivo:Number
    }
);

const components = 
{
   FileUpload:FileUpload,
   InputText:InputText,
   TableButton:TableButton
}

const setComponent = (campoType) =>
{
  switch (campoType) {
      case "table":
          return components.TableButton
        break;
      case "file":
           return components.FileUpload
        break;
      default:
          return components.InputText
        break;
     }
}

const takeValor = (valor) => 
{
    const valorForm = useForm(
   {
      valor:valor.value,
      tipo_activo_campo_id: activoCampoId.value,
      activo_id: props.activoId
   }
  );
   //console.log(valorForm);
   valorForm.post(route('saveEditCampos',props.activoId),{
    preserveScroll:true,
    preserveState:true,
    onSuccess:() => valorForm.reset(),
    onError: () => message.value = "hay errores"
  });

}

const activoCampoId = ref(-1);
const putId = (id) =>
{
    activoCampoId.value = id;
}

const setFiles = (files) =>
{
    //console.log(files[0]);
    const fileForm = useForm({
     file:files[0],
     tipo_activo_campo_id: activoCampoId.value,
     activo_id: props.activoId
  });

  fileForm.post(route('saveEditCampos',props.activoId),{
    preserveScroll:true,
    preserveState:true,
    onSuccess:() => fileForm.reset()
  });
}

let dinamicModal = ref(false);
let columNameReactive = ref(-1);
let idColumReactive = ref(-1);
const openDinamicModal = (colum) =>
{
    columNameReactive.value = colum.campo;
    idColumReactive.value = colum.id;
    dinamicModal.value = true;
}
const closeDinamicModal = () =>
{
    dinamicModal.value = false;
}

</script>
<template>
    <table class="">
     <thead class="border-b-2">
          <tr>
             <th class="p-2">Nombre de columna</th>
             <th class="p-2">Tipo de campo</th>
             <th class="p-2">Valor</th>
          </tr>
      </thead>
      <tbody class="mt-2">
          <tr v-for="(colum, index) in columns" :key="index">
             <td class="text-center">
               {{ colum.campo }}
             </td>
             <td class="text-center">
               {{colum.nombre}}
             </td>
             <td class="flex justify-center pt-2">
                <component :is="setComponent(colum.nombre)"
                :placeholder="colum.valor"
                @click="putId(colum.id)"
                @updateCampo="takeValor"
                @retornar="setFiles"
                @openModalTableCampos="openDinamicModal(colum)" />

                <ModalTableItems 
                 :campoName="colum.campo"
                 :idCampo="colum.id"
                 :activo_id="activoId"
                 :tipo_inputs="tipo_inputs"
                 :tipoActivo="tipoActivo"
                 :show="dinamicModal" 
                 @close="closeDinamicModal"/>
             </td>
          </tr>
      </tbody>
    </table>

</template>
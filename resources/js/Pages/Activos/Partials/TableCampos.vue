<script setup>
import FileUpload from '../Partials/FileUpload.vue';
import InputText from '../Partials/InputText.vue';
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import TableButton from './TableButton.vue';
import ModalTableItems from './ModalTableItems.vue'
import { ref } from 'vue';

var props = defineProps(
    {
        columns:Object,
        activoId:Number
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

const dinamicModal = ref(false);
const openDinamicModal = () =>
{
    //console.log('files');
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
          <tr v-for="colum in columns" :key="colum.id">
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
                @openModalTableCampos="openDinamicModal" />
             </td>
          </tr>
      </tbody>
    </table>
    <ModalTableItems :show="dinamicModal" @close="closeDinamicModal"/>
</template>
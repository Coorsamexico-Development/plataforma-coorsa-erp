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
import InputText from "./InputText.vue";
import FileUpload from "./FileUpload.vue";
import axios from 'axios';
import ModalTableShowItems from '../Partials/ModalTableShowItems.vue';

const emit = defineEmits(["close", "axios"]);

var props = defineProps(
    {
        campo:Object,
        campos:Object,
        idActivo:Number
    }
);

const close = () => 
{
    emit('close');
};

const components = 
{
    TableButton:TableButton,
    InputText:InputText,
    FileUpload:FileUpload,

    ColumText:ColumText,
    ColumFile:ColumFile
}

const setComponent = (campoType, permiso) =>
{
  //console.log(permiso)
  if(permiso == true)
  {
    switch (campoType) {
      case "table":
          return components.TableButton
        break;

      case "file":
           return components.FileUpload
        break;

      case "text":
          return components.InputText
        break

      case "number":
          return components.InputText
        break

      default:
          return components.InputText
        break;
    
     }
  }
  else
  {
    switch (campoType) {
      case "table":
          return components.TableButton
        break;

        /*
      case "file":
           return components.ColumFile
        break;
      */

      case "text":
          return components.ColumText
        break

      case "number":
          return components.ColumText
        break

     }
  }

}

const openShow = ref(false);
const campoReactive = ref(null);
const camposReactive = ref(null);
const openModalShowCampos = (campo) =>
{
   campoReactive.value = campo;
   console.log(campo)
   axios.get('/columnasxCampo/'+campo.campoId +'/'+props.idActivo).then((response)=> 
    {
        console.log(response.data);
        camposReactive.value = response.data;
    });
   openShow.value = true;
}

const closeModalShowCampos = () => 
{
  openShow.value = false;
}

const updateCampo = (valor, campo_id) => 
{
  /*
  console.log(valor);
  console.log(campo_id)
  */
  const valorForm = useForm(
   {
      valor:valor.value,
      tipo_activo_campo_id: campo_id,
      activo_id: props.idActivo
   }
  );
   //console.log(valorForm);
   valorForm.post(route('saveEditCampos',props.idActivo),{
    preserveScroll:true,
    preserveState:true,
    onSuccess:() => valorForm.reset(),
  });
  
}

const setFiles = (files, campo_id) => 
{
  //console.log(campo_id);
   //console.log(files[0]);
   const fileForm = useForm({
     file:files[0],
     tipo_activo_campo_id: campo_id,
     activo_id: props.idActivo
  });
 
  fileForm.post(route('saveEditCampos',props.idActivo),{
    preserveScroll:true,
    preserveState:true,
    onSuccess:() => fileForm.reset()
  });
  
}

const addNewDato = () => 
{
  const newValores = useForm(
    {
       valor:null,
       tipo_activo_campo_id:'',
       activo_id: props.idActivo
    }
   );
   
   // console.log(props.campos);
    for(let i = 0 ; i < props.campos.length; i++)
    {
         //console.log(props.campos[i]);
         let campoDatos = props.campos[i];
         newValores.valor = 'sin valor';
         newValores.tipo_activo_campo_id = props.campos[i].campoId;
         //console.log(newValores);

         newValores.post(route('new.valor.campo'),{
            preserveScroll:true,
            preserveState:true,
           
         })
    }

}

</script>
<template>
         <DialogModal :maxWidth="'5xl'"  @close="close()">
           <template #title>
                 <h1 >Campos de <span class="font-bold">{{ campo.campo }}</span></h1>
            </template>
            <template #content>
              <div class="flex flex-col items-center justify-center w-full">
                  <table class="w-full">
                     <thead>
                        <tr class="border-b" v-if="campos">
                           <th class="p-8 pt-0 pb-1 text-center" v-for="campose in campos[0]" :key="campose.id">
                             {{ campose.campo }}
                           </th>
                        </tr>
                     </thead>
                     <tbody>
                        <tr v-if="campos">
                           <td class="p-8 pt-0 pb-1 text-center" v-for="campose in campos[0]" :key="campose.id">                           
                                 <component :is="setComponent(campose.tipo_input_nombre, $page.props.can['activos.edit.campos'])" 
                                 :valore="valore"
                                  @updateCampo ="updateCampo"
                                  @retornar="setFiles"
                                  @openModalTableCampos="openModalShowCampos(valore)" />
                           </td>
                          
                          <!--      
                          <td class="p-8 pt-0 pb-1 text-center" v-for="valore in campos" :key="valore.id">
                          
                               <div v-if="valore.tipo_input == 'file'">
                                  <ColumFile :valore="valore" />
                              </div>
                                              
                             <component :is="setComponent(valore.tipo_input, $page.props.can['activos.edit.campos'])" 
                             :valore="valore"
                              @updateCampo ="updateCampo"
                              @retornar="setFiles"
                              @openModalTableCampos="openModalShowCampos(valore)" />
                          </td>
                        --->  
                        </tr>
                     </tbody>
                  </table>
                  <button @click="addNewDato" class="mt-4 bg-[#EC2944] text-white rounded-full w-8">+</button>
              </div>
              <ModalTableShowItems :idActivo="idActivo" :campo="campoReactive" :campos="camposReactive" @close="closeModalShowCampos" :show="openShow"/>
            </template>
            <template #footer>
                <SecondaryButton  @click="close()" class="closeModal1" style="float:right">
                      Cerrar
                </SecondaryButton>
            </template>
        </DialogModal>
</template>

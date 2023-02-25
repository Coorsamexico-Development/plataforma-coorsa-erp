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
import TableRow from '../Partials/TableRow.vue';
import DangerButton from '@/Components/DangerButton.vue';

const emit = defineEmits(["close", "axios"]);

var props = defineProps(
    {
        campo:Object,
        filare:Object,
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

        
      case "file":
           return components.ColumFile
        break;
      

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
const filaReactive = ref(null);
const camposReactive = ref(null);
const openModalShowCampos = (campo,fila, idActivo) =>
{
  /*
    console.log(campo);
    console.log(idActivo);
    console.log(fila);
    */
    filaReactive.value = fila;
    campoReactive.value = campo;

    if(fila)
    {
      axios.get('/columnasxCampoFila/'+campo.id+'/'+idActivo+'/'+fila.id).then((response)=> 
       {
                console.log(response.data);
               camposReactive.value = response.data;
       });
    }
    else
    {
      axios.get('/columnasxCampo/'+campoReactive.value.id+'/'+idActivo).then((response)=> 
       {
                //console.log(response.data);
               camposReactive.value = response.data;
       });
    }
    openShow.value = true;
}

const closeModalShowCampos = () => 
{
  openShow.value = false;
}

const updateCampo = (valor, columna, fila) => 
{
  const valorForm = useForm(
   {
      valor:valor.value,
      tipo_activo_campo_id: columna.id,
      activo_id: props.idActivo,
      fila_id:fila.id
   }
  );
   valorForm.post(route('saveEditCampos',props.idActivo),{
    preserveScroll:true,
    preserveState:true,
    onSuccess:() => valorForm.reset(),
  });
  
}

const setFiles = (files, campo_id, fila_id) => 
{
    console.log(campo_id);
    console.log(fila_id);
    console.log(files[0]);

   const fileForm = useForm({
     file:files[0],
     tipo_activo_campo_id: campo_id.id,
     activo_id: props.idActivo,
     fila_id: fila_id.id
  });
 
  fileForm.post(route('saveEditCampos',props.idActivo),{
    preserveScroll:true,
    preserveState:true,
    onSuccess:() => fileForm.reset()
  });
  
}

const addNewFila = (campo_id, otro_campo_id, fila_id) => 
{   
  if(fila_id)
  {
    if(campo_id)
   {
        const filaForm = useForm({
         tipo_activo_campo_id: campo_id,
         activos_items_id: props.idActivo,
         fila_id: fila_id
      });
    
      filaForm.post(route('newFila'),{
        preserveScroll:true,
        preserveState:true,
        onSuccess:() => filaForm.reset()
      });
   }
   else if(otro_campo_id)
   {
    const filaForm = useForm({
         tipo_activo_campo_id: otro_campo_id,
         activos_items_id: props.idActivo,
         fila_id: fila_id
      });
    
      filaForm.post(route('newFila'),{
        preserveScroll:true,
        preserveState:true,
        onSuccess:() => filaForm.reset()
      });
   }
  }
  else{
   if(campo_id)
   {
        const filaForm = useForm({
         tipo_activo_campo_id: campo_id,
         activos_items_id: props.idActivo
      });
    
      filaForm.post(route('newFila'),{
        preserveScroll:true,
        preserveState:true,
        onSuccess:() => filaForm.reset()
      });
   }
   else if(otro_campo_id)
   {
    const filaForm = useForm({
         tipo_activo_campo_id: otro_campo_id,
         activos_items_id: props.idActivo
      });
    
      filaForm.post(route('newFila'),{
        preserveScroll:true,
        preserveState:true,
        onSuccess:() => filaForm.reset()
      });
   }
  }
}

</script>
<template>
         <DialogModal :maxWidth="'5xl'"  @close="close()">
           <template #title>
                 <h1 class="text-center" >Campos de <span class="font-bold">{{ campo.campo }}</span></h1>
            </template>
            <template #content>
              <div class="flex flex-col items-center justify-center w-full">
                 <table class="w-full" v-if="campos">
                    <thead>
                      <tr class="text-center">
                        <th></th>
                        <th v-for="(columna,index) in campos[0]" :key="index" >
                            {{ columna.campo }}
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr class="text-center border-b" v-for="(fila, index2) in campos[1]" :key="index2">
                       <td>
                        {{ fila.id }} 
                        <!-- Filaid: {{ fila.id }} 
                        <DangerButton>
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                                 <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                             </svg>
                        </DangerButton>
                        -->
                       </td>
                       <td v-for="(columna,index3) in campos[0]" :key="index3" >
                         <div v-if="columna.tipo_input_nombre == 'file'">
              
                          <component v-if="$page.props.can['activos.edit.campos']" 
                          :is="setComponent(columna.tipo_input_nombre, $page.props.can['activos.edit.campos'])"
                          :columna="columna"
                          :fila="fila"
                          @retornar="setFiles"
                          @openModalTableCampos="openModalShowCampos(columna,fila, idActivo)" />
                         </div>
                          <div v-if="campos[2].length > 0 ">
                            <div v-for="(valore, index4) in campos[2]" :key="index4">
                               <div v-if="valore.columna_id == columna.id  && valore.fila_id == fila.id">
                                  <div v-if="valore.valor">
                                      <component :is="setComponent(columna.tipo_input_nombre, $page.props.can['activos.edit.campos'])" 
                                        :valore="valore"
                                        :columna="columna"
                                        :fila="fila"
                                        @updateCampo ="updateCampo"
                                        @retornar="setFiles"
                                        @openModalTableCampos="openModalShowCampos(columna,fila, idActivo)" />
                                        
                                  </div>
                               </div>
                               <div v-else>
                                <div v-if="index3 == index4">
                                  <component :is="setComponent(columna.tipo_input_nombre, $page.props.can['activos.edit.campos'])" 
                                        :valore="valore"
                                        :columna="columna"
                                        :fila="fila"
                                        @updateCampo ="updateCampo"
                                        @retornar="setFiles"
                                        @openModalTableCampos="openModalShowCampos(columna,fila, idActivo)" />
                                </div>
                               </div>
                            </div>
                          </div>
                          <div v-if="campos[2].length <= 0">
                            <component :is="setComponent(columna.tipo_input_nombre, $page.props.can['activos.edit.campos'])" 
                                :columna="columna"
                                :fila="fila"
                                @updateCampo ="updateCampo"
                                @retornar="setFiles"
                                @openModalTableCampos="openModalShowCampos(columna,fila, idActivo)" />
                          </div>
                       </td>
                      </tr>
                    </tbody>
                 </table>
                 <button @click="addNewFila(campo.idCampo, campo.id, filare.id)" class="mt-4 bg-[#EC2944] text-white rounded-full w-8">+</button>
              </div>

              <ModalTableShowItems :idActivo="idActivo" :campo="campoReactive" :filare="filaReactive" :campos="camposReactive" @close="closeModalShowCampos" :show="openShow"/>
            </template>
            <template #footer>
                <SecondaryButton  @click="close()" class="closeModal1" style="float:right">
                      Cerrar
                </SecondaryButton>
            </template>
        </DialogModal>
</template>

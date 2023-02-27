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
import InputDate from './InputDate.vue';
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
    InputDate:InputDate,

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

      case "date":
          return components.InputDate
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

      case "date":
          return components.InputDate
        break

     }
  }

}

const openShow = ref(false);
const campoReactive = ref(null);
const filaReactive = ref(null);
const camposReactive = ref([]);
const openModalShowCampos = (campo, fila, idActivo) =>
{
    filaReactive.value = fila;
    campoReactive.value = campo;

    console.log(fila.id, campo.id, idActivo);

    if(fila)
    {
      axios.get('/columnasxCampoFila/'+campo.id+'/'+idActivo+'/'+fila.id).then((response)=> 
       {
                //console.log(response.data);
                let camposAxios = response.data;   
                camposReactive.value = response.data;
                let newValores = [];
                for (let index = 0; index < camposAxios[1].length; index++)  //recorrido de filas
                  {
                      const fila = camposAxios[1][index];
                      for (let index1 = 0; index1 < camposAxios[0].length; index1++) //recorrido de columnas por fila
                      {
                        let newInterseccion = {};
                        const columna = camposAxios[0][index1];
                        //console.log(fila.id, columna.id)
                        newInterseccion.fila_id = fila.id;
                        newInterseccion.columna_id = columna.id;
                        newInterseccion.valor = null;
                        newValores.push(newInterseccion);
                      }
                  }

                //recorrido para posicionar valor
                 for (let index2 = 0; index2 < newValores.length; index2++) 
                 {
                    const interseccion = newValores[index2];
                    //console.log(interseccion);
                    for (let index3 = 0; index3 < camposAxios[2].length; index3++)  //recorremos valores
                    {
                      const valor = camposAxios[2][index3];
                      if(valor.columna_id == interseccion.columna_id && valor.fila_id == interseccion.fila_id)
                      { 
                         //console.log(valor.valor);
                         interseccion.valor = valor.valor;
                      }
                    }
                 }
                //console.log(newValores);
                camposReactive.value.pop();
                camposReactive.value.push(newValores);
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
                        <DangerButton>
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                                 <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                             </svg>
                        </DangerButton>
                       </td>
                       <td v-for="(columna,index3) in campos[0]" :key="index3" >
                          <div v-if="campos[2].length > 0 "> <!--Validamos si trae valores-->
                            <div v-for="(valore, index4) in campos[2]" :key="index4">
                               <div v-if="valore.columna_id == columna.id  && valore.fila_id == fila.id">
                                  <div v-if="valore.valor">
                                      <div v-if="columna.tipo_input_nombre == 'file'">
                                          <ColumFile  
                                              :valore="valore"
                                              :columna="columna"
                                              :fila="fila" />  
                                      </div>
                                      <component :is="setComponent(columna.tipo_input_nombre, $page.props.can['activos.edit.campos'])" 
                                        :valore="valore"
                                        :columna="columna"
                                        :fila="fila"
                                        @updateCampo ="updateCampo"
                                        @retornar="setFiles"
                                        @openModalTableCampos="openModalShowCampos(columna,fila, idActivo)" />                     
                                  </div>
                                  <div v-else>
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
                       </td>
                      </tr>
                    </tbody>
                 </table>
                 <button v-if="filare" @click="addNewFila(campo.idCampo, campo.id, filare.id)" class="mt-4 bg-[#EC2944] text-white rounded-full w-8">+</button>
                 <button v-else @click="addNewFila(campo.idCampo, campo.id)" class="mt-4 bg-[#EC2944] text-white rounded-full w-8">+</button>
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

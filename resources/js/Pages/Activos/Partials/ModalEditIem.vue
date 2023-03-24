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
import InputText from '../Partials/InputText.vue'
import ModalAddTipoEvidencia from '../Partials/ModalAddTipoEvidencia.vue';
import axios from 'axios';
import { value } from 'dom7';
import InputError from '@/Components/InputError.vue';
import TableButton from '../Partials/TableButton.vue';
import ModalTableItems from '../Partials/ModalTableItems.vue';
 
const emit = defineEmits(["close"]);

var props = defineProps(
    {
        activo:{
         type:Object,
         required:true
        },
        tipo_evidencias:Object,
        //
        campos:Object,
        tipo_inputs:Object,
        tipoActivo:Object
    }
);

const components = 
{
   UploadFile: UploadFile,
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
           return components.UploadFile
        break;

      default:
          return components.InputText
        break;
     }
}

const campo_valor_id = ref(null);
const putId = (valor_id) => 
{
  //console.log(valor_id);
  campo_valor_id.value = valor_id;
}

//Campo tipo file
const setFile = (file) => 
{
  const fileForm = useForm({
   file:file,
   tipo_activo_campo_id: campo_valor_id.value,
   activo_id: props.activo.id
});

  fileForm.post(route('saveEditCampos',props.activo.id),{
    preserveScroll:true,
    preserveState:true,
    onSuccess:() => fileForm.reset()
  });
}

let message = ref('error');
//Otros tipo campo
const updateCampo = (valor) => 
{
  //console.log(props.activo.id);
  const valorForm = useForm(
   {
      valor:valor.value,
      tipo_activo_campo_id: campo_valor_id.value,
      activo_id: props.activo.id
   }
  )
   //console.log(valorForm);
   valorForm.post(route('saveEditCampos',props.activo.id),{
    preserveScroll:true,
    preserveState:true,
    onSuccess:() => valorForm.reset(),
    onError: () => message.value = "hay errores"
  });
} 

/*Subida de evidencias*/ 
const user = usePage().props.value.user;
const EvidenciasForm = useForm({
   nombre:null,
   imagenes:null,
   tipoEvidencia: -1,
   usuario: user.id,
   activo_id: -1
});
let imagenesArray = ref([]);
const setPhotoFiles = (fieldName, fileList) => 
{
   imagenesArray.value = fileList;
   EvidenciasForm.imagenes = imagenesArray.value;
   EvidenciasForm.activo_id = props.activo.id;
   
   EvidenciasForm.post(route('saveEvidencias'),
   {
    preserveScroll:true,
    preserveState:true,
    onError: () =>  "Hay errores",
    onSuccess:() => EvidenciasForm.reset()
   })
   
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

//MODAL TABLE CAMPOS
const tableModal = ref(false);
let campoName = ref(null);
let idCampoR = ref(null);
let colums = ref(null);
const openTableModal = (campo,idCampo) => 
{
  campoName.value = campo;
  idCampoR.value = idCampo;
  //console.log(campoName);
  tableModal.value = true;

  axios.get('/getCampos/'+props.activo.id+'/'+props.tipoActivo.id).then((response)=> 
     {
        //console.log(response);
        colums.value = response.data;
     });

}
const closeTableModal = () => 
{
  tableModal.value = false;
}

const arregloCampos = computed(() => {
   let arregloAux = [];
   for (let index = 0; index < props.campos.length; index++) 
   {
      let objCampo = {};
      const campo = props.campos[index];
      objCampo.id = campo.idCampo;
      objCampo.campo = campo.campo;
      objCampo.principal = campo.principal;
      objCampo.input = campo.input;
      objCampo.valor = "";
      arregloAux.push(objCampo);
   }

   
   for (let index2 = 0; index2 < arregloAux.length; index2++) 
   {
      const campo = arregloAux[index2];
      //console.log(campo);
      //console.log(props.activo.valor_campos_activos);
      for (let index3 = 0; index3 < props.activo.valor_campos_activos.length; index3++) 
      {
         const valor = props.activo.valor_campos_activos[index3];
        if(campo.id == valor.tipo_activo_campo_id)
        {
          campo.valor = valor.valor;
        }
      }
   }

   return arregloAux;
});


</script>
<template>
         <DialogModal  @close="close()">
           <template #title>
               <h2 style="font-weight:bolder">Opciones de activo</h2>
            </template>
            <template #content>
               <div class="mt-4 border-b-2">
                <h2 class="mr-4">Campos</h2>
                <div v-for="campo in arregloCampos" :key="campo.id">
                  <InputLabel>{{ campo.campo }}</InputLabel>
                  <component 
                        :valore="campo.valor"
                       :is="setComponent(campo.input)" 
                       @input="putId(campo.id)" 
                       @updateCampo="updateCampo"
                       @retornar="setFile"
                       @openModalTableCampos="openTableModal(campo.campo, campo.id)" />  
                </div>
               </div>
               <ModalTableItems v-if="campoName !== null" 
               :activo_id="activo.id" 
               :campoName="campoName" 
               :idCampo="idCampoR"
               :tipoActivo="activo.tipo_activo" 
               :show="tableModal" 
               :tipo_inputs="tipo_inputs"
               :colums="colums"
               @close="closeTableModal"/>
               <div class="flex flex-col mt-4">
                   <h2 class="mr-4">Evidencias</h2>
                   <div>
                      <InputLabel>Nombre de evidencia(s)</InputLabel>
                      <TextInput v-model="EvidenciasForm.nombre" />
                   </div>
                   <div class="flex flex-row">
                     <div class="mr-4">
                        <InputLabel>Tipo de evidencia(s)</InputLabel>
                        <SelectComponent v-model="EvidenciasForm.tipoEvidencia">
                          <option :disabled="true" :selected="true">Selecciona una opción</option>
                          <option v-for="tipoevidencia in tipo_evidencias" :key="tipoevidencia" :value="tipoevidencia.id">
                             {{ tipoevidencia.nombre }}
                          </option>
                        </SelectComponent>
                        <ButtonAdd class="ml-4" @click="addTipoEvidencia">+</ButtonAdd>
                        <ModalAddTipoEvidencia :show="modalTipoEvidencia" @close="closeTipoEvidencia" />
                     </div>
                      <div>
                        <input class="mt-6" type="file" multiple  @change="setPhotoFiles($event.target.name, $event.target.files)"  />
                      </div>
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

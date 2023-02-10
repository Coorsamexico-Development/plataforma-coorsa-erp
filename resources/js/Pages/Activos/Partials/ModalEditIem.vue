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

const emit = defineEmits(["close"]);

var props = defineProps(
    {
        activo:Object,
        tipo_evidencias:Object,
        //
        campos:Object
    }
);

const components = 
{
   UploadFile: UploadFile,
   InputText:InputText
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
          return components.InputText
        break;
     }
}

const campo_valor_id = ref(null);
const putId = (valor_id) => 
{
  console.log(valor_id);
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
    preserveState:true
  });
}

const newValue = ref(null);
const putvalue = (valor) =>  //si se hace por un watch este va a detectar cada cambio..
{
   //console.log(newValue.value)
}

const update = () => 
{
  console.log("hola");
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
</script>
<template>
         <DialogModal  @close="close()">
           <template #title>
               <h2 style="font-weight:bolder">Opciones de activo</h2>
            </template>
            <template #content>
               <div class="mt-4 border-b-2">
                {{  pruebaReactiva}}
                <h2 class="mr-4">Campos</h2>
                <div v-if="activo.valor_campos_activos.length > 0"> <!--Si existen valores se setean en los inputs-->
                  <div v-for="campo in campos" :key="campo.id"> 
                    <InputLabel>{{ campo.campo }}</InputLabel>
                    <div v-for="valor in activo.valor_campos_activos" :key="valor.id">
                      <component v-if="valor.tipo_activo_campo_id == campo.idCampo" 
                       :is="setComponent(campo.input)" 
                       @click="putId(valor.tipo_activo_campo_id)" 
                       @retornar="setFile"/>
                    </div>
                    
                   </div>
                </div>
                <div v-else> <!--Si no existen valores estaran vacios los inputs-->
                  <div v-for="campo in campos" :key="campo.id">  
                       <component :is="setComponent(campo.input)" @change="putInfo" @retornar="setFile"/>
                   </div>
                </div>
               </div>
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

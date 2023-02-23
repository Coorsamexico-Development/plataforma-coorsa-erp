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
   console.log(campo);
   axios.get('/columnasxCampo/'+campo.campoId +'/'+campo.idActivo).then((response)=> 
    {
         //  console.log(response.data);
            //camposReactive.value = response.data;
            let arregloTemporal = [];
             let valorTemporal;
             for(let x=0; x < response.data.length; x++)
             {
                let element = response.data[x];
                let objeto = {};

                if(element == response.data[0])
                {
                   objeto.idActivo = element.idActivo;
                   objeto.campoId = element.campoId;
                   objeto.campo = element.campo;
                   objeto.tipo_input= element.tipo_input;
                   objeto.valores = [{idActivo: element.idActivo,valor:element.valor, campoId:element.campoId,campo: element.campo}];

                   valorTemporal = element.campoId;
                   arregloTemporal.push(objeto);
                }
                else
                {
                  if(valorTemporal !== element.campoId) //ssi es diferente
                  {
                     arregloTemporal.push(
                      {
                         idActivo: element.idActivo,
                         campoId:element.campoId,
                         campo: element.campo,
                         tipo_input: element.tipo_input,
                         valores: [{idActivo: element.idActivo,valor:element.valor, campoId:element.campoId,campo: element.campo}]
                      }
                     )

                     valorTemporal = element.campoId;
                  }
                  else
                  {
                    for(let i=0; i < arregloTemporal.length ; i++)
                    {
                      if(element.campoId == arregloTemporal[i].campoId)
                      {
                        arregloTemporal[i].valores.push({idActivo: element.idActivo,valor:element.valor,campoId:element.campoId,campo: element.campo});
                      }
                    }
                  }
                } 
                camposReactive.value=arregloTemporal; 
             }  
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
         console.log(props.campos[i]);
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

let arreglo = ref([]);

</script>
<template>
         <DialogModal :maxWidth="'5xl'"  @close="close()">
           <template #title>
                 <h1 >Campos de <span class="font-bold">{{ campo.campo }}</span></h1>
            </template>
            <template #content>
              <div class="flex flex-col items-center justify-center w-full">
      
                 <table class="w-full">
                    <td class="text-center "  v-for="campo in campos">
                      <span class="font-bold">{{ campo.campo }}</span>
                      <tr class="flex justify-center pt-4 pb-4 text-center border-b" v-for ="valore in campo.valores">
                         <div v-if="campo.tipo_input == 'file'">
                              <ColumFile :valore="valore" />
                          </div>
                          <!--
                            Permissions
                            activos.edit.campos
                            access.control-interno
                          -->
                          <component :is="setComponent(campo.tipo_input, $page.props.can['access.control-interno'])" 
                              :valore="valore"
                              @updateCampo ="updateCampo"
                              @retornar="setFiles"
                              @openModalTableCampos="openModalShowCampos(valore)" />
                      </tr>
                    </td>
                 </table>
                <!--
                  <table class="w-full">
                     <thead>
                        <tr class="border-b ">
                           <th class="p-8 pt-0 pb-1 text-center" v-for="campose in campos" :key="campose.id">
                             {{ campose.campo }}
                           </th>
                        </tr>
                     </thead>
                     <tbody>
                        <tr>
                          <td class="p-8 pt-0 pb-1 text-center" v-for="(valore,index) in campos" :key="valore.id">
                               <div v-if="valore.tipo_input == 'file'">
                                  <ColumFile :valore="valore" />
                              </div>
                              <component :is="setComponent(valore.tipo_input, $page.props.can['activos.edit.campos'])" 
                              :valore="valore"
                              @updateCampo ="updateCampo"
                              @retornar="setFiles"
                              @openModalTableCampos="openModalShowCampos(valore)" />
                          </td>  
                        </tr>
                     </tbody>
                  </table>
                  <button @click="addNewDato" class="mt-4 bg-[#EC2944] text-white rounded-full w-8">+</button>
                                -->
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

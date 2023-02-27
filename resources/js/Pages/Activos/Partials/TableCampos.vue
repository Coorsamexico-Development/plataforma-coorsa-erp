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
        tipoActivo:Number,
    }
);

const components = 
{
   FileUpload:FileUpload,
   InputText:InputText,
   TableButton:TableButton
}

const emit = defineEmits(["editColum"]);

const setComponent = (campoType) =>
{
  switch (campoType) {
      case "table":
          return components.TableButton
        break;
        /*
      case "file":
           return components.FileUpload
        break;
      default:
          return components.InputText
        break;
        */
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
let colums = ref(null);
const openDinamicModal = (colum) =>
{
    columNameReactive.value = colum.campo;
    idColumReactive.value = colum.id;
    dinamicModal.value = true;
    axios.get('/getCampos/'+colum.id+'/'+props.tipoActivo).then((response)=> 
     {
        //console.log(response);
        colums.value = response.data;
     });
}
const closeDinamicModal = () =>
{
    dinamicModal.value = false;
}

const changeEdit = (colum) => 
{
    emit('editColum',colum)
}

</script>
<template>
    <table class="w-full">
     <thead class="border-b-2">
                <tr>
             <th></th>
             <th class="p-2">Nombre de columna</th>
             <th class="p-2">Tipo de campo</th>
             <th class="p-2">Campo</th>
          </tr>
      </thead>
      <tbody class="mt-2">
          <tr v-for="(colum, index) in columns" :key="index">
            <td>
              {{ colum.id }}
            </td>
            <td>
               <button @click="changeEdit(colum)" class="bg-[#f28c00] m-2 rounded-2xl pl-2 pr-2">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="35" height="33" viewBox="0 0 35 33">
                   <defs>
                     <clipPath id="clip-Icono-editar">
                       <rect width="35" height="33"/>
                     </clipPath>
                   </defs>
                   <g id="Icono-editar" clip-path="url(#clip-Icono-editar)">
                     <rect width="35" height="33" fill="rgba(255,255,255,0)"/>
                     <g id="Grupo_513" data-name="Grupo 513" transform="translate(-7736.179 -2823)">
                       <g id="editar" transform="translate(7743.179 2828.996)">
                         <g id="Grupo_459" data-name="Grupo 459" transform="translate(0 1.55)">
                           <g id="Grupo_458" data-name="Grupo 458">
                             <path id="Trazado_286" data-name="Trazado 286" d="M17.778,43.412a.773.773,0,0,0-.773.773v7.729a.773.773,0,0,1-.773.773H2.319a.773.773,0,0,1-.773-.773V36.456a.773.773,0,0,1,.773-.773h9.275a.773.773,0,1,0,0-1.546H2.319A2.319,2.319,0,0,0,0,36.456V51.915a2.319,2.319,0,0,0,2.319,2.319H16.232a2.319,2.319,0,0,0,2.319-2.319V44.185A.773.773,0,0,0,17.778,43.412Z" transform="translate(0 -34.137)" fill="#fff"/>
                           </g>
                         </g>
                         <g id="Grupo_461" data-name="Grupo 461" transform="translate(4.638 0.004)">
                           <g id="Grupo_460" data-name="Grupo 460">
                             <path id="Trazado_287" data-name="Trazado 287" d="M118.547.87a2.959,2.959,0,0,0-4.185,0l-10.181,10.18a.781.781,0,0,0-.186.3l-1.546,4.638a.773.773,0,0,0,.733,1.017.785.785,0,0,0,.244-.039l4.638-1.546a.773.773,0,0,0,.3-.187L118.547,5.055A2.959,2.959,0,0,0,118.547.87Zm-1.093,3.092L107.405,14.011l-3,1,1-3L115.455,1.967a1.412,1.412,0,1,1,2,2Z" transform="translate(-102.409 -0.004)" fill="#fff"/>
                           </g>
                         </g>
                       </g>
                     </g>
                   </g>
                 </svg>
               </button>
            </td>
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
             </td>
          </tr>
      </tbody>
    </table>
    <ModalTableItems 
        :colums="colums"
        :campoName="columNameReactive"
        :idCampo="idColumReactive"
        :activo_id="activoId"
        :tipo_inputs="tipo_inputs"
        :tipoActivo="tipoActivo"
        :show="dinamicModal" 
        @close="closeDinamicModal"/>

</template>
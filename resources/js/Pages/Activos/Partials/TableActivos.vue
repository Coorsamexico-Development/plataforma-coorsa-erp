<script setup>
import "@fancyapps/ui/dist/fancybox.css";
import { Inertia } from "@inertiajs/inertia";
import CarruselUsers from  '../Partials/CarruselUsers.vue';
import ModalEditItem from '../Partials/ModalEditIem.vue';
import TableButton from "./TableButton.vue";
import ColumText from "./ColumText.vue";
import ColumFile from "./ColumFile.vue";
import { ref } from "@vue/reactivity";
import iziToast from 'izitoast';
import 'izitoast/dist/css/iziToast.css';
import { Fancybox } from "@fancyapps/ui";
import "@fancyapps/ui/dist/fancybox.css";
import { index } from "d3-array";
import axios from "axios";
import ModalTableShowItems from "./ModalTableShowItems.vue";

const emit = defineEmits(["axios" ])

var props = defineProps(
    {
        activos:Object,
        campos:Object,
        tipo_evidencias:Object,
        allcampos:Object,
        tipo_inputs:Object,
        tipoActivo:Object
    }
);

const desactivarItem = (activos_empleados,id) => 
{
    if(activos_empleados.length  > 0)
    {
       //alert("Hay aun usuarios asignados");
       iziToast.warning({
          theme: 'danger',
          title: 'Advertencia',
          message: 'Aun existen usuarios asociados a este activo.',
          position: 'topRight',
       });
    }
    else
    {
      Inertia.post(route('changeStatusActivoItem', id),{},
      {
        preserveScroll:true,
        preserveState:true,
        onSuccess: close()
      });
    }
   //
}


const modalEditItem = ref(false);
let activoModal = ref(null);
let valores_activos = ref([]);
const openModalEditItem = (activo) =>
{
   activoModal.value = activo;
   /*
   axios.get('/valorCampo/'+activo.id).then((response)=> 
    {
        valores_activos.value = response.data
    });
  */
   modalEditItem.value = true;
}

const closeModalEditItem = () =>
{
  modalEditItem.value = false;
  valores_activos.value = [];
}

const emitAxios = (id) => 
{
   emit("axios", id);
}

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

const modalTable = ref(false);
const campoReactive = ref(null);
const camposReactive = ref(null);
const openModalTable = (campo) => 
{
   campoReactive.value = campo;
   axios.get('/columnasxCampo/'+campoReactive.value.idCampo).then((response)=> 
    {
        //console.log(response.data);
        camposReactive.value = response.data;
    });
   modalTable.value = true;
}

const closeModalTable = () => 
{
  modalTable.value = false;
}
</script>
<template>
     <table class="w-full table-auto md:table-fixed">
        <thead class="border-b-2 border-[#707070]  font-extralight " style="" >
            <tr>
                <th><span class="text-sm font-extralight uppercase"></span></th>
                <th><span class="text-sm font-extralight uppercase" style="letter-spacing:0.15rem">Fecha de alta</span></th>
                <th><span class="text-sm font-extralight uppercase" style="letter-spacing:0.15rem">Status</span></th>
                <th v-for="campo in campos" :key="campo.id">
                  <span class="text-sm font-extralight uppercase" style="letter-spacing:0.15rem">
                    {{ campo.campo }}
                  </span>
                </th>
                <th><span class="text-sm font-extralight uppercase" style="letter-spacing:0.15rem">Documento</span></th>
                <th><span class="text-sm font-extralight uppercase" style="letter-spacing:0.15rem">Usuarios</span></th>
            </tr>
        </thead>
        <tbody>
            <tr class="text-center" v-for="activo in activos" :key="activo.id" :class="{'bg-gray-300': !activo.status , 'bg-gray-100': activo.status }">
               <td class="">
                  <div class="flex flex-row justify-center">
                    <button @click=" desactivarItem(activo.activos_empleados,activo.id)"  class="bg-[#ec677e] m-2 rounded-2xl pl-2 pr-2">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="32" height="35" viewBox="0 0 32 35">
                          <defs>
                            <clipPath id="clip-Icono-eliminar">
                              <rect width="32" height="35"/>
                            </clipPath>
                          </defs>
                          <g id="Icono-eliminar" clip-path="url(#clip-Icono-eliminar)">
                            <rect width="32" height="35" fill="rgba(255,255,255,0)"/>
                            <g id="eliminar" transform="translate(7.003 6.001)">
                              <path id="Trazado_318" data-name="Trazado 318" d="M222.939,154.7a.541.541,0,0,0-.541.541v10.221a.541.541,0,0,0,1.082,0V155.244A.541.541,0,0,0,222.939,154.7Zm0,0" transform="translate(-210.374 -146.338)" fill="#fff"/>
                              <path id="Trazado_319" data-name="Trazado 319" d="M104.939,154.7a.541.541,0,0,0-.541.541v10.221a.541.541,0,0,0,1.082,0V155.244A.541.541,0,0,0,104.939,154.7Zm0,0" transform="translate(-98.756 -146.338)" fill="#fff"/>
                              <path id="Trazado_320" data-name="Trazado 320" d="M1.533,6.873V20.2a2.984,2.984,0,0,0,.793,2.058,2.663,2.663,0,0,0,1.932.835H14.49a2.663,2.663,0,0,0,1.932-.835,2.984,2.984,0,0,0,.793-2.058V6.873a2.066,2.066,0,0,0-.53-4.063H13.917V2.135A2.124,2.124,0,0,0,11.775,0h-4.8A2.124,2.124,0,0,0,4.832,2.135v.676H2.063a2.066,2.066,0,0,0-.53,4.063ZM14.49,22.009H4.258A1.713,1.713,0,0,1,2.614,20.2V6.921h13.52V20.2A1.713,1.713,0,0,1,14.49,22.009ZM5.913,2.135A1.042,1.042,0,0,1,6.973,1.08h4.8a1.042,1.042,0,0,1,1.06,1.055v.676H5.913ZM2.063,3.893H16.686a.973.973,0,1,1,0,1.947H2.063a.973.973,0,1,1,0-1.947Zm0,0" transform="translate(0 0)" fill="#fff"/>
                              <path id="Trazado_321" data-name="Trazado 321" d="M163.939,154.7a.541.541,0,0,0-.541.541v10.221a.541.541,0,0,0,1.082,0V155.244A.541.541,0,0,0,163.939,154.7Zm0,0" transform="translate(-154.565 -146.338)" fill="#fff"/>
                            </g>
                          </g>
                        </svg>
                    </button>
                    <button @click="openModalEditItem(activo)" class="bg-[#f28c00] m-2 rounded-2xl pl-2 pr-2">
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
                  </div>
               </td>
               <td>
                  {{ activo.fecha }}
               </td>
               <td >
                  <p :class="{'text-red-600': !activo.status , 'text-green-500': activo.status }">
                    <span v-if="activo.status">
                       Activo
                    </span>
                    <span v-if="!activo.status">
                      Inactivo
                    </span>
                  </p>
               </td>
               <td v-for="campo in campos" :key="campo.id">
                  <div v-if="activo.valor_campos_activos.length > 0"> <!--Si existen uno o mas-->
                     <div v-for="valor in activo.valor_campos_activos" :key="valor.id">
                         <component  :is="setComponent(campo.input)" 
                         @openModalTableCampos="openModalTable(campo)" :valor="valor"  :campo="campo" />
                     </div>
                  </div>
                  <div v-else>                
                  </div>
               </td>
               <ModalTableShowItems :campo="campoReactive" :campos="camposReactive" :show="modalTable" @close="closeModalTable" />
               <td>
                 <div class="flex justify-center" v-if="activo.evidencias_activo.length > 0 " >
                  <div v-for="(image,index) in activo.evidencias_activo" :key="image.id">
                    <button v-if="index == 0" class="bg-[#0097F2] pl-2 pr-2 rounded-3xl block" :href="image.imagen" :data-fancybox="'gallery-'+activo.id">
                      <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="38" height="35" viewBox="0 0 38 35">
                    <defs>
                      <clipPath id="clip-Icono-imagen">
                        <rect width="38" height="35"/>
                      </clipPath>
                    </defs>
                    <g id="Icono-imagen" clip-path="url(#clip-Icono-imagen)">
                      <rect width="38" height="35" fill="rgba(255,255,255,0)"/>
                      <g id="Grupo_514" data-name="Grupo 514" transform="translate(-7685.123 -2906)">
                        <g id="imagen" transform="translate(7687.123 2904.5)">
                          <g id="Grupo_452" data-name="Grupo 452" transform="translate(4 7.5)">
                            <path id="Trazado_283" data-name="Trazado 283" d="M27.7,30.96H7.112A3.116,3.116,0,0,1,4,27.848V10.612A3.116,3.116,0,0,1,7.112,7.5H27.7a3.116,3.116,0,0,1,3.112,3.112V27.848A3.116,3.116,0,0,1,27.7,30.96ZM7.112,8.936a1.678,1.678,0,0,0-1.676,1.676V27.848a1.678,1.678,0,0,0,1.676,1.676H27.7a1.678,1.678,0,0,0,1.676-1.676V10.612A1.678,1.678,0,0,0,27.7,8.936Z" transform="translate(-4 -7.5)" fill="#fff"/>
                          </g>
                          <g id="Grupo_453" data-name="Grupo 453" transform="translate(7.296 15.737)">
                            <path id="Trazado_284" data-name="Trazado 284" d="M28.469,36.576H12.561A1.675,1.675,0,0,1,11.2,33.917L17.517,25.2a1.2,1.2,0,0,1,1.939,0l3.354,4.63,1.059-1.462a1.2,1.2,0,0,1,1.939,0l4.018,5.548a1.676,1.676,0,0,1-1.357,2.659ZM18.486,26.31l-6.119,8.45a.239.239,0,0,0,.194.379H28.469a.239.239,0,0,0,.194-.38l-3.825-5.282-1.06,1.463a1.2,1.2,0,0,1-1.939,0Z" transform="translate(-10.884 -24.705)" fill="#fff"/>
                          </g>
                          <g id="Grupo_454" data-name="Grupo 454" transform="translate(20.757 10.373)">
                            <path id="Trazado_285" data-name="Trazado 285" d="M42.591,20.682a3.591,3.591,0,1,1,3.591-3.591A3.595,3.595,0,0,1,42.591,20.682Zm0-5.745a2.154,2.154,0,1,0,2.154,2.154A2.157,2.157,0,0,0,42.591,14.936Z" transform="translate(-39 -13.5)" fill="#fff"/>
                          </g>
                        </g>
                      </g>
                    </g>
                      </svg>
                   </button>
                    <button  v-if="index > 0"  class="bg-[#0097F2] pl-2 pr-2 rounded-3xl hidden" :href="image.imagen" :data-fancybox="'gallery-'+activo.id">
                      <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="38" height="35" viewBox="0 0 38 35">
                    <defs>
                      <clipPath id="clip-Icono-imagen">
                        <rect width="38" height="35"/>
                      </clipPath>
                    </defs>
                    <g id="Icono-imagen" clip-path="url(#clip-Icono-imagen)">
                      <rect width="38" height="35" fill="rgba(255,255,255,0)"/>
                      <g id="Grupo_514" data-name="Grupo 514" transform="translate(-7685.123 -2906)">
                        <g id="imagen" transform="translate(7687.123 2904.5)">
                          <g id="Grupo_452" data-name="Grupo 452" transform="translate(4 7.5)">
                            <path id="Trazado_283" data-name="Trazado 283" d="M27.7,30.96H7.112A3.116,3.116,0,0,1,4,27.848V10.612A3.116,3.116,0,0,1,7.112,7.5H27.7a3.116,3.116,0,0,1,3.112,3.112V27.848A3.116,3.116,0,0,1,27.7,30.96ZM7.112,8.936a1.678,1.678,0,0,0-1.676,1.676V27.848a1.678,1.678,0,0,0,1.676,1.676H27.7a1.678,1.678,0,0,0,1.676-1.676V10.612A1.678,1.678,0,0,0,27.7,8.936Z" transform="translate(-4 -7.5)" fill="#fff"/>
                          </g>
                          <g id="Grupo_453" data-name="Grupo 453" transform="translate(7.296 15.737)">
                            <path id="Trazado_284" data-name="Trazado 284" d="M28.469,36.576H12.561A1.675,1.675,0,0,1,11.2,33.917L17.517,25.2a1.2,1.2,0,0,1,1.939,0l3.354,4.63,1.059-1.462a1.2,1.2,0,0,1,1.939,0l4.018,5.548a1.676,1.676,0,0,1-1.357,2.659ZM18.486,26.31l-6.119,8.45a.239.239,0,0,0,.194.379H28.469a.239.239,0,0,0,.194-.38l-3.825-5.282-1.06,1.463a1.2,1.2,0,0,1-1.939,0Z" transform="translate(-10.884 -24.705)" fill="#fff"/>
                          </g>
                          <g id="Grupo_454" data-name="Grupo 454" transform="translate(20.757 10.373)">
                            <path id="Trazado_285" data-name="Trazado 285" d="M42.591,20.682a3.591,3.591,0,1,1,3.591-3.591A3.595,3.595,0,0,1,42.591,20.682Zm0-5.745a2.154,2.154,0,1,0,2.154,2.154A2.157,2.157,0,0,0,42.591,14.936Z" transform="translate(-39 -13.5)" fill="#fff"/>
                          </g>
                        </g>
                      </g>
                    </g>
                      </svg>
                   </button>
                  </div>
                 </div>
               </td>
               <td>
                  <CarruselUsers :activo_id="activo.id" :usuarios="activo.activos_empleados" :status="activo.status" @emit-axios="emitAxios(activo.tipo_activo)"></CarruselUsers>
               </td>
            </tr>
        </tbody>
    </table>
    <ModalEditItem :tipoActivo="tipoActivo" :tipo_evidencias="tipo_evidencias"  :tipo_inputs="tipo_inputs" :activo="activoModal" :campos="allcampos" :show="modalEditItem"  @close="closeModalEditItem"></ModalEditItem>
</template>
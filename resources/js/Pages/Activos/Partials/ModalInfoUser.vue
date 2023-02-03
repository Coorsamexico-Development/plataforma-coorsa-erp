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
import { Inertia } from "@inertiajs/inertia";
import axios from 'axios';
import { Fancybox } from "@fancyapps/ui";
import "@fancyapps/ui/dist/fancybox.css";

const emit = defineEmits(["close", "axios"]);

var props = defineProps(
    {
        usuario:Object,
    }
);

const images = ref([]);

const close = () => 
{
    emit('close');
};

const emitAxios = () =>
{
  emit('axios');
}

 let infoUser = ref([]);
 axios.get('/getInfoEmpleado/'+props.usuario.id).then((response)=> 
 {
      //console.log(response);
      infoUser.value = response.data;
 });

 const desactivarEmpleadoActivo = () =>
 {
    //console.log(props.usuario.id)
    Inertia.post(route('changeStatusActivoEmpleado', props.usuario.id),{},{
      preserveScroll:true,
      preserveState:true,
      onSuccess: close(),
      onFinish:emitAxios, close
    });
 }

 axios.get('/getImages/'+props.usuario.id).then((response)=> 
     {
      //console.log(response);
      images.value = response.data;
     });
</script>
<template>
         <DialogModal  @close="close()">
           <template #title>
               <h2 style="font-weight:bolder"></h2>
            </template>
            <template #content>
                 <div class="grid grid-cols-3">
                   <div class="flex flex-col justify-center ">
                     <img class="w-40 h-40 rounded-full" :src="usuario.fotografia" :alt="usuario.name" />
                     <div class="mt-2"  >
                        <button  data-fancybox="gallery-b" class="bg-[#0097F2] pl-2 pr-2 rounded-3xl"  >
                           <svg  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="38" height="35" viewBox="0 0 38 35">
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
                        <div class="hidden">
                           <img  v-for="image in images" :key="image.id" data-fancybox="gallery-b"   :src="image.foto"/>
                        </div>
                        <button class="bg-[#ec677e] m-2 rounded-2xl pl-2 pr-2" @click="desactivarEmpleadoActivo">
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
                     </div>
                   </div>
                   <div class="flex flex-col col-start-2 col-end-4 ml-8" style="font-family: 'Montserrat';">
                      <h1 class="text-xl">
                        Numero de empleado: 
                        <span class="font-bold">
                            {{ usuario.numero_empleado }}
                        </span>
                      </h1>
                      <h2 class="mt-4 text-lg"><span class="font-semibold">Nombre:</span> <span>{{ usuario.name + ' ' + usuario.apellido_paterno + ' ' + usuario.apellido_materno}}</span></h2>
                      <h2 class="text-lg" v-if="infoUser[0]"><span class="font-semibold" v-if="infoUser.length > 0">Puesto:{{ infoUser[0].puesto }} </span></h2>
                      <h2 class="text-lg " v-if="infoUser[0]"><span class="font-semibold" v-if="infoUser.length > 0">Departamento: {{ infoUser[0].departamento }}</span></h2>
                      <h2 class="text-lg" v-if="infoUser[0]">
                        <span class="font-semibold" v-if="infoUser.length > 0 ">Ubicación:</span>
                        {{ infoUser[0].ubicacion }}
                      </h2>
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

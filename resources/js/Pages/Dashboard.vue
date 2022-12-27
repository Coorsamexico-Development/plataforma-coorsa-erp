<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Inertia } from '@inertiajs/inertia'
import CardMenu from './PartialsDashboard/CardMenu.vue';
import CardNoticias from './PartialsDashboard/CardNoticias.vue';
import CardVideos from './PartialsDashboard/CardVideos.vue';
import CardNominas from './PartialsDashboard/CardNominas.vue';
import CardMV from './PartialsDashboard/CardMV.vue';
import CarruselNoticias from './PartialsDashboard/CarruselNoticias.vue';
import MenuDesplegable from './PartialsDashboard/MenuDesplegable.vue';
import ButtonAdd from '@/Components/ButtonAdd.vue';
import { onMounted, reactive, ref, watch } from 'vue';
import ModalAddNoticia from './PartialsDashboard/ModalAddNoticia.vue';

var props = defineProps({
    menus:Object,
    noticias:Object,
    videos:Object
});

let modalAddNoticia = ref(false);

const openModalAgregarNoticia  = () => 
{
   modalAddNoticia.value = true
}

const closeModalAgregarNoticia = () => 
{
  modalAddNoticia.value = false;
}


</script>

<template>
    <AppLayout title="Dashboard">
      <section class="inicio">
        <div class="inicio_info">
            <div class="ml-16 inicio_info_text">
               <h1 class="m-5 text-6xl font-bold text-white">Somos más que <br> un TEAM.</h1>
               <h3 class="m-5 text-4xl text-white"><span class="underline decoration-orange-500">Conoce</span> más sobre coorsa</h3>
            </div>
        </div>
        <div class="inicio_quienes_somos">
          <div class="inicio_quienes_somos_title">
            <h3 class="text-[#EC2944] font-bold text-5xl" style="font-weight:bold">QUIENES</h3>
            <h3 class="text-[#1D2B4E] font-bold text-9xl"><strong>SOMOS</strong></h3>
          </div>
          <div class="inicio_quienes_somos_text">
             <p class="text-2xl">
                 <strong>COORSA</strong> es una empresa dedicada a innovar y<br>
                 mejorar procesos de la cadena de suministros con<br>
                 más de 10 servicios dentro  del rango del ramo logístico<br>
                 ubicados en más de 9 estados de la república<br>
                 mexicana.
             </p>
          </div>
        </div>
      </section>
      <section class="inicio_mv">
              <div class="flex flex-col items-center align-middle inicio_m">
                <img class="mt-24 icon_m" src="../../img/Icono_Mision.png">
                <div>
                   <h3 class="mt-10 text-3xl text-white">Misión</h3>
                   <p class="mt-5 text-2xl text-white">
                     Eficientar los procesos de la cadena de<br>
                     suministros, a través de servicios de alta calidad<br>
                     que generan ambientes de innovación logística, <br>
                     haciendo de lo bueno algo mejor
                   </p>
                </div>
              </div>
              <div class="flex flex-col items-center inicio_v">
                 <img class="mt-24 icon_v" src="../../img/Icono_Vision.png">
                 <div>
                   <h3 class="mt-10 text-3xl text-white">Visión</h3>
                   <p class="mt-5 mb-16 text-2xl text-white">
                     Ser la empresa lider en soluciones logísticas<br>
                     en México y en otros países, innovando y <br>
                     creando procesos en la cadena de suministros,<br>
                     convirtiéndonos en socios estrátegicos de <br>
                     nuestros clientes.
                   </p>
                </div>
              </div>
      </section>
      <section>
        <div class="bg-[#1A1A22]">
            <div class="pt-20 pb-48 ml-48">
              <h2 class="text-[#EC2944] font-bold text-5xl mt-16">NUESTRA</h2>
              <h1 class="font-bold text-white text-8xl">COMUNIDAD</h1>
            </div>
        </div>
        <div class="flex justify-center justify-items-center" style="margin-top:-7rem">
          <iframe width="980" height="550" src="https://www.youtube.com/embed/Vrp248tXNb0" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>
        <div class="p-16 bg-white ">
          
        </div>
      </section>
      <section>
        <ButtonAdd  @click="openModalAgregarNoticia">Agregar</ButtonAdd>
        <ModalAddNoticia :show="modalAddNoticia" @close="closeModalAgregarNoticia "></ModalAddNoticia>
      </section>
    </AppLayout>
</template>

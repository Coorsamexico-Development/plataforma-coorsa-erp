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
               <h1 class="m-5 text-6xl  text-white" style="font-family: 'Montserrat'; font-weight: 600;">Somos más que <br> un TEAM.</h1>
               <h3 class="m-5 text-4xl text-white"><span class="underline decoration-orange-500" style="font-family: 'Montserrat';">Conoce</span> más sobre coorsa</h3>
            </div>
        </div>
        <div class="inicio_quienes_somos">
          <div class="inicio_quienes_somos_title">
            <h3 class="text-[#EC2944] font-bold text-5xl" style="font-family: 'Montserrat';font-weight:500">QUIENES</h3>
            <h3 class="text-[#1D2B4E] font-bold text-9xl" style=" font-family: 'Montserrat'; font-weight:900"><strong>SOMOS</strong></h3>
          </div>
          <div class="inicio_quienes_somos_text">
             <p class="text-2xl" style="font-family: 'Montserrat'; ">
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
                   <h3 class="mt-10 text-3xl text-white" style="font-family: 'Montserrat'; ">Misión</h3>
                   <p class="mt-5 text-2xl text-white" style="font-family: 'Montserrat';">
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
                   <h3 class="mt-10 text-3xl text-white" style="font-family: 'Montserrat'; ">Visión</h3>
                   <p class="mt-5 mb-16 text-2xl text-white" style="font-family: 'Montserrat'; ">
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
        <div class="video_section">
            <div class="pt-20 pb-48 ml-48">
              <h2 class="text-[#EC2944] font-500 text-5xl mt-16" style="font-family: 'Montserrat'; ">NUESTRA</h2>
              <h1 class="font-bold text-white text-8xl" style="font-family: 'Montserrat';">COMUNIDAD</h1>
            </div>
        </div>
        <div class="flex justify-center justify-items-center" style="margin-top:-7rem">
          <iframe width="980" height="550" src="https://www.youtube.com/embed/Vrp248tXNb0" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>
        <div class="p-16 bg-white ">
          
        </div>
      </section>
      <section>
        <div class="grid grid-cols-3 m-4 gap-x-8">
           <CardNoticias  v-for="noticia in noticias" :key="noticia.id" :noticia="noticia"></CardNoticias> 
        </div>
        <ButtonAdd v-if="$page.props.can['noticias.create']" style="float:right" class="mr-8"  @click="openModalAgregarNoticia">Agregar</ButtonAdd>
        <ModalAddNoticia :show="modalAddNoticia" @close="closeModalAgregarNoticia "></ModalAddNoticia>
      </section>
      <section class="grid grid-rows-3 mt-28">
        <div class="ml-16 ">
           <h2 class="text-[#EC2944] text-5xl font-semibold" style="font-family: 'Montserrat';">ELLOS HABLAN</h2>
           <h1 class="text-[#1A1A22] font-extrabold text-8xl" style="font-family: 'Montserrat'; ">POR NOSOTROS</h1>
        </div>
        <div class="grid grid-cols-3" style="margin-top:-6rem">
           <div class="flex flex-col items-center pt-9 pb-9">
               <img class="pt-2 rounded-full h-28 w-18" src="../../img/FOTO_PAOLA.png">
               <h2 class="text-[#1A1A22] mt-12 font-bold text-center" style="font-family: 'Montserrat'; ">Paola Granados</h2>
               <h4 class="text-[#1A1A22] text-center font-semibold" style="font-family: 'Montserrat'; ">ANALISTA DE INSUMOS</h4>
               <span class="w-16 h-1 bg-[#EC2944]"></span>
               <p class="text-[#1A1A22] pb-3 mt-3 text-center" style="font-family: 'Montserrat'; ">"Durante este tiempo me he<br>
                sentido muy agusto, son una<br>
                empresa joven y tienen facilidad de<br>
                crecimiento laboral y personal."</p>
           </div>
           <div class="bg-[#1A1A22] flex flex-col items-center pt-9 pb-9">
            <img class="pt-2 rounded-full h-28 w-18" src="../../img/FOTO_LESLYE.png">
               <h2 class="mt-12 font-bold text-center text-white" style="font-family: 'Montserrat';">Leslye Gallardo</h2>
               <h4 class="font-semibold text-center text-white" style="font-family: 'Montserrat';">JEFA DE PATIO</h4>
               <span class="w-16 h-1 bg-[#EC2944]"></span>
               <p class="pb-3 mt-3 text-center text-white" style="font-family: 'Montserrat'; ">"Siempre voy a estar muy<br>
                agradecida por la oportunidad, por<br>
                creer y confiar en mi trabajo y en<br>
                mí, somos como una familia."</p>
           </div>
           <div class="flex flex-col items-center pt-9 pb-9">
            <img class="pt-2 rounded-full h-28 w-18" src="../../img/FOTO_IVONNE.png">
               <h2 class="text-[#1A1A22] mt-12 font-bold text-center" style="font-family: 'Montserrat';">Ivonne Torres</h2>
               <h4 class="text-[#1A1A22] text-center font-semibold" style="font-family: 'Montserrat';">PROCESS MANAGER JR</h4>
               <span class="w-16 h-1 bg-[#EC2944]"></span>
               <p class="text-[#1A1A22] text-center mt-3 pb-10" style="font-family: 'Montserrat';">
                "Buscamos que nuestro equipo de<br>
                trabajo tanto coorporativo como<br>
                operativo se sientan cómodos y<br>
                tengan un crecimiento."</p>
           </div>
        </div>
        <div class="flex flex-col items-center justify-center">
          <h3 class="text-5xl font-bold" style="font-family: 'Montserrat';">
            <span class="text-[#1A1A22] ">HACIENDO DE LO BUENO, </span>
            <span class="text-[#EC2944]">ALGO MEJOR</span>
          </h3>
          <h2 style="font-family: 'Montserrat';" class="text-[#1A1A22] text-2xl mt-4"><a href="https://coorsamexico.com/">WWW.COORSAMEXICO.COM</a></h2>
        </div>
      </section>
    </AppLayout>
</template>

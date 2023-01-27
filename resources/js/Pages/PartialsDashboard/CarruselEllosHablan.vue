<script >
// import Swiper core and required modules
import { Navigation, Pagination, Scrollbar, A11y, Autoplay } from 'swiper';
// Import Swiper Vue.js components
import { Swiper, SwiperSlide } from 'swiper/vue';
// Import Swiper styles
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import 'swiper/css/scrollbar';
import 'swiper/css/autoplay';

// Import Swiper styles
export default {
  components: {
    Swiper,
    SwiperSlide,
  },
  setup() {
    const onSwiper = (swiper) => {
     
    };
    const onSlideChange = () => {
      
    };
    return {
      onSwiper,
      onSlideChange,
      modules: [Navigation, Pagination, A11y, Autoplay],
    };
  },
};
</script>
<template>
 <swiper
 :modules="modules"
    :slides-per-view="1"
    :space-between="50"
    :autoplay='{
               "delay": 6000,
              "disableOnInteraction": false
     }'
    :pagination="{ clickable: true }"
    @swiper="onSwiper"
    @slideChange="onSlideChange"
  >
  <swiper-slide> 
    <div class="bg-white">
        <div class="pt-8 pl-4 pb-2"> 
            <h2 class="text-[#1A1A22] text-xl font-semibold" style="font-family: 'Montserrat';">ELLOS HABLAN</h2>
            <h1 class="text-[#EC2944] font-extrabold text-2xl" style="font-family: 'Montserrat'; ">POR NOSOTROS</h1>
        </div>
        <div class="flex flex-col items-center pt-9 pb-9">
              <img src="../../../img/FOTO_PAOLA.png">
              <h2 class="mt-4 font-bold text-center text-xl text-[#1A1A22] " style="font-family: 'Montserrat'; letter-spacing:0.2rem ">Paola Granados</h2>
               <h4 class="font-semibold text-center mt-1 text-[#1A1A22] " style="font-family: 'Montserrat';letter-spacing:0.3rem ">ANALSITA DE INSUMOS</h4>
               <span class="w-16 h-1 mt-2 bg-[#EC2944]"></span>
               <p class="pb-3 mt-3 text-center text-sm text-[#1A1A22] " style="font-family: 'Montserrat'; line-height: 1.8;">
                "Durante este tiempo me he<br>
                sentido muy agusto, son una<br>
                empresa joven y tienen facilidad de<br>
                crecimiento laboral y personal."</p>
        </div>
    </div>
  </swiper-slide>
  <swiper-slide> 
    <div class="bg-[#1A1A22]">
        <div class="pt-8 pl-4 pb-2"> 
            <h2 class="text-white text-xl font-semibold" style="font-family: 'Montserrat';">ELLOS HABLAN</h2>
            <h1 class="text-[#EC2944] font-extrabold text-2xl" style="font-family: 'Montserrat'; ">POR NOSOTROS</h1>
        </div>
        <div class="flex flex-col items-center pt-9 pb-9">
              <img src="../../../img/FOTO_LESLYE.png">
              <h2 class="mt-4 font-bold text-center text-xl text-white" style="font-family: 'Montserrat'; letter-spacing:0.2rem ">Leslye Gallardo</h2>
               <h4 class="font-semibold text-center mt-1 text-white" style="font-family: 'Montserrat';letter-spacing:0.3rem ">JEFA DE PATIO</h4>
               <span class="w-16 h-1 mt-2 bg-[#EC2944]"></span>
               <p class="pb-3 mt-3 text-center text-sm text-white" style="font-family: 'Montserrat'; line-height: 1.8;">
                "Siempre voy a estar muy
                agradecida por la<br> oportunidad, por
                creer y confiar en mi trabajo<br> y en
                mí, somos como una familia."</p>
        </div>
    </div>
  </swiper-slide>
  <swiper-slide> 
    <div class="bg-white">
        <div class="pt-8 pl-4 pb-2"> 
            <h2 class="text-[#1A1A22] text-xl font-semibold" style="font-family: 'Montserrat';">ELLOS HABLAN</h2>
            <h1 class="text-[#EC2944] font-extrabold text-2xl" style="font-family: 'Montserrat'; ">POR NOSOTROS</h1>
        </div>
        <div class="flex flex-col items-center pt-9 pb-9">
              <img src="../../../img/FOTO_IVONNE.png">
              <h2 class="mt-4 font-bold text-center text-xl text-[#1A1A22] " style="font-family: 'Montserrat'; letter-spacing:0.2rem ">Ivonne Torres</h2>
               <h4 class="font-semibold text-center mt-1 text-[#1A1A22] " style="font-family: 'Montserrat';letter-spacing:0.3rem ">PROCESS MANAGER JR</h4>
               <span class="w-16 h-1 mt-2 bg-[#EC2944]"></span>
               <p class="pb-3 mt-3 text-center text-sm text-[#1A1A22] " style="font-family: 'Montserrat'; line-height: 1.8;">
                "Buscamos que nuestro equipo de<br>
                trabajo tanto coorporativo como<br>
                operativo se sientan cómodos y<br>
                tengan un crecimiento."</p>
        </div>
    </div>
  </swiper-slide>
  </swiper>
  <div class="flex flex-col items-center justify-center mt-8 mb-8 pl-2 pr-2">
     <h3 class="text-2xl font-semibold text-center" style="font-family: 'Montserrat';">
       <span class="text-[#1A1A22] ">HACIENDO DE LO BUENO, </span>
       <span class="text-[#EC2944]">ALGO MEJOR</span>
     </h3>
     <h2 style="font-family: 'Montserrat'; letter-spacing:0.4rem; margin-top: 3rem;" class="text-[#1A1A22] text-xs mt-4"><a href="https://coorsamexico.com/">WWW.COORSAMEXICO.COM</a></h2>
  </div>
</template>
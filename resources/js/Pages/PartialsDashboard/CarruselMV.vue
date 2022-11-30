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
               "delay": 3000,
              "disableOnInteraction": false
     }'
    :pagination="{ clickable: true }"
    :scrollbar="{ draggable: true }"
    @swiper="onSwiper"
    @slideChange="onSlideChange"
  >
  <swiper-slide style=""> 
      <div style="margin:2rem;">
          <h2 style="color:#26458D; font-weight:bolder; margin-bottom: 1rem;">¿Quiénes Somos?</h2>
          <h3 style="text-align:center;">
            Somos un equipo de profesionales altamente comprometido en dar soluciones lógisticas,
            somos reconocidos por innovar procesos en la cadena de suministros, optimizando
            tiempos y recursos operativos de: almacenamiento, traslado y control de inventarios.
          </h3>
       </div>
    </swiper-slide>
    <swiper-slide>
       <div style="margin: 2rem;">
          <h2 style="color:#26458D; font-weight:bolder; margin-bottom: 1rem;">Nuestra Misión</h2>
          <h3 style="text-align:center;">Ser la empresa líder en soluciones 
            logísticas en México y en otros países, innovando y 
            creando procesos en la cadena de suministros, 
            convirtiéndonos en socios estrátegicos de nuestros clientes.
          </h3>
       </div>
    </swiper-slide>
    <swiper-slide> 
      <div style="margin:2rem;">
          <h2 style="color:#26458D; font-weight:bolder; margin-bottom: 1rem;">Nuestra Visión</h2>
          <h3 style="text-align:center;">
            Eficientar los procesos de la cadena de suministros, 
            a través de servicios de alta calidad que 
            generen ambientes de innovación logística, haciendo de lo bueno algo mejor.
          </h3>
       </div>
    </swiper-slide>
  </swiper>
</template>
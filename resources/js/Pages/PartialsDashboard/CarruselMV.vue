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
    navigation
    :autoplay='{
               "delay": 2500,
              "disableOnInteraction": false
     }'
    :pagination="{ clickable: true }"
    :scrollbar="{ draggable: true }"
    @swiper="onSwiper"
    @slideChange="onSlideChange"
  >
    <swiper-slide>
        <img src="https://blog.ida.cl/wp-content/uploads/sites/5/2020/04/tamano-redes-blog-655x470.png">
    </swiper-slide>
    <swiper-slide> 
        <img src="https://www.40defiebre.com/wp-content/uploads/2015/10/imagenes.png">
    </swiper-slide>
   

  </swiper>
</template>
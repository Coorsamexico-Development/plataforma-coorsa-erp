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
  props: {
        noticias: Object,
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
               "delay": 6000,
              "disableOnInteraction": false
     }'
    :pagination="{ clickable: true }"
    :scrollbar="{ draggable: true }"
    @swiper="onSwiper"
    @slideChange="onSlideChange"
  >
  
    <swiper-slide v-for="noticia in noticias" :key="noticia.id">
       <div style="display: flex; justify-content: center;">
          <img style=" height:15rem;" :src="noticia.image">
       </div>
    </swiper-slide>
  </swiper>
</template>
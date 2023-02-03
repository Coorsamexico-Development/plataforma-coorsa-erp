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
import ModalAddEmpleadoActivo  from '../Partials/ModalAddEmpleadoActivo.vue';
import ModalInfoUser from '../Partials/ModalInfoUser.vue';
import { Inertia } from "@inertiajs/inertia";

// Import Swiper styles
export default {
  components: {
    Swiper,
    SwiperSlide,
  },

  emits: ['emitAxios'],

  props:
  {
    activo_id:Number,
    usuarios:Object,
    status:Number
  },
  
  data() {
    return { 
        modalEmpleadoActivo: false,
        modalMoreInfo:false
    }
  },

  setup() {
    const onSwiper = (swiper) => 
    {
     
    };
    const onSlideChange = () => 
    {
      
    };
    return {
      onSwiper,
      onSlideChange,
      modules: [Navigation, Pagination, A11y, Autoplay],
    };
  },

  mounted() 
  {
      this.checkUsers();
  },
  methods:
  {
    openModalEmpleadoActivo () 
    {
        this.modalEmpleadoActivo = true;
    },

    closeModalEmpleadoActivo () 
    {
        this.modalEmpleadoActivo = false;
    },

    openInfoUser()
    {
      this.modalMoreInfo = true;
    },

    closeInforUser()
    {
        this.modalMoreInfo = false;
    },

    changeStatus ()
    {
      //console.log("hola");
      //console.log(this.activo_id);
      Inertia.post(route('changeStatusActivoItemLibre', this.activo_id),{},{
      preserveScroll:true,
      preserveState:true,
      onSuccess: close()
    });
    },

    emitAxios()
    {
       //Se aplica esta accion cuando se agrega un usuario
        this.$emit('emitAxios');
       // this.checkUsers();
         //console.log("axios")
    },

    checkUsers()
    {
       
        if(this.usuarios.length > 0 ) // si existe mas de un usuario no hace nada
       {      
         //console.log(this.usuarios.length);
       }
       else //si los usuarios son 0 checamos si el activo sigue activo
       {
         //console.log(this.status);
         if(this.status == 1)
         {
           this.changeStatus();
         }
       }
    }
  },

  components: {ModalAddEmpleadoActivo,ModalInfoUser, SwiperSlide, Swiper}
};
</script>
<template>
<div class="flex flex-col w-94">
    <div>
     <swiper
       :modules="modules"
       :slides-per-view="2"
       :space-between="0"
       :autoplay='{
                  "delay": 6000,
                 "disableOnInteraction": false
        }'
   
       @swiper="onSwiper"
       @slideChange="onSlideChange"
       navigation
     >
      <swiper-slide v-for="usuario in usuarios" :key="usuario.id">  
        <div class="">
            <button @click="openInfoUser()" class="bg-[#1D2B4E] text-white pl-2 pr-2 rounded-full uppercase">
                {{ usuario.name.charAt(0) }}
            </button>
            <ModalInfoUser :show="modalMoreInfo" :usuario="usuario" @close="closeInforUser" @axios="emitAxios"></ModalInfoUser>
        </div>
      </swiper-slide>
   </swiper>
   <button @click="openModalEmpleadoActivo" class="bg-[#F1687B] text-white pl-2 pr-2 rounded-full absolute -mt-6 ml-20 z-10">
     +
   </button>
 </div>
</div>

<ModalAddEmpleadoActivo :show="modalEmpleadoActivo" :activo_id="$props.activo_id" @close="closeModalEmpleadoActivo" @axios="emitAxios"> </ModalAddEmpleadoActivo>
</template>
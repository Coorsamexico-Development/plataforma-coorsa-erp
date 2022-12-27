<script setup>

import { Inertia } from '@inertiajs/inertia'
import Card from '../../Components/Card.vue';
import { onMounted, reactive, ref, watch } from 'vue';
import moment from 'moment';
import CarruselNoticias from './CarruselNoticias.vue';
import ButtonModal from '../../Components/ButtonModal.vue';
import ModalAddNoticia from './ModalAddNoticia.vue';
import ModalNoticiaDes from './ModalNoticiaDesc.vue';
import ButtonAdd from '@/Components/ButtonAdd.vue';


var props = defineProps({
    noticia: Object
});

const abrirModalDes = ref(false); 

const masInfo = ( ) => 
{
    abrirModalDes.value = true;
}

const closeMasInfo = () =>
{
    abrirModalDes.value =false;
}

</script>
<style>
.fondo 
{
  background-size: cover;
  background-repeat: no-repeat;
}
</style>
<template>
    <Card class="w-full h-96 fondo card"  :style="{ backgroundImage : 'url(' + encodeURI(props.noticia.image) + ')' }">  
      <div class="flex flex-col items-center content-center justify-center mt-40">
        <h3 class="text-xl font-bold text-white" style="z-index:3;">{{ props.noticia.titulo }}</h3>
        <button @click="masInfo" style="z-index:3;" class="p-2 pr-11 pl-11 border-2 border-solid border-[#EC2944] text-white">
          LEER MÁS
        </button>
      </div>
    </Card>
    <ModalNoticiaDes :show="abrirModalDes" @close="closeMasInfo" :descripcion="props.noticia.descripcion"></ModalNoticiaDes>
</template>

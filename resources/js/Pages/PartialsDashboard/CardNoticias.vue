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
import { Fancybox } from "@fancyapps/ui";
import "@fancyapps/ui/dist/fancybox.css";


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
    <Card class="w-full fondo card">  
       <img  class="w-full" style="height:25rem" :src="noticia.image">
       <div class="ml-4" style="font-family: 'Montserrat';">
          <h3 class="mt-8 text-[#1A1A22] font-semibold">{{noticia.titulo}}</h3>
          <p class="mt-2 text-sm font-extralight">{{ noticia.descripcion }}</p>
       </div>
       <ButtonAdd :data-fancybox="'single'+noticia.id"  :data-src="noticia.mas"  class="m-4">Ver más</ButtonAdd>
    </Card>
    <ModalNoticiaDes :show="abrirModalDes" @close="closeMasInfo" :descripcion="props.noticia.descripcion"></ModalNoticiaDes>
</template>

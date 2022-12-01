<script setup>

import { Inertia } from '@inertiajs/inertia'
import Card from '../../Components/Card.vue';
import { onMounted, reactive, ref, watch } from 'vue';
import moment from 'moment';
import CarruselNoticias from './CarruselNoticias.vue';
import ButtonModal from '../../Components/ButtonModal.vue';
import ModalAddNoticia from './ModalAddNoticia.vue';
import ButtonAdd from '@/Components/ButtonAdd.vue';


var props = defineProps({
    noticias: Object
});

let modalNoticia = ref(false);

const showModalNoticia = () => {
    modalNoticia.value = true;
}

const closeModalNoticia = () => {
    modalNoticia.value = false;
}

</script>

<template>
    <Card class="">
       <div class="carrusel_noticias">
           <CarruselNoticias style="margin:1rem" :noticias="noticias"></CarruselNoticias>
       </div>
      
        <ButtonAdd v-if="$page.props.can['noticias.create']" @click="showModalNoticia" style="width:2rem; margin:1rem; padding: 0.5rem 3rem; display: flex; align-content: center; justify-content: center;">
            <strong style="font-size:1.5rem; font-weight:bolder">+</strong>
        </ButtonAdd>
        <ModalAddNoticia  :show="modalNoticia" @close="closeModalNoticia"></ModalAddNoticia>
    </Card>
</template>

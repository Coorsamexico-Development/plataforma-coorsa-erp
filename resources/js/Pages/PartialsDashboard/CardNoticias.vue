<script setup>

import { Inertia } from '@inertiajs/inertia'
import Card from '../../Components/Card.vue';
import { onMounted, reactive, ref, watch } from 'vue';
import moment from 'moment';
import CarruselNoticias from './CarruselNoticias.vue';
import ButtonModal from '../../Components/ButtonModal.vue';
import ModalAddNoticia from './ModalAddNoticia.vue';

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
    <Card style=" width: 24rem;">
        <CarruselNoticias style="margin:1rem" :noticias="noticias"></CarruselNoticias>
        <ButtonModal v-if="$page.props.can['noticias.create']" @click="showModalNoticia">Añadir nueva noticia
        </ButtonModal>
        <ModalAddNoticia :show="modalNoticia" @close="closeModalNoticia"></ModalAddNoticia>
    </Card>
</template>

<script setup>
import { Inertia } from '@inertiajs/inertia'
import Card from '../../Components/Card.vue';
import { onMounted, reactive, ref, watch } from 'vue';
import CarruselVideos from './CarruselVideos.vue';
import ButtonModal from '../../Components/ButtonModal.vue';
import ModalAddVideo from './ModalAddVideo.vue';

var props = defineProps({
    videos: Object
});

let modalVideo = ref(false);

const abrirModalVideo = () => {
    modalVideo.value = true;
}

const closeModalVideo = () => {
    modalVideo.value = false;
}

</script>

<template>
    <Card style=" width: 24rem;  margin:0.5rem; padding: 1.5rem;">
        <h4 style="color:#26458D; font-weight:bolder">Videos Coorsa</h4>
        <CarruselVideos :videos="videos"></CarruselVideos>
        <ButtonModal v-if="$page.props.can['video.create']" @click="abrirModalVideo">Añadir nuevo video</ButtonModal>
        <ModalAddVideo :show="modalVideo" @close="closeModalVideo"></ModalAddVideo>
    </Card>
</template>

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
    <Card>
        <!-- <CarruselVideos :videos="videos"></CarruselVideos> -->
        <!--
        <ButtonModal v-if="$page.props.can['video.create']" @click="abrirModalVideo">Añadir nuevo video</ButtonModal>
        <ModalAddVideo :show="modalVideo" @close="closeModalVideo"></ModalAddVideo>
        -->
        <div style="margin:1rem">
            <iframe width="560" height="315" src="https://www.youtube.com/embed/videoseries?list=PLjSCaE1YbE5-nRJUcFcol3CsjUEw5G8fx" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>
    </Card>
</template>

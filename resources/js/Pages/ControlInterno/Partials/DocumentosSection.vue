<script setup>
import { ref } from 'vue';
import Card from '../../../Components/Card.vue';
import CirculeLogo from '../../../Components/CirculeLogo.vue';
import Title from '../../../Components/Title.vue';
import CardDocumento from '../../../Components/CardDocumento.vue';
import DangerButton from '../../../Components/DangerButton.vue';

import { Fancybox } from "@fancyapps/ui/src/Fancybox/Fancybox.js";

import { Navigation, A11y, Autoplay } from 'swiper';
// Import Swiper Vue.js components
import { Swiper, SwiperSlide } from 'swiper/vue';
// Import Swiper styles
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/effect-creative';
import { useForm } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';



const props = defineProps({
    documentos: {
        type: Object,
        required: true,
    }
});

const modules = ref([Navigation, A11y, Autoplay])

// const onSwiper = (swiper) => {
//     console.log(swiper);
// };
// const onSlideChange = () => {
//     console.log('slide change');
// };


const startFancy = (docUrl) => {

    Fancybox.show([
        {
            src: docUrl,
        },
    ], {}); //starts fancybox with the gallery object
}

const deleteCalificaion = (docCalifacionId) => {
    Inertia.delete(route('documentos-calificacion-mes.destroy', docCalifacionId), {
        preserveScroll: true,
        preserveState: true,
        only: ['calificaciones'],
    })
}


</script>
<template>
    <Card class="py-2">
        <Title class="flex items-center">
            <CirculeLogo url-logo="/assets/img/icono doc 7.png" /> <span>Documentos</span>
        </Title>
        <div class="h-32 px-6 py-4 md:h-48 md:px-12">
            <swiper class="h-full" :modules="modules" :slides-per-view="6" :space-between="20" navigation
                :pagination="{ clickable: true }" :scrollbar="{ draggable: true }">
                <swiper-slide v-for="doc in documentos" :key="'doc' + doc.id">


                    <CardDocumento @click.stop="startFancy(doc.documento_url)" class="relative h-full border"
                        :scr="doc.documento_url">
                        {{ doc.mes }}
                        <DangerButton class="absolute w-6 px-1 py-0 rounded-full shadow top-2 right-1"
                            @click.stop="deleteCalificaion(doc)">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-5 h-5"
                                viewBox="0 0 16 16">
                                <path
                                    d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z" />
                                <path fill-rule="evenodd"
                                    d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" />
                            </svg>
                        </DangerButton>
                    </CardDocumento>
                </swiper-slide>

            </swiper>
        </div>
    </Card>
</template>
<style lang="css" scoped>

</style>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Inertia } from '@inertiajs/inertia'
import CardMenu from './PartialsDashboard/CardMenu.vue';
import CardNoticias from './PartialsDashboard/CardNoticias.vue';
import CardVideos from './PartialsDashboard/CardVideos.vue';
import CardNominas from './PartialsDashboard/CardNominas.vue';
import CardMV from './PartialsDashboard/CardMV.vue';

var props = defineProps({
    menus:Object,
    noticias:Object,
    videos:Object
});

</script>

<template>
    <AppLayout title="Dashboard">
        <template #header >
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Inicio
            </h2>
        </template>
        <div class="cards-container">
          <div class="card_MV">
            <CardMV></CardMV>
          </div>
          <div class="card_menu"> 
            <CardMenu :menus="props.menus"></CardMenu>
          </div>
          <div class="card_noticias"> 
            <CardNoticias style="width:30rem"  :noticias="props.noticias"></CardNoticias>         
          </div>
          <div  class="card_videos">
            <CardVideos :videos="videos" ></CardVideos>
          </div>
          <div  class="card_nominas">
            <CardNominas></CardNominas>
          </div>
        </div>
    </AppLayout>
</template>

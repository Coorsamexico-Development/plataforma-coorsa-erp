<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Inertia } from '@inertiajs/inertia'
import CardMenu from './PartialsDashboard/CardMenu.vue';
import CardNoticias from './PartialsDashboard/CardNoticias.vue';
import CardVideos from './PartialsDashboard/CardVideos.vue';
import CardNominas from './PartialsDashboard/CardNominas.vue';
import CardMV from './PartialsDashboard/CardMV.vue';
import CarruselNoticias from './PartialsDashboard/CarruselNoticias.vue';
import MenuDesplegable from './PartialsDashboard/MenuDesplegable.vue';

var props = defineProps({
    menus:Object,
    noticias:Object,
    videos:Object
});



</script>

<template>
    <AppLayout title="Dashboard">
        <div class="cards-container">
          <div class="card_MV">
              <CardMV></CardMV>
          </div>
          <div class="card_noticias">
             <CardNoticias :noticias="noticias"></CardNoticias>
          </div>
          <div class="card_videos">
             <CardVideos></CardVideos>
          </div>
          <div class="menu_desplegable">
            <MenuDesplegable :menus="menus" ></MenuDesplegable>
          </div>
        </div>
    </AppLayout>
</template>

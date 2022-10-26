<script setup>

import { Inertia } from '@inertiajs/inertia'
import Card from '../../Components/Card.vue';
import { onMounted, reactive, ref, watch } from 'vue';
import moment from 'moment';
import Carrusel from './Carrusel.vue';

var props = defineProps({
    noticias:Object
});


</script>

<template>
    <Card style=" width: 24rem;  margin:0.5rem; padding: 1.5rem;" >
        <h4 style="color:#26458D; font-weight:bolder">Servicios y noticias</h4>
        <Carrusel style="margin:1rem" :noticias="noticias"></Carrusel>
    </Card>
</template>

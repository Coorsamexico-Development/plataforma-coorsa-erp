<script setup>
import { Inertia } from '@inertiajs/inertia'
import Card from '../../Components/Card.vue';
import { onMounted, reactive, ref, watch } from 'vue';
import CarruselVideos from './CarruselVideos.vue';

var props = defineProps({
    videos:Object
});


</script>

<template>
    <Card style=" width: 24rem;  margin:0.5rem; padding: 1.5rem;" >
        <h4 style="color:#26458D; font-weight:bolder">Videos Coorsa</h4>
        <CarruselVideos></CarruselVideos>
    </Card>
</template>

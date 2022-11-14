<script setup>

import { Inertia } from '@inertiajs/inertia'
import Card from '../../Components/Card.vue';
import { onMounted, reactive, ref, watch } from 'vue';
import CarruselMV from './CarruselMV.vue';


var props = defineProps({
   
});

</script>

<template>
    <Card style=" width: 24rem;  margin:0.5rem; padding: 1.5rem;" >
        <CarruselMV></CarruselMV>
    </Card>
</template>

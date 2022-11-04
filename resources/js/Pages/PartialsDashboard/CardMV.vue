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
        <h4 style="color:#26458D; font-weight:bolder; margin-bottom: 1rem;">¿Quiénes Somos?</h4>
        <CarruselMV></CarruselMV>
    </Card>
</template>

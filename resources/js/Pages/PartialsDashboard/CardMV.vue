<script setup>

import { Inertia } from '@inertiajs/inertia'
import Card from '../../Components/Card.vue';
import { onMounted, reactive, ref, watch } from 'vue';
import CarruselMV from './CarruselMV.vue';


var props = defineProps({
   
});

</script>

<template>
    <Card class="card_mv" style="" >
        <CarruselMV></CarruselMV>
    </Card>
</template>

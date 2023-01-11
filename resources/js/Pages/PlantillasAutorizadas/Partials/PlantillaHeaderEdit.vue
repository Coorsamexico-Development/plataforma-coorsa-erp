<script setup>
import TextInput from '@/Components/TextInput.vue';
import { computed } from '@vue/runtime-core';
defineEmits(['updateUbicacion']);
const props = defineProps({
    ubicacion: {
        type: Object,
        required: true
    }
});

const total = computed(() => {
    let totalActivos = 0
    let totalRequeridos = 0
    props.ubicacion.plantillas_autorizadas.forEach(plantilla => {
        totalActivos += plantilla.cantidad_activa;
        totalRequeridos += plantilla.cantidad;
    })
    return totalActivos + '/' + totalRequeridos
})

</script>
<template>
    <th scope="col" class="px-2 py-1 text-xs text-center">
        <TextInput class="text-center" v-model="ubicacion.name" @blur="$emit('updateUbicacion', ubicacion)" />
        <span class="block"> {{ total }} </span>
    </th>
</template>


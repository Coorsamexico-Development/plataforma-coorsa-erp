<script setup>
import ColumnPlantilla from '../../../Components/ColumnPlantilla.vue';

defineEmits(['save']);


const props = defineProps({
    ubicacion: {
        type: Object,
        required: true,
    },
    puestos: {
        type: Object,
        required: true,
    },

})

</script>
<template>
    <tr>
        <td class="px-2 whitespace-nowrap">{{ ubicacion.name }}</td>
        <ColumnPlantilla v-for="puesto in puestos" :key="puesto.id" :ubicacionId="ubicacion.id" :puesto="puesto"
            class="px-2 whitespace-nowrap" @save="$emit('save', $event)" />
    </tr>
</template>

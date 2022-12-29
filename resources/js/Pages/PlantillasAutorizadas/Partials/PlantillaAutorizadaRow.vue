<script setup>
import ColumnPlantilla from '../../../Components/ColumnPlantilla.vue';

defineEmits(['save']);

const props = defineProps({
    ubicaciones: {
        type: Object,
        required: true,
    },
    puesto: {
        type: Object,
        required: true,
    },
});

</script>
<template>
    <tr>
        <td class="px-2 whitespace-nowrap">{{ puesto.name }}</td>
        <ColumnPlantilla v-for="ubicacion in ubicaciones" :key="ubicacion.id" :ubicacionId="ubicacion.id"
            :puesto="puesto" class="px-2 whitespace-nowrap" @save="$emit('save', $event)" />
        <td></td>
    </tr>
</template>

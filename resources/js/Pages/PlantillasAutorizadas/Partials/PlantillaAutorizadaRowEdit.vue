<script setup>
import ColumnPlantillaEdit from './ColumnPlantillaEdit.vue';

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
        <ColumnPlantillaEdit v-for="ubicacion in ubicaciones" :key="ubicacion.id" :puestoId="puesto.id"
            :ubicacion="ubicacion" class="px-2 whitespace-nowrap" @save="$emit('save', $event)" />
        <td></td>
    </tr>
</template>

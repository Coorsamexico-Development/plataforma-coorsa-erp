<script setup>
import { computed, reactive } from "vue";

defineEmits(["save"]);

const props = defineProps({
    ubicacion: {
        type: Object,
        required: true,
    },
    puestoId: {
        type: Number,
        required: true,
    },
});

const plantillaAutorizada = reactive({
    id: -1,
    puesto_id: props.puestoId,
    ubicacione_id: props.ubicacion.id,
    cantidad: 0,
    cantidad_activa: 0,
});

const color = computed(() => {
    let plantillaFind = props.ubicacion.plantillas_autorizadas.find(
        (plantilla) => {
            return plantilla.puesto_id === props.puestoId;
        }
    );
    if (plantillaFind !== undefined) {
        plantillaAutorizada.id = plantillaFind.id;
        plantillaAutorizada.cantidad = plantillaFind.cantidad;
        plantillaAutorizada.cantidad_activa = plantillaFind.cantidad_activa;
        if (plantillaFind.cantidad_activa > plantillaFind.cantidad) {
            return "bg-orange-400";
        } else {
            return "bg-white";
        }
    }
    return "bg-white";
});
</script>
<template>
    <td class="relative px-2 whitespace-nowrap">

        <div :class="color" class="mx-4 my-1 rounded">
            <div v-if="plantillaAutorizada.id !== -1">
                {{ plantillaAutorizada.cantidad_activa }}
                /
                <input type="number"
                    class="w-2/4 p-0 bg-transparent border-transparent focus:ring-0 focus:border-transparent focus:border-b-gray-600"
                    @blur="$emit('save', plantillaAutorizada)" min="0" max="10000" v-model="plantillaAutorizada.cantidad" />
            </div>
        </div>
    </td>
</template>

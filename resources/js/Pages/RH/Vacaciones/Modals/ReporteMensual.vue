<script setup>
import { ref, watchEffect } from "vue";
import DialogModalPopUp from "@/Components/DialogModalPopUp.vue";
import moment from "moment";
import Reporte from "@/Iconos/Reporte.vue";

defineProps({
    position: {
        type: Object,
        required: true,
    },
    show: {
        type: Boolean,
        default: false,
    },
});

const date = ref();

defineEmits(["close"]);
</script>
<template>
    <DialogModalPopUp
        :show="show"
        :position="position"
        @close="$emit('close')"
        maxWidth="xs"
    >
        <template #title>
            <span class="text-[18px] font-semibold">
                Reporte de vacaciones
            </span>
        </template>
        <template #content>
            <div class="grid gap-2">
                <VueDatePicker
                    month-picker
                    placeholder="Seleecione un mes para el reporte"
                    v-model="date"
                />
                <a
                    :href="route('reporteVacaciones', { ...date })"
                    class="bg-[#1d96f1] text-white px-3 py-1 rounded-xl flex items-center justify-center gap-4"
                >
                    Descargar Reporte
                </a>
            </div>
        </template>
    </DialogModalPopUp>
</template>

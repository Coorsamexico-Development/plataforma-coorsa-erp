<script setup>
import DialogModal from "@/Components/DialogModal.vue";
import Anime from "animejs/lib/anime.es.js";
import { onUpdated } from "vue";

const emit = defineEmits(["close"]);

defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    maxWidth: {
        type: String,
        default: "2xl",
    },
    closeable: {
        type: Boolean,
        default: true,
    },
    empleado: {},
    puesto: {},
});

const close = () => {
    emit("close");
};

/* onUpdated(() => {
    Anime({
        targets: "span .id",
        delay: 500,
        backgroundColor: "#F0F055",
    });
}); */
</script>

<template>
    <DialogModal
        :show="show"
        :max-width="maxWidth"
        :closeable="closeable"
        @close="close"
    >
        <template #title>
            <h1 class="text-[40px] pt-4"></h1>
        </template>
        <template #content>
            <span>
                El puesto
                <span class="font-bold">{{ puesto.name }}</span> no puede ser
                eliminado ya que esta asignado actulmente a los empleados:
                <br />
                <ul v-for="emp in empleado" :key="id">
                    <li>
                        <span class="font-bold">
                            {{ emp.name }}
                            {{ emp.apellido_paterno }}
                            {{ emp.apellido_materno }}
                        </span>
                        con numero de empleado
                        <span class="font-bold id">
                            {{ emp.numero_empleado }}
                        </span>
                    </li>
                </ul>
            </span>
        </template>
    </DialogModal>
</template>

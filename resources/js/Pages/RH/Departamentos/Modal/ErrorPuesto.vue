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
        default: "3xl",
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
                <span class="text-[20px]">
                    El puesto
                    <span class="font-bold">{{ puesto.name }}</span> no puede
                    ser eliminado ya que esta asignado actulmente a los
                    siguientes empleados:
                    <br />
                </span>
                <div
                    class="text-[15px] mt-2 max-h-[79vh] overflow-y-auto overflow-x-visible"
                >
                    <ul class="">
                        <li
                            v-for="emp in empleado"
                            :key="id"
                            class="py-1 pl-4 even:bg-gray-50 shadow-sm even:shadow-[#DADBDC] odd:shadow-[#202020]"
                        >
                            <span class="font-bold capitalize">
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
                </div>
            </span>
        </template>
    </DialogModal>
</template>

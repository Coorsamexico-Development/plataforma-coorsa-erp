<script setup>
import DialogModal from "@/Components/DialogModal.vue";
import Diagrama from "../Partials/Diagrama.vue";

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
    title: {
        type: String,
        default: "Gerencia",
    },
    nodos: {
        default: null,
    },
    relaciones: {
        default: null,
    },
    sons: {
        default: null,
    },
});

const close = () => {
    emit("close");
};

const modal = (nds) => {
    let a = nds.a;
    emit("modal", { a });
};
</script>

<template>
    <DialogModal
        :show="show"
        :max-width="maxWidth"
        :closeable="closeable"
        @close="close"
    >
        <template #title>
            <h1 class="text-[40px] pt-4">{{ title.nombre }}</h1>
        </template>
        <template #content>
            <div class="h-[83vh]">
                <Diagrama
                    :nodos="nodos"
                    :rels="relaciones"
                    :area="title.id"
                    :sons="sons"
                    @area="modal"
                />
            </div>
        </template>
    </DialogModal>
</template>

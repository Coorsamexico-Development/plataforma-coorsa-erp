<script setup>
import DialogModal from "@/Components/DialogModal.vue";
import Error from "@/Iconos/Error.vue";

const emit = defineEmits(["close"]);

const props = defineProps({
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
    area: {},
});

const close = () => {
    emit("close");
};
</script>

<template>
    <DialogModal
        :show="show"
        :max-width="'lg'"
        :closeable="closeable"
        @close="close"
    >
        <template #title>
            <h1 class="text-[30px] pt-4 font-bold leading-4">
                {{ area.puesto }}
            </h1>
            <span class="font-bold text-[14px]">{{ area.ceco }}</span>
        </template>
        <template #content>
            <div class="grid gap-4 place-content-center">
                <div class="flex justify-center w-full">
                    <Error
                        class="h-[13rem] w-[13rem] transition-all duration-500"
                    />
                </div>
                <div>
                    <h2 class="text-[20px] text-center leading-5">
                        Fallo al remover el puesto ya que esta asignado al area
                        <span class="font-bold">{{ area.nombre }}</span>
                    </h2>
                </div>
            </div>
        </template>
    </DialogModal>
</template>

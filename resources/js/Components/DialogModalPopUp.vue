<script setup>
import Modal from "@/Components/ModalPopUp.vue";

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
    position: {
        type: Object,
        required: true,
    },
});

const close = () => {
    emit("close");
};
</script>

<template>
    <Modal
        :show="show"
        :max-width="maxWidth"
        :closeable="closeable"
        @close="close"
        :position="position"
    >
        <div class="p-[27px] max-w-[100%] relative gap-4 grid">
            <div class="text-[25px] font-semibold" v-if="$slots.title">
                <slot name="title" />
            </div>

            <div>
                <slot name="content" />
            </div>
            <div class="flex justify-center w-full" v-if="$slots.footer">
                <slot name="footer" />
            </div>
        </div>
    </Modal>
</template>

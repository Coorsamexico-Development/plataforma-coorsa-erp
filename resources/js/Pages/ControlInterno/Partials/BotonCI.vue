<script setup>
defineProps({
    disabled: {
        type: Boolean,
        default: false,
    },
    value: {
        type: String,
        default: "Tetxto",
    },
});

defineEmits(["click"]);
</script>
<template>
    <button
        class="px-2 py-1 font-medium max-h-fit w-full text-left text-[#0A0F2C] rounded-lg disabled:cursor-auto disabled:text-[#FFFFFF] disabled:bg-[#1D96F1] disabled:font-semibold enabled:hover:bg-[#aad8fa] enabled:hover:text-white"
        @click="$emit('click')"
        :disabled="disabled"
    >
        {{ value }}
    </button>
</template>

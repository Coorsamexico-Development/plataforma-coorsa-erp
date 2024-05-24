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
        class="px-[4rem] py-1 font-medium max-h-fit text-[#FFFFFF] disabled:cursor-auto disabled:text-[#9f9f9f] text-[25px] bg-blue-500 rounded-full enabled:active:scale-90 transition-all duration-200"
        @click="$emit('click')"
        :disabled="disabled"
    >
        {{ value }}
    </button>
</template>

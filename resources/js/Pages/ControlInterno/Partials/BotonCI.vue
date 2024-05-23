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
        class="px-2 py-1 font-medium max-h-fit w-fit text-[#0A0F2C] disabled:cursor-auto disabled:text-[#474b61]"
        @click="$emit('click')"
        :disable="disabled"
    >
        {{ value }}
    </button>
</template>

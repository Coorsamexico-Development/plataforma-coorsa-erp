<script setup>
defineProps({
    value: {
        type: String,
        default: "Texto",
    },
    class: {
        type: String,
        default: "text-[28px]",
    },
});
</script>
<template>
    <span
        class="px-4 py-1 font-semibold text-white uppercase bg-blue-500 rounded-xl h-fit"
        :class="class"
    >
        {{ value }}
    </span>
</template>

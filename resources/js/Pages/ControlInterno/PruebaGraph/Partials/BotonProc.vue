<script setup>
import { computed, onMounted } from "vue";
const props = defineProps({
    active: { type: Boolean },
});
const classes = computed(() => {
    return props.active
        ? "px-6 py-1 transition-all duration-200 bg-[#0097f2] rounded-xl hover:cursor-pointer scale-[110%] text-white  text-[15px]"
        : "px-6 py-1 transition-all duration-200 bg-[#8fb3c9] rounded-xl hover:cursor-pointer hover:bg-[#0097f2] hover:scale-105 text-white text-[15px]";
});
</script>
<template>
    <button
        :class="classes"
        :disabled="active"
        class="disabled:hover:cursor-default"
    >
        <slot />
    </button>
</template>

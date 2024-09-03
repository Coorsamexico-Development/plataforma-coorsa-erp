<script setup>
import { computed } from "vue";

const props = defineProps({
    name: {
        type: Text,
        rquired: true,
    },
    bgColor: {
        type: Text,
        required: true,
    },
});

const bgColor = computed(() => props.bgColor);

defineEmits(["click"]);
</script>
<template>
    <div
        class="flex flex-col text-center max-sm:font-medium text-[11px] gap-[11px] w-fit cursor-pointer items-center transition-all duration-1000"
        @click="$emit('click')"
    >
        <div
            class="w-[5rem] h-[5rem] grid place-content-center rounded-full"
            :class="bgColor"
        >
            <slot />
        </div>
        <span>{{ name }}</span>
    </div>
</template>

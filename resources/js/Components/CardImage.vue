<script setup>
import { computed } from "vue"

const props = defineProps({
    src: {
        type: String,
        default: '.png'
    },
})

const urlLogo = computed(() => {
    if (props.src.endsWith('.pdf')) {
        return
    }
    return '/assets/img/icono pdf.png'
})

</script>
<template>
    <div class="h-48 bg-white shadow-xl rounded-3xl w-36 md:h-52 md:w-44">
        <div class="overflow-hidden h-3/4">
            <img :src="urlLogo" alt="logo-file" class="w-full" />
        </div>
        <div class="relative flex justify-center font-bold text-center text-gray-400 h-1/4">
            <slot />
        </div>

    </div>
</template>

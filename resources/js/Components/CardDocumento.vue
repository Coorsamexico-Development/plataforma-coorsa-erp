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
    <div
        class="w-full px-1 pt-2 overflow-hidden bg-gray-300 shadow-xl cursor-pointer lg:px-2 lg:pt-6 hover:bg-gray-400 sm:rounded-2xl">
        <div class="h-3/4">
            <img :src="urlLogo" alt="logo-file" class="object-cover w-full h-full px-1 lg:px-4" />
        </div>
        <div class="flex justify-center h1/4 ">
            <slot />
        </div>
    </div>
</template>

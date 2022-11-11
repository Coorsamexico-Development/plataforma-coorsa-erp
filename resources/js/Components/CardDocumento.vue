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
    return '/assets/img/icono doc 7.png'
})

</script>
<template>
    <div class="w-full px-2 pt-6 overflow-hidden bg-gray-300 shadow-xl cursor-pointer hover:bg-gray-400 sm:rounded-2xl">
        <div class=" h-3/4">
            <img :src="urlLogo" alt="logo-file" class="w-full h-full" />
        </div>
        <div class="flex justify-center h1/4 ">

            <slot />
        </div>
    </div>
</template>

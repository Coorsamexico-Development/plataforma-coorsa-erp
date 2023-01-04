<script setup>
import { computed, ref } from "vue"

const props = defineProps({
    src: {
        type: String,
        default: '.png'
    },
})

const urlLogo = computed(() => {
    if (props.src.endsWith('.pdf')) 
    {
        return '../resources/img/portada_pdf.jpg'
    }
});





</script>
<template>
    <div 
        v-if="props.src.endsWith('.pdf')"
        style="width:10.5rem; height: 15rem;"
        class="w-full px-1 pt-2 overflow-hidden shadow-xl cursor-pointer fondo_pdf lg:px-2 lg:pt-6 hover:bg-gray-400 sm:rounded-2xl">
        <div class="flex justify-center h1/4 ">
            <slot />
        </div>
    </div>
    <div 
        v-if="props.src.endsWith('.xlsx')"
        style="width:10.5rem; height: 15rem;"
        class="w-full px-1 pt-2 overflow-hidden shadow-xl cursor-pointer fondo_excel lg:px-2 lg:pt-6 hover:bg-gray-400 sm:rounded-2xl">
        <div class="flex justify-center h1/4 ">
            <slot />
        </div>
    </div>
</template>

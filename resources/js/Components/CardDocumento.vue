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
    
})


</script>
<template>
    <div 
        v-if="props.src.endsWith('.pdf')"
        style="background-image: url('../../img/portada_pdf.jpg');"
        class="w-full px-1 pt-2 overflow-hidden shadow-xl cursor-pointer lg:px-2 lg:pt-6 hover:bg-gray-400 sm:rounded-2xl">
       
        <div class="flex justify-center h1/4 ">
            <slot />
        </div>
    </div>
</template>

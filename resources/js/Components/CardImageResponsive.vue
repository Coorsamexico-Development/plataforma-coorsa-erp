<script setup>
import { computed } from "vue"

const props = defineProps({
    img: {
        type: String,
        default: '/assets/img/logo-red.jpg'
    },
    file: {
        type: String,
        default: ''
    }
})

const isDownload = computed(() => {
    if (props.file.endsWith('.pdf')) {
        return false
    }
    return true
})

</script>
<template>
    <div class="h-48 bg-white sm:rounded-none rounded-lg shadow-xl hover:shadow-md w-36 md:h-52 md:w-44" 
    style="display: flex;
    align-items: center;
    justify-content: space-evenly;
    flex-direction: column;
    height: 14rem;"
    >
        <div class="relative flex flex-col justify-center font-bold text-gray-400 mt-11 h-1/4">
            <slot />
        </div>
    </div>
</template>

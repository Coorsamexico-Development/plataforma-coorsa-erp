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
    <div class="h-48 bg-white shadow-xl hover:shadow-md rounded-3xl w-36 md:h-52 md:w-44">
        <div class="relative overflow-hidden group h-3/4">
            <img :src="img" alt="logo-file" class="w-auto h-full m-auto rounded-t-3xl" />
            <div
                class="absolute top-0 bottom-0 left-0 right-0 w-full h-full transition-opacity duration-300 ease-in-out bg-gray-900 opacity-0 group-hover:opacity-80 rounded-t-3xl">
                <div class="flex items-center justify-center w-full h-full">
                    <div
                        class="w-6 h-6 py-1 transition duration-300 bg-white rounded-full group-hover:scale-125 hover:bg-green-500 hover:text-white">
                        <a v-if="isDownload" target="_blank" :href="file" download>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mx-auto" fill="currentColor"
                                viewBox="0 0 16 16">
                                <path fill-rule="evenodd"
                                    d="M1 3.5a.5.5 0 0 1 .5-.5h13a.5.5 0 0 1 0 1h-13a.5.5 0 0 1-.5-.5zM8 6a.5.5 0 0 1 .5.5v5.793l2.146-2.147a.5.5 0 0 1 .708.708l-3 3a.5.5 0 0 1-.708 0l-3-3a.5.5 0 0 1 .708-.708L7.5 12.293V6.5A.5.5 0 0 1 8 6z" />
                            </svg>
                        </a>
                        <a v-else :href="file" data-fancybox data-type="pdf">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mx-auto" fill="currentColor"
                                viewBox="0 0 16 16">
                                <path fill-rule="evenodd"
                                    d="M8.636 3.5a.5.5 0 0 0-.5-.5H1.5A1.5 1.5 0 0 0 0 4.5v10A1.5 1.5 0 0 0 1.5 16h10a1.5 1.5 0 0 0 1.5-1.5V7.864a.5.5 0 0 0-1 0V14.5a.5.5 0 0 1-.5.5h-10a.5.5 0 0 1-.5-.5v-10a.5.5 0 0 1 .5-.5h6.636a.5.5 0 0 0 .5-.5z" />
                                <path fill-rule="evenodd"
                                    d="M16 .5a.5.5 0 0 0-.5-.5h-5a.5.5 0 0 0 0 1h3.793L6.146 9.146a.5.5 0 1 0 .708.708L15 1.707V5.5a.5.5 0 0 0 1 0v-5z" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="relative flex justify-center font-bold text-center text-gray-400 h-1/4">
            <slot />
        </div>

    </div>
</template>

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
            <div style="" class="absolute  z-40 float-left w-6 h-6 py-1 transition duration-300 bg-black rounded-full group-hover:scale-125 hover:bg-green-500 hover:text-white">
            <a class="" v-if="isDownload" target="_blank" :href="file" download>
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
            <slot />
        </div>

    </div>
</template>

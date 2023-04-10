<script setup>
import { computed, onMounted, onUnmounted, ref } from 'vue';

const props = defineProps({
    align: {
        type: String,
        default: 'right',
    },
    width: {
        type: String,
        default: '48',
    },
    contentClasses: {
        type: Array,
        default: () => ['py-1', 'bg-gray-800'],
    },
    type: {
        type: String,
        default: 'submit',
    },
});

let open = ref(false);

const closeOnEscape = (e) => {
    if (open.value && e.key === 'Escape') {
        open.value = false;
    }
};

onMounted(() => document.addEventListener('keydown', closeOnEscape));
onUnmounted(() => document.removeEventListener('keydown', closeOnEscape));

const widthClass = computed(() => {
    return {
        '48': 'w-48',
    }[props.width.toString()];
});

const alignmentClasses = computed(() => {
    if (props.align === 'left') {
        return 'top-0 left-0';
    }

    if (props.align === 'right') {
        return 'top-0 left-full ';
    }

    return 'origin-top';
});
</script>

<template>
    <div class="relative">
        <div @click="open = !open">
            <button :type="type"
                class="relative inline-flex items-center px-1 py-1 text-xs font-semibold tracking-widest text-white uppercase transition bg-gray-800 border border-transparent rounded-md hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 disabled:opacity-25"
                :class="{ 'rounded-tr-none rounded-br-none': open }">
                <div class="flex">
                    <slot name="button" />
                    <div class="pl-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-caret-right" viewBox="0 0 16 16">
                            <path
                                d="M6 12.796V3.204L11.481 8 6 12.796zm.659.753 5.48-4.796a1 1 0 0 0 0-1.506L6.66 2.451C6.011 1.885 5 2.345 5 3.204v9.592a1 1 0 0 0 1.659.753z" />
                        </svg>
                    </div>

                </div>

            </button>

        </div>

        <!-- Full Screen Dropdown Overlay -->
        <div v-show="open" class="fixed inset-0 z-50" @click="open = false" />

        <transition enter-active-class="transition duration-200 ease-out"
            enter-from-class="transform -translate-x-2 opacity-0" enter-to-class="transform translate-x-0 opacity-100"
            leave-active-class="transition duration-75 ease-in" leave-from-class="transform translate-x-0 opacity-100"
            leave-to-class="transform -translate-x-2 opacity-0">
            <div v-show="open" class="absolute z-50 py-0" :class="[widthClass, alignmentClasses]" style="display: none;"
                @click="open = false">
                <div class="ml-16 rounded-md rounded-tl-none cursor-pointer ring-1 ring-gray-500 ring-opacity-5 sm:ml-0"
                    :class="contentClasses">
                    <slot name="content" />
                </div>
            </div>
        </transition>
    </div>
</template>

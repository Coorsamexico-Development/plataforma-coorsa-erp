<script setup>
import { def } from '@vue/shared';
import { ref, computed } from 'vue';
import ModalTableItems from './ModalTableItems.vue';

var props = defineProps({
    type: {
        type: String,
        default: 'button',
    },
});

const emit = defineEmits(["openModalTableCampos"]);

const openTableModal = () => 
{
    emit('openModalTableCampos');
}

</script>

<template>
    <button @click="openTableModal" :type="type" class="inline-flex items-center px-3 py-2 text-xs font-semibold tracking-widest text-gray-700 uppercase transition border border-gray-300 shadow-sm bg-teal-50 rounded-2xl hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200 active:text-gray-800 active:bg-gray-50 disabled:opacity-25">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5 4a3 3 0 00-3 3v6a3 3 0 003 3h10a3 3 0 003-3V7a3 3 0 00-3-3H5zm-1 9v-1h5v2H5a1 1 0 01-1-1zm7 1h4a1 1 0 001-1v-1h-5v2zm0-4h5V8h-5v2zM9 8H4v2h5V8z" clip-rule="evenodd" />
        </svg>
    </button>
    <ModalTableItems :show="tableModal" @close="closeTableModal" />
</template>

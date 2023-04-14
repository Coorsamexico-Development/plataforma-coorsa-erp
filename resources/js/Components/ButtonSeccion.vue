<script setup>
defineProps({
    type: {
        type: String,
        default: 'button',
    },
    active: {
        type: Boolean,
        default: false,
    }
});
</script>

<template>
    <button :type="type" style="background: transparent "
        class="inline-flex items-center w-40 px-4 py-2 text-xs font-semibold tracking-widest text-gray-700 uppercase transition border-4 rounded-md shadow-sm buttonseccion focus:outline-none hover:border-blue-300 focus:ring focus:ring-gray-200 active:text-gray-800 active:bg-gray-50 disabled:opacity-25"
        :class="active ? 'border-blue-400' : 'border-gray-300'">
        <slot />
    </button>
</template>

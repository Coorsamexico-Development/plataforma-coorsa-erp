<script setup>
defineProps({
    type: {
        type: String,
        default: 'button',
    },
});
</script>

<template>
    <button :type="type" style="background: transparent "
        class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-gray-700 uppercase transition border border-gray-300 rounded-md shadow-sm buttonseccion hover:border-red hover:text-gray-500 focus:outline-none focus:border-gray-300 focus:ring focus:ring-gray-200 active:text-gray-800 active:bg-gray-50 disabled:opacity-25">
        <slot />
    </button>
</template>
<style scoped>
.active {
    border-color: #CB4060
}
</style>

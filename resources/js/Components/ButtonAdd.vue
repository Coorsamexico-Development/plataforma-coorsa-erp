<script setup>
defineProps({
    type: {
        type: String,
        default: 'button',
    },
});
</script>

<template>
    <button :type="type" 
    style="border: 0.2rem solid #EC2944; padding:0.5em 1em 0.5em 1em;"
    class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-[#1A1A22] uppercase transition border border-gray-300 rounded-md shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200 active:text-gray-800 active:bg-gray-50 disabled:opacity-25">
        <slot />
    </button>
</template>

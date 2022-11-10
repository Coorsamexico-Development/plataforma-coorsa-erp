<script setup>
defineProps({
    urlLogo: String
})
</script>
<template>
    <div class="m-2">
        <div class="w-10 h-10 p-1 bg-gray-300 rounded-full">
            <img :src="urlLogo" class="w-full h-full" />
            <slot />
        </div>
    </div>
</template>

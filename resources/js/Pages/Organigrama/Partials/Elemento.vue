<script setup>
defineProps(["nodo"]);
</script>
<template>
    <div>
        <div
            class="w-full px-[5px] text-center text-[15px] font-extralight hover:cursor-pointer rounded-2xl"
        >
            <span class="font-semibold">{{ nodo.Puesto }}</span>
            <h1 class="w-full">
                {{ nodo.Ceco }}
            </h1>
        </div>
    </div>
</template>

<style scoped>
.drag > div {
    transform: rotate(3deg);
}
.ghost {
    background: #e5e7eb;
    border-radius: 6px;
}
.ghost > div {
    visibility: hidden;
}
</style>

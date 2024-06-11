<script setup>
import { onMounted, ref } from "vue";

const data = ref("data");

onMounted(() =>
    Echo.channel("ChatBot").listen("getData", (data) => console.log("hola"))
);
</script>
<template>
    <div
        class="bg-[#f2f2f2] text-[18px] font-medium grid place-content-center h-screen w-screen"
    >
        <div
            class="p-[15px] bg-white rounded-xl drop-shadow-[1px_2px_5px_#BDBDBD]"
        >
            {{ data }}
        </div>
    </div>
</template>

<script setup>
import { useForm } from "@inertiajs/inertia-vue3";
import { onMounted, ref } from "vue";

const data = ref("data");
const form = useForm({
    mensaje: "HolaMundo",
});

function sendData() {
    axios
        .post(route("chatBotData", { mensaje: "HolaMundo" }))
        .then(({ data }) => console.log(data))
        .catch((err) => console.log(err.response ?? err));
    /* form.transform((data) => ({
        ...data,
    })).post(route("chatBotData")); */
}

onMounted(() =>
    Echo.channel("ChatBot").listen("getData", (data) => console.log("hola"))
);
</script>
<template>
    <div
        class="bg-[#f2f2f2] text-[18px] font-medium grid place-content-center h-screen w-screen gap-2"
    >
        <div
            class="p-[15px] bg-white rounded-xl drop-shadow-[1px_2px_5px_#BDBDBD]"
        >
            {{ data }}
        </div>

        <button
            @click="sendData"
            class="px-2 py-1 text-white bg-blue-500 rounded-xl"
        >
            Enviar
        </button>
    </div>
</template>

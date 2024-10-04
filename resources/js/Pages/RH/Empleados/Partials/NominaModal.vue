<script setup>
import { useForm, Link } from "@inertiajs/inertia-vue3";
import DialogModal from "@/Components/DialogModal.vue";
import InputError from "@/Components/InputError.vue";
import axios from "axios";
import BadGoodRequest from "./BadGoodRequest.vue";
import { reactive, ref } from "vue";
const emit = defineEmits(["close"]);

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    maxWidth: {
        type: String,
        default: "2xl",
    },
    closeable: {
        type: Boolean,
        default: true,
    },
});

const close = () => {
    emit("close");
    form.reset();
};

const form = useForm({
    doc: "hola",
});
const formData = new FormData();
const badGodShow = ref(false);
const badGodData = reactive({
    title: "Titulo",
    type: null,
    info: null,
});

const subir = () => {
    axios
        .post(route("nomina.upload", formData), formData, {
            headers: "multipart/form-data",
            onUploadProgress: () => (form.processing = true),
        })
        .then(({ data }) => {
            badGodData.title = "Subida Correcta";
            badGodData.type = "god";
            badGodData.info = data;
            console.log(data);
        })
        .catch((err) => {
            badGodData.title = "Error en la subida";
            badGodData.type = "bad";
            badGodData.info = err.response ?? err;
            console.log(err.response ?? err);
        })
        .finally(() => {
            form.processing = false;
            badGodShow.value = true;
            close();
        });
};
</script>
<template>
    <DialogModal :nominas="nominas" :show="show" @close="close">
        <template #title>
            <h1 class="text-xl font-semibold">Recibos de Nomina</h1>
        </template>
        <template #content>
            <div class="p-4">
                <div class="flex flex-col justify-center gap-2">
                    <label for="documento" class="text-sm"
                        >Subir documento:
                        <span class="text-red-500">*</span></label
                    >
                    <input
                        type="file"
                        @input="
                            formData.append('doc', $event.target.files[0]);
                            form.doc = $event.target.files[0];
                        "
                        name="documento"
                        id="documento"
                        class="px-2 py-1 text-sm border border-black rounded-xl"
                    />
                    <InputError
                        :message="form.errors.doc"
                        class="mt-2 capitalize"
                    />
                </div>
            </div>
        </template>
        <template #footer>
            <form class="flex" @submit.prevent="subir">
                <input
                    type="submit"
                    value="Subir"
                    class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-white uppercase transition bg-blue-600 border border-transparent rounded-md hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring focus:ring-blue-300 disabled:opacity-25 hover:cursor-pointer"
                    :disabled="form.progress || form.doc === ''"
                />
            </form>
        </template>
    </DialogModal>

    <BadGoodRequest
        :show="badGodShow"
        @close="badGodShow = false"
        :data="badGodData"
    />
</template>

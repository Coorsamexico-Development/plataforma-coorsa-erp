<script setup>
import { onBeforeUpdate } from "vue";
import DialogModal from "@/Components/DialogModal.vue";
import { useForm } from "@inertiajs/inertia-vue3";

const emit = defineEmits(["close"]);
const form = useForm({
    id: "",
    nombre: "",
});

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
    title: {
        type: String,
        default: "Gerencia",
    },
    area: {
        default: null,
    },
});

const submit = () => {
    form.transform((data) => ({
        ...data,
    })).post(route("area.edit"), {
        onFinish: () => {
            form.reset();
            close();
        },
        onStart: () => {
            form.reset();
        },
    });
};

const close = () => {
    form.reset();
    emit("close");
};
onBeforeUpdate(() => {
    form.id = props.area.nid;
    form.nombre = props.area.id;
});
</script>

<template>
    <DialogModal
        :show="show"
        :max-width="maxWidth"
        :closeable="closeable"
        @close="close"
    >
        <template #title>
            <h1 class="text-[30px] pt-4">
                Cambiar nombre del area: {{ area.id }}
            </h1>
        </template>
        <template #content>
            <form @submit.prevent="submit">
                <label for="nombre">Nuevo Nombre</label>
                <input
                    id="nombre"
                    type="text"
                    class="w-full transition-all duration-200 border-black rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 read-only:bg-gray-300"
                    v-model="form.nombre"
                    required
                />
            </form>
        </template>
        <template #footer>
            <form @submit.prevent="submit">
                <input
                    type="submit"
                    class="px-4 py-1 transition-all duration-200 bg-green-300 rounded-xl hover:cursor-pointer hover:scale-110 hover:bg-green-500 hover:text-white disabled:bg-gray-300 disabled:hover:cursor-default disabled:hover:scale-100 disabled:hover:text-black"
                    value="Actualizar"
                    :disabled="form.progress || form.nombre === area.id"
                />
            </form>
        </template>
    </DialogModal>
</template>

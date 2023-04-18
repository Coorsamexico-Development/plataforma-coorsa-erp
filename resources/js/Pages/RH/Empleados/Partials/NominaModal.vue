<script setup>
import { useForm, Link } from "@inertiajs/inertia-vue3";
import DialogModal from "@/Components/DialogModal.vue";
import InputError from "@/Components/InputError.vue";
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
    doc: "",
});

const subir = () => {
    form.transform((data) => ({
        ...data,
    })).post(route("nomina.upload"), {
        onSuccess: () => close(),
        onCancel: () => close(),
    });
};
</script>
<template>
    <DialogModal :nominas="nominas" :show="show" @close="close">
        <template #title>
            <h1 class="font-semibold text-xl">Recibos de Nomina</h1>
        </template>
        <template #content>
            <div class="p-4">
                <div class="flex flex-col gap-2 justify-center">
                    <label for="documento" class="text-sm"
                        >Subir documento:
                        <span class="text-red-500">*</span></label
                    >
                    <input
                        type="file"
                        @input="form.doc = $event.target.files[0]"
                        name="documento"
                        id="documento"
                        class="border rounded-xl px-2 py-1 border-black text-sm"
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
</template>

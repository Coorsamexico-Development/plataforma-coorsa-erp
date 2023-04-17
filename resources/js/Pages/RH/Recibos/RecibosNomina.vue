<script setup>
import { useForm, Link } from "@inertiajs/inertia-vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import InputError from "@/Components/InputError.vue";
import Card from "@/Components/Card.vue";
defineProps({ nominas: Object });

const form = useForm({
    doc: "",
});

const subir = () => {
    form.transform((data) => ({
        preserveScroll: true,
        preserveState: true,
        ...data,
    })).post(route("nomina.upload"), {
        onSuccess: () => form.reset(),
        onCancel: () => form.reset(),
    });
};
</script>
<template>
    <AppLayout title="Recibos Nomina" :nominas="nominas">
        <Card>
            <div class="grid place-content-center p-4">
                <form class="flex gap-2 flex-col" @submit.prevenDefault="subir">
                    <div class="flex gap-2 items-center">
                        <label for="documento">Subir documento</label>
                        <input
                            type="file"
                            @input="form.doc = $event.target.files[0]"
                            name="documento"
                            id="documento"
                            class="border-2 rounded-xl px-2 py-1 border-gray-400"
                        />
                        <InputError :message="form.errors.doc" class="mt-2" />
                    </div>
                    <div>
                        <button
                            type="submit"
                            class="hover:cursor-pointer bg-indigo-400 p-2 rounded-full text-white"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="icon icon-tabler icon-tabler-file-upload"
                                width="30"
                                height="30"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="#ffffff"
                                fill="none"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <path
                                    stroke="none"
                                    d="M0 0h24v24H0z"
                                    fill="none"
                                />
                                <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                <path
                                    d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"
                                />
                                <line x1="12" y1="11" x2="12" y2="17" />
                                <polyline points="9 14 12 11 15 14" />
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        </Card>
    </AppLayout>
</template>

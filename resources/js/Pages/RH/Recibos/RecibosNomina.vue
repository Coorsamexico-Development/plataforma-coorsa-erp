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
                <form class="flex gap-2 flex-col" @submit.prevent="subir">
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
                        <input
                            type="submit"
                            value="Subir"
                            class="hover:cursor-pointer bg-indigo-400 p-2 rounded-full text-white"
                        />
                    </div>
                </form>
            </div>
        </Card>
    </AppLayout>
</template>

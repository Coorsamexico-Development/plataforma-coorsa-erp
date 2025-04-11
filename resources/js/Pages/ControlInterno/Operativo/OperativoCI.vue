<script setup>
import { useForm } from "@inertiajs/inertia-vue3";
import CardCi from "../Partials/CardCi.vue";
import { watch } from "vue";

const props = defineProps({
    show: {
        type: Object,
        default: false,
    },
});

const form = useForm({
    file: null,
});

watch(
    () => form.file,
    (newFile) => {
        form.transform((data) => ({
            ...data,
            onSuccess: () => form.reset(),
        })).post(
            route("saveDocument", form, {
                forceFormData: true,
            })
        );
    }
);
</script>
<template>
    <CardCi>
        <div class="flex items-center w-full h-fit">
            <label for="documento"> Subir documento</label>
            <input
                id="documento"
                class="hidden"
                type="file"
                @input="form.file = $event.target.files[0]"
            />
        </div>
    </CardCi>
</template>

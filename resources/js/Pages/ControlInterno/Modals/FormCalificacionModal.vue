<script setup>
import { ref } from "vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import SecondaryButton from '@/Components/SecondaryButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SpinProgress from "@/Components/SpinProgress.vue";
import DialogModal from "@/Components/DialogModal.vue";
import { useForm } from "@inertiajs/inertia-vue3";

const emit = defineEmits(["close", "messageError"]);
const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    departamento: {
        type: Object,
        required: true,
    },
});
const form = useForm({
    'calificacion': "0",
    'mes': "",
    "documento": null,
});
const file = ref(null)

const setFileName = () => {
    fileName.value = file.value.files[0].name;
}

const selectFile = () => {
    file.value.click();
}

const close = () => {
    emit('close');
}



const create = async () => {
    if (document.getElementById("formCalificacion").reportValidity()) {
        form.post(route('departamentos-aditorias.calificacion.store', props.departamento.id), {
            preserveScroll: true,
            preserveState: true,
            only: ['calificaciones'],
            onSuccess: () => {
                close()
            }
        });

    }
}

</script>

<template>
    <DialogModal :show="show" maxWidth="2xl" @close="close()">
        <template #title>
            <h2 class="font-semibold text-md">
                Calificacion <span class="text-white bg-blue-400">{{ departamento.nombre }}</span>
            </h2>
        </template>
        <template #content>
            <form id="formCalificacion" @submit.prevent="create()">
                <div class="grid grid-cols-2 gap-2 md:grid-cols-2">
                    <div class="mt-2">
                        <InputLabel for="name">
                            Mes:<span class="text-red-400">*</span>
                        </InputLabel>

                        <TextInput id="name" type="date" v-model="form.mes" class="block w-full mt-1" required
                            maxlength="40" />
                        <InputError :message="form.errors.mes" class="mt-2" />
                    </div>
                    <div class="mt-2">
                        <InputLabel for="calificacion">
                            Calificaci&oacute;n:<span class="text-red-400">*</span>
                        </InputLabel>
                        <TextInput id="calificacion" type="number" placeholder="Calificacion 0-100"
                            v-model="form.calificacion" class="block w-full mt-1" required min="0" max="100" step="1"
                            pattter="[0-9]+" />
                        <InputError :message="form.errors.calificacion" class="mt-2" />
                    </div>
                    <div class="col-span-2 mt-2">
                        <InputLabel for="documento">
                            Documento:<span class="text-red-400">*</span>
                        </InputLabel>
                        <input type="file" class="hidden" ref="file" name="file"
                            @input="form.documento = $event.target.files[0]" @change="setFileName()"
                            accept=".pdf,.jpg,.jpeg,.png">
                        <SecondaryButton type="button" @click="selectFile()">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="currentColor"
                                viewBox="0 0 16 16">
                                <path
                                    d="M8 6.5a.5.5 0 0 1 .5.5v1.5H10a.5.5 0 0 1 0 1H8.5V11a.5.5 0 0 1-1 0V9.5H6a.5.5 0 0 1 0-1h1.5V7a.5.5 0 0 1 .5-.5z" />
                                <path
                                    d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5L14 4.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5h-2z" />
                            </svg>
                            SELECIONA UN ARCHIVO
                        </SecondaryButton>
                        <InputError :message="form.errors.documento" class="mt-2" />
                    </div>
                </div>
            </form>
        </template>

        <template #footer>
            <div class="mx-2 my-2">
                <SecondaryButton @click="close()">
                    Cancelar
                </SecondaryButton>
                <PrimaryButton class="ml-4" @click="create()" :disabled="form.processing">
                    <SpinProgress v-if="form.processing" :inprogress="form.processing" /> Guardar
                </PrimaryButton>
            </div>
        </template>
    </DialogModal>
</template>

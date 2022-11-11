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
            only: ['archivos'],
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
                        <TextInput id="calificacion" type="number" placeholder="ejemplo@coorsamexico.com"
                            v-model="form.calificacion" class="block w-full mt-1" required min="0" />
                        <InputError :message="form.errors.calificacion" class="mt-2" />
                    </div>
                    <div class="mt-2">
                        <InputLabel for="documento">
                            Documento:<span class="text-red-400">*</span>
                        </InputLabel>
                        <input type="file" class="hidden" ref="file" name="file"
                            @input="form.documento = $event.target.files[0]" @change="setFileName()"
                            accept=".pdf,.jpg,.jpeg,.png">
                        <SecondaryButton class="relative mr-1" type="button" @click="selectFile()">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 32.294 31.373">
                                <g id="Grupo_121" data-name="Grupo 121" transform="translate(-151.33 -440.732)">
                                    <path id="Trazado_150" data-name="Trazado 150"
                                        d="M2821.419,472.105h-18.335a4.848,4.848,0,0,1-4.842-4.843V448.927a4.848,4.848,0,0,1,4.842-4.843h18.335a4.848,4.848,0,0,1,4.843,4.843v18.335A4.849,4.849,0,0,1,2821.419,472.105Zm-18.335-26.668a3.494,3.494,0,0,0-3.49,3.49v18.335a3.494,3.494,0,0,0,3.49,3.49h18.335a3.494,3.494,0,0,0,3.49-3.49V448.927a3.493,3.493,0,0,0-3.49-3.49Z"
                                        transform="translate(-2642.638)" fill="#fff" />
                                    <rect id="Rectángulo_183" data-name="Rectángulo 183" width="26.668" height="26.668"
                                        rx="6.258" transform="translate(151.33 440.732)" fill="#fff" />
                                </g>
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

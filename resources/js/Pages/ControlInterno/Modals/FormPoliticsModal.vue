<script setup>
import { computed, onMounted, ref } from "vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import SecondaryButton from '@/Components/SecondaryButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SpinProgress from "@/Components/SpinProgress.vue";
import DialogModal from "@/Components/DialogModal.vue";
import { useForm } from "@inertiajs/inertia-vue3";
import SelectComponent from "../../../Components/SelectComponent.vue";
import ListDataInput from "../../../Components/ListDataInput.vue";
import DangerButton from '@/Components/DangerButton.vue';
import { Inertia } from "@inertiajs/inertia";

const emit = defineEmits(["close", "messageError"]);
const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    tipoPoliticas: {
        type: Object,
        required: true
    },
    typeForm: {
        type: String,
        default: 'create'
    },
    politic: {
        type: Object,
    },
});
const form = useForm({
    'namepolitica': '',
    'descripcion': '',
    'type_politic': '',
    'autor': '',
    'imagePolitic': null,
    'pdf': null,
});
const fileImage = ref(null)
const fileName = ref("");
const fileNameImg = ref("");
const file = ref(null)
const processingDelete = ref(false)
const listusers = ref([]);


onMounted(() => {
    getListUsers();
})


const getListUsers = async () => {
    try {

        const resp = await axios.get(route('users.list'));
        listusers.value = resp.data;
    } catch (e) {
        console.log(e);
        if (error.response && error.response.data.hasOwnProperty('errors')) {

            alert(error.response.data.message)
        } else {
            alert("Error GET USERS")
        };
    }
}


const setFileNameImg = () => {
    fileNameImg.value = fileImage.value.files[0].name;

    form.imagePolitic = fileImage.value.files[0]
}
const setFileName = () => {
    fileName.value = file.value.files[0].name;
}

const selectFile = () => {
    file.value.click();
}
const selectFileImage = () => {
    fileImage.value.click();
}

const close = () => {
    fileName.value = "";
    fileNameImg.value = "";
    if (fileImage.value?.value) {
        fileImage.value.value = null;
    }
    if (file.value?.value) {
        file.value.value = null;
    }
    form.reset();
    emit('close');
}



const createOrUpdate = async () => {
    if (document.getElementById("formPolitic").reportValidity()) {
        if (props.typeForm === 'create') {
            form.post(route('politics.store'), {
                preserveScroll: true,
                preserveState: true,
                only: ['errors', 'politicas'],
                onSuccess: () => {
                    close()
                }
            });
        } else {
            form.transform((data) => ({
                ...data,
                _method: 'put',//debido que no soporta subir archivos el method put
            })).post(route('politics.update', props.politic.id), {
                _method: 'put',
                preserveScroll: true,
                preserveState: true,
                only: ['errors', 'politicas'],
                onSuccess: () => {
                    close()
                }
            });
        }
    }
}


const deletePolitica = () => {
    processingDelete.value = true;
    Inertia.delete(route('politics.destroy', props.politic.id), {
        preserveScroll: true,
        preserveState: true,
        only: ['errors', 'politicas'],
        onSuccess: () => {
            processingDelete.value = false;
            close()

        }
    })
}

const titleModal = computed(() => {
    if (props.typeForm === 'update') {
        form.namepolitica = props.politic.namepolitica
        form.descripcion = props.politic.descripcion
        form.type_politic = props.politic.type_politic
        form.autor = props.politic.autor
        form.imagePolitic = null;
        form.pdf = null;
        return 'Actualizar';
    }
    return 'Crear;'
})




</script>

<template>
    <DialogModal :show="show" maxWidth="2xl" @close="close()">
        <template #title>
            <h2 class="font-semibold text-md">
                {{ titleModal }} Politica
            </h2>
        </template>
        <template #content>
            <form id="formPolitic" @submit.prevent="createOrUpdate()">
                <div class="grid grid-cols-2 gap-2 md:grid-cols-2">
                    <div class="mt-2">
                        <InputLabel for="namepolitica">
                            Nombre de la Politica:<span class="text-red-400">*</span>
                        </InputLabel>

                        <TextInput id="namepolitica" type="text" v-model="form.namepolitica"
                            placeholder="Nombre Politica" class="block w-full mt-1" maxlength="60" required />
                        <InputError :message="form.errors.namepolitica" class="mt-2" />
                    </div>
                    <div class="mt-2">
                        <InputLabel for="descripcion">
                            Descripci&oacute;n:<span class="text-red-400">*</span>
                        </InputLabel>
                        <TextInput id="descripcion" type="text" v-model="form.descripcion" class="block w-full mt-1"
                            required maxlength="100" />
                        <InputError :message="form.errors.descripcion" class="mt-2" />
                    </div>
                    <div class="mt-2">
                        <InputLabel for="type_politic">
                            Tipo de Politica<span class="text-red-400">*</span>
                        </InputLabel>
                        <SelectComponent v-model="form.type_politic" required>
                            <option v-for="tipoPolit in tipoPoliticas" :key="tipoPolit.id" :value="tipoPolit.id">
                                {{ tipoPolit.name }}
                            </option>
                        </SelectComponent>

                        <InputError :message="form.errors.type_politic" class="mt-2" />
                    </div>
                    <div class="mt-2">
                        <InputLabel for="autor">
                            Author<span class="text-red-400">*</span>
                        </InputLabel>
                        <ListDataInput list="list_users" v-model="form.autor" :options="listusers" class="flex-1" />
                        <InputError :message="form.errors.autor" class="mt-2" />
                    </div>
                    <div class="mt-2">
                        <InputLabel for="imagePolitic">
                            Imagen:
                        </InputLabel>
                        <input type="file" class="hidden" ref="fileImage" name="imagePolitic" @change="setFileNameImg()"
                            accept=".jpg,.jpeg,.png">
                        <span class="text-xs">{{ fileNameImg }}</span>
                        <SecondaryButton type="button" @click="selectFileImage()">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="currentColor"
                                viewBox="0 0 16 16">
                                <path
                                    d="M8 6.5a.5.5 0 0 1 .5.5v1.5H10a.5.5 0 0 1 0 1H8.5V11a.5.5 0 0 1-1 0V9.5H6a.5.5 0 0 1 0-1h1.5V7a.5.5 0 0 1 .5-.5z" />
                                <path
                                    d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5L14 4.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5h-2z" />
                            </svg>
                            SELECIONA UNA IMAGEN
                        </SecondaryButton>
                        <InputError :message="form.errors.imagePolitic" class="mt-2" />
                    </div>
                    <div class="mt-2">
                        <InputLabel for="documento">
                            Documento:<span class="text-red-400">*</span>
                        </InputLabel>
                        <input type="file" class="hidden" ref="file" name="pdf"
                            @input="form.pdf = $event.target.files[0]" @change="setFileName()">

                        <span class="text-xs">{{ fileName }}</span>
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
                        <InputError :message="form.errors.pdf" class="mt-2" />
                    </div>

                </div>
            </form>
        </template>

        <template #footer>
            <div class="mx-2 my-2">
                <SecondaryButton @click="close()">
                    Cancelar
                </SecondaryButton>
                <DangerButton v-if="typeForm !== 'create'" class="ml-4" @click="deletePolitica()"
                    :disabled="processingDelete">
                    <SpinProgress v-if="processingDelete" :inprogress="processingDelete" /> Eliminar
                </DangerButton>
                <PrimaryButton class="ml-4" @click="createOrUpdate()" :disabled="form.processing">
                    <SpinProgress v-if="form.processing" :inprogress="form.processing" /> Guardar
                </PrimaryButton>
            </div>
        </template>
    </DialogModal>
</template>

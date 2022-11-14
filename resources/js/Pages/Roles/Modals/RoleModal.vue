<script setup>
import { useForm } from "@inertiajs/inertia-vue3";
import { computed } from "@vue/runtime-core";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import SelectComponent from "@/Components/SelectComponent.vue";
import InputError from "@/Components/InputError.vue";
import SecondaryButton from '@/Components/SecondaryButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SpinProgress from "@/Components/SpinProgress.vue";
import DialogModal from "@/Components/DialogModal.vue";

const emit = defineEmits(["close", "messageError"]);
const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    typeForm: {
        type: String
    },
    role: {
        type: Object,
        require: true
    },
});


const form = useForm({
    id: -1,
    nombre: '',
});


const close = () => {
    emit('close');
}

const title = computed(() => {
    if (props.typeForm == 'create') {
        form.reset();
        return '<svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-5 h-5" fill="currentColor" viewBox="0 0 16 16">'
            + '<path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>'
            + '<path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>'
            + '</svg> Nuevo Usuario';
    } else {
        form.id = props.role.id
        form.name = props.role.name;
        form.description = props.role.description;


        return 'Actualizar Usuario';
    }
})


const createOrUpdate = async () => {
    if (document.getElementById("formRole").reportValidity()) {
        if (props.typeForm === 'create') {
            form.post(route('roles.store'), {
                preserveScroll: true,
                onSuccess: () => {
                    close()
                }
            });
        } else {

            form.put(route('roles.update', props.role.id), {
                preserveScroll: true,
                onSuccess: () => {
                    close()
                },
                onError: (error) => {
                    console.log(error);
                }
            });
        }
    }
}

</script>

<template>
    <DialogModal :show="show" maxWidth="2xl" @close="close()">
        <template #title>
            <h2 class="font-semibold text-md" v-html="title"></h2>
        </template>
        <template #content>
            <form id="formRole">
                <div class="">
                    <div class="mt-4">
                        <InputLabel for="name">
                            Nombre:<span class="text-red-400">*</span>
                        </InputLabel>

                        <TextInput id="name" type="text" placeholder="Role Name" v-model="form.nombre"
                            class="block w-full mt-1" required maxlength="40" />
                        <InputError :message="form.errors.nombre" class="mt-2" />
                    </div>
                </div>
            </form>
        </template>

        <template #footer>
            <div class="mx-2 my-2">
                <SecondaryButton @click="close()">
                    Cancel
                </SecondaryButton>
                <PrimaryButton class="ml-4" @click="createOrUpdate()" :disabled="form.processing">
                    <SpinProgress v-if="form.processing" :inprogress="form.processing" /> Guardar
                </PrimaryButton>
            </div>
        </template>
    </DialogModal>
</template>

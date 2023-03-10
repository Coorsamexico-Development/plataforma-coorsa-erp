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
import ButtonAdd from '@/Components/ButtonAdd.vue';

const emit = defineEmits(["close", "messageError"]);
const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    departamento:String
});

const fileImage = ref(null)
const fileName = ref("");
const fileNameImg = ref("");
const file = ref(null)

const formProceso = useForm({
    'nombre': null,
    'descripción': null,
    "logo": null,
    "departamento_auditoria_id": props.departamento
});

const selectFileImage = () => {
    fileImage.value.click();
}

const setFileNameImg = () => {
    fileNameImg.value = fileImage.value.files[0].name;
    formProceso.logo = fileImage.value.files[0]
}

const create = () => 
{
   formProceso.post(route('storeProceso'),
   {
    preserveScroll:true,
    preserveState:true,
    onSuccess:() => formProceso.reset(),
    onFinish:() => close()
   })
}

const close = () => {
    emit('close');
}

</script>

<template>
    <DialogModal :show="show" maxWidth="2xl" @close="close()">
        <template #title>
            <div class="grid grid-cols-2">
             <div class="flex justify-start">
                <h2 class="font-bold tracking-widest text-md uppercase">
                   Nuevo proceso
                </h2>
            </div>
            <div class="flex justify-end">
               <button @click="close" class="bg-[#EC2944] pl-4 pr-4  text-white rounded-full">
                  <svg xmlns="http://www.w3.org/2000/svg" width="11.606" height="11.606" viewBox="0 0 11.606 11.606">
                   <g id="Grupo_581" data-name="Grupo 581" transform="translate(-15.921 -7.94)">
                     <line id="Línea_4" data-name="Línea 4" x2="12.413" transform="translate(17.335 18.132) rotate(-45)" fill="none" stroke="#fff" stroke-linecap="round" stroke-width="2"/>
                     <line id="Línea_5" data-name="Línea 5" y2="12.413" transform="translate(17.335 9.355) rotate(-45)" fill="none" stroke="#fff" stroke-linecap="round" stroke-width="2"/>
                   </g>
                 </svg>
               </button>
            </div>
          </div>
        </template>
        <template #content>
            <form  @submit.prevent="create()">
                <div class=" grid grid-cols-3">
                  <div>
                    <InputLabel>Nombre del proceso</InputLabel>
                    <TextInput v-model="formProceso.nombre"></TextInput>
                  </div>
                  <div>
                    <InputLabel>Descripción</InputLabel>
                    <TextInput v-model="formProceso.descripción"></TextInput>
                  </div>
                  <div>
                    <InputLabel>Logo</InputLabel>
                    <input type="file" class="hidden" ref="fileImage" name="imagePolitic" @change="setFileNameImg()"
                            accept=".jpg,.jpeg,.png,.svg">
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
                  </div>
                </div>
            </form>
            <div class="grid grid-cols-4 mt-4">
                <ButtonAdd class="ml-4 col-start-4 flex justify-center" @click="create()" :disabled="formProceso.processing">
                  <SpinProgress v-if="formProceso.processing" :inprogress="formProceso.processing" /> Guardar
               </ButtonAdd>
            </div>
        </template>
    </DialogModal>
</template>

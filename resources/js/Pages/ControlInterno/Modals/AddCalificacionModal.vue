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
});

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
                   Agregar calificación
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

        </template>
    </DialogModal>
</template>

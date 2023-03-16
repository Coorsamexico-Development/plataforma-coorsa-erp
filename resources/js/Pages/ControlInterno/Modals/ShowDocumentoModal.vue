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
import ButtonAdd  from "../../../Components/ButtonAdd.vue";
import TableDocumentos from "../Partials/TableDocumentos.vue";
import AddDocumentoModal from "../Modals/AddDocumentoModal.vue";

const emit = defineEmits(["close", "messageError", 'recargarDocs']);
const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    departamento:String,
    usuarios:Object,
    procesoId:Number,
    documentos:Object
});


const addDocumentoModal = ref(false);
const openAddDocumentoModal = () => 
{
  console.log(props.documentos)
  addDocumentoModal.value = true;
}

const closeDocumentoModal = () => 
{
  addDocumentoModal.value = false;
}

const close = () => {
    emit('close');
}

const recargarDocumentos = () => 
{
  emit('recargarDocs', props.procesoId)
}
</script>

<template>
    <DialogModal :show="show" maxWidth="2xl" @close="close()">
        <template #title>
          <div class="grid grid-cols-2">
             <div class="flex justify-start">
                <h2 class="font-bold tracking-widest uppercase text-md">
                   Documentos
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
         <div class="mt-2 mb-8">
            <TableDocumentos :usuarios="usuarios"  :procesoId="procesoId" @recargarDocs="recargarDocumentos" :documentos="documentos" />
         </div>
         <div class="flex justify-end">
             <ButtonAdd @click="openAddDocumentoModal">Agregar</ButtonAdd>
         </div> 
         <AddDocumentoModal @recargarDocs="recargarDocumentos" :procesoId="procesoId" :usuarios="usuarios" :show="addDocumentoModal" @close="closeDocumentoModal" />
        </template>
    </DialogModal>
</template>

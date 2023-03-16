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

const emit = defineEmits(["close", "messageError", 'recargarDocs']);

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    procesoId:Number,
    usuarios:Object, 
    documentoId:Number
});


const fecha = new Date();
const mesActual = fecha.getMonth()+1;

const documentoForm = useForm({
    'user_id': null,
    'proceso_id': props.procesoId,
    "documento": null,
    "mes":mesActual,
    "portada":null
});

const fileName = ref("");
const file = ref(null)
const setFileName = () => 
{
    fileName.value = file.value.files[0].name;
}

const selectFile = () => {
    file.value.click();
}


const portadaName = ref("");
const portada = ref(null);

const setPortadaName = () =>
{
  portadaName.value = portada.value.files[0].name;
}

const selectPortada = () => 
{
  portada.value.click();
}

const close = () => {
    emit('close');
}

const save = () => 
{
   documentoForm.post(route('updateDocumento', props.documentoId),
   {
    preserveScroll:true,
    preserveState:true,
    onSuccess:() => documentoForm.reset(),
    onProgress:() =>close(),
    onFinish:() => recargarDocs()
   });
}

const recargarDocs = () =>
{
   emit('recargarDocs')
}
</script>
<template>
        <DialogModal :show="show" maxWidth="2xl" @close="close()">
        <template #title>
          <div class="grid grid-cols-2">
             <div class="flex justify-start">
                <h2 class="font-bold tracking-widest uppercase text-md">
                   Editar documento
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
           <div class="grid grid-cols-2">
             <div class="m-2">
               <InputLabel>Autor</InputLabel>
               <input v-model="documentoForm.user_id" list="lisEmpleados" class="w-full py-2 text-sm text-black bg-white border-2 border-black rounded-md shadow-sm focus:border-black focus:ring focus:ring-gray-200 focus:ring-opacity-50 disabled:bg-gray-300" >
               <datalist id="lisEmpleados">
                 <option v-for="empleado in usuarios" :key="empleado.id" :value="empleado.id">
                    {{ empleado.name + ' ' + empleado.apellido_paterno + ' ' +   empleado.apellido_materno  }} 
                 </option>
               </datalist>
             </div>
             <div class="m-2">
                <InputLabel>Documento</InputLabel>
                <input type="file" class="hidden" ref="file" name="pdf"
                            @input="documentoForm.documento = $event.target.files[0]" @change="setFileName()">
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
                  <InputError :message="documentoForm.errors.documento" class="mt-2" />
             </div>
             <div class="m-2">
                <InputLabel>Portada</InputLabel>
                <input type="file" class="hidden" ref="portada"
                 @input="documentoForm.portada = $event.target.files[0]" @change="setPortadaName()" /> 
                 <span class="text-xs">{{ portadaName }}</span>
                <SecondaryButton type="button" @click="selectPortada()">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="currentColor"
                          viewBox="0 0 16 16">
                          <path
                              d="M8 6.5a.5.5 0 0 1 .5.5v1.5H10a.5.5 0 0 1 0 1H8.5V11a.5.5 0 0 1-1 0V9.5H6a.5.5 0 0 1 0-1h1.5V7a.5.5 0 0 1 .5-.5z" />
                          <path
                              d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5L14 4.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5h-2z" />
                  </svg>
                  SELECCIONA UNA PORTADA
                </SecondaryButton>
             </div>
           </div>
           <div class="grid grid-cols-4 mt-4">
                <ButtonAdd class="flex justify-center col-start-4 ml-4" @click="save()" :disabled="documentoForm.processing">
                  <SpinProgress v-if="documentoForm.processing" :inprogress="documentoForm.processing" /> Guardar
               </ButtonAdd>
          </div>
        </template>
    </DialogModal>
</template>
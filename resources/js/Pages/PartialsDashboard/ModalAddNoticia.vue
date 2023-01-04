<script setup>
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import DialogModal from '@/Components/DialogModal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { usePage } from '@inertiajs/inertia-vue3'
import ButtonModal from '../../Components/ButtonModal.vue';
import SelectComponent from '@/Components/SelectComponent.vue';
import DropZone from '@/Components/DropZone.vue';

const emit = defineEmits(["close"])

const user =  usePage().props.value.user.id;

let tipoContenido = ref(null);

const fileUpload = ref(null);

const contentUpload = ref(null);

const noticiaForm = useForm({
    titulo:"",
    imagen:null,
    autor: user,
    activo:1,
    descripcion:"",
    contenidoImg:null,
    contenidoVideo:null
});

const selectNewFile = () => 
{
    fileUpload.value.click();
    tipoContenido.value.click();
};

const uploadFile = () =>
{
  const file = fileUpload.value.files[0];
  noticiaForm.imagen  = file;
};


const uploadContentFile = () => 
{
    const newfile = contentUpload.value.files[0];
    noticiaForm.contenidoImg = newfile;
}

const enviarNuevaNoticia = () =>
{
    noticiaForm.post(route('noticia.store'),{
       onFinish: () => noticiaForm.reset(),
       onSuccess: () =>  close() 
    })
}
const close = () => {
        
        emit('close');
    };

</script>
<template>
     <DialogModal :show="show" @close="close()">
           <template #title>
               <h2 style="font-weight:bolder">Nueva noticia</h2>
            </template>
            <template #content>
               <form v-on:submit.prevent="enviarNuevaNoticia()">
                <div style="display: flex;">
                  <div style="float:left; margin: 2rem; margin-top: 0rem;" >
                     <InputLabel>Titulo de noticia</InputLabel>
                     <TextInput v-model="noticiaForm.titulo" class="border-2 border-black border-solid "></TextInput>
                     <InputLabel class="mt-4">Portada:</InputLabel>
                     <DropZone v-model="noticiaForm.imagen"></DropZone>
                     <InputLabel class="mt-4">Tipo de contenido.</InputLabel>
                     <SelectComponent v-model="tipoContenido">
                        <option selected disabled>Selecciona un tipo</option>
                        <option value="video">Video</option>
                        <option value="imagen">Imagen</option>
                     </SelectComponent>
                   </div> 
                   <div>
                     <InputLabel>Descripción</InputLabel>
                     <textarea v-model="noticiaForm.descripcion"></textarea>
                     <div v-if="tipoContenido != null">
                        <InputLabel class="mt-9">Contenido</InputLabel>
                        <TextInput v-if="tipoContenido == 'video'" v-model="noticiaForm.contenido" class="border-2 border-black border-solid"></TextInput>
                        <DropZone v-if="tipoContenido == 'imagen'" v-model="noticiaForm.contenidoImg" ></DropZone>
                     </div>
                   </div>
                </div>
                  <ButtonModal  type="submit">Enviar</ButtonModal>
               </form>
            </template>
            
            <template #footer>
                <SecondaryButton  @click="close()" class="closeModal1" style="float:right">
                      Cerrar
                </SecondaryButton>
            </template>
        </DialogModal>
</template>
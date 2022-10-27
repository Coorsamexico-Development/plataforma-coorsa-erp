<script setup>
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import DialogModal from '@/Components/DialogModal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { usePage } from '@inertiajs/inertia-vue3'
import ButtonModal from '../../Components/ButtonModal.vue';

const emit = defineEmits(["close"])

const user =  usePage().props.value.user.id;

const fileUpload = ref(null);

const noticiaForm = useForm({
    imagen:null,
    autor: user,
    activo:1
});

const selectNewFile = () => 
{
    fileUpload.value.click();
};

const uploadFile = () =>
{
  const file = fileUpload.value.files[0];
  noticiaForm.imagen  = file;
};


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
                     <InputLabel>Noticia:</InputLabel>
                     <input type="file" enctype="multipart/form-data"
                         ref="fileUpload"
                         @change="uploadFile">
                   </div> 
                </div>
                  <ButtonModal type="submit">Enviar</ButtonModal>
               </form>
            </template>
            
            <template #footer>
                <SecondaryButton  @click="close()" class="closeModal1" style="float:right">
                      Cerrar
                </SecondaryButton>
            </template>
        </DialogModal>
</template>
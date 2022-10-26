<script setup>
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import DialogModal from '@/Components/DialogModal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { usePage } from '@inertiajs/inertia-vue3'

const emit = defineEmits(["close",])

const user =  usePage().props.value.user.id;

const menuForm = useForm
    ({
    nombre: '',
    descripcion: '',
    imagen:'',
    autor: user,
    activo:1
    });

const enviarNuevoMenu = () =>
{
    formSolicitud.post('/menu');
    close();
    formSolicitud.reset();
}
const close = () => {
        
        emit('close');
    };

</script>
<template>
     <DialogModal :show="show" @close="close()">
           <template #title>
               <h2 style="font-weight:bolder">MENU DEL DIA</h2>
            </template>
            <template #content>
               <form>
                <div style="display: flex;">
                  <div style="float:left; margin: 2rem; margin-top: 0rem;" >
                     <InputLabel>Nombre de menú:</InputLabel>
                     <TextInput style="border:solid 0.15rem #26458D" v-model="menuForm.nombre"></TextInput>
                   </div> 
                   <div style="float:left; margin: 2rem;margin-top: 0rem;">
                     <InputLabel>Descripción de menú:</InputLabel>
                     <TextInput style="border:solid 0.15rem #26458D" v-model="menuForm.descripcion"></TextInput>
                   </div>
                </div>
                <div style="display: flex;">
                  <div style="float:left; margin: 2rem; margin-top: 0rem;" >
                     <InputLabel>Imagen:</InputLabel>
                     <TextInput type="file" style="border:solid 0.15rem #26458D" v-model="menuForm.imagen"></TextInput>
                   </div> 
                </div>
               </form>
            </template>
            
            <template #footer>
                <SecondaryButton  @click="close()" class="closeModal1" style="float:right">
                      Cerrar
                </SecondaryButton>
            </template>
        </DialogModal>
</template>
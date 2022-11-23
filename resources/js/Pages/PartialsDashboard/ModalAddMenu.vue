<script setup>
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import DialogModal from '@/Components/DialogModal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { usePage } from '@inertiajs/inertia-vue3'
import ButtonModal from '../../Components/ButtonModal.vue';

const emit = defineEmits(["close",])

const user =  usePage().props.value.user.id;

const menuForm = useForm({
    descripcion: '',
    autor: user,
    activo:1
});

const enviarNuevoMenu = () =>
{
    menuForm.post(route('menu.store'),{
       onFinish: () => menuForm.reset(),
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
               <h2 style="font-weight:bolder">MENU DEL DIA</h2>
            </template>
            <template #content>
               <form v-on:submit.prevent="enviarNuevoMenu()">
                <div style="display: flex;">

                   <div style="float:left; margin: 2rem;margin-top: 0rem;">
                     <InputLabel>Descripción de menú:</InputLabel>
                     <textarea style="border:solid 0.15rem #26458D; width:30rem; height:10rem;" v-model="menuForm.descripcion"></textarea>
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
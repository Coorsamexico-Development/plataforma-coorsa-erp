<script setup>

import { Inertia } from '@inertiajs/inertia'
import Card from '../../Components/Card.vue';
import { onMounted, reactive, ref, watch } from 'vue';
import moment from 'moment';
import ButtonModal from '../../Components/ButtonModal.vue';
import ModalNomina from './ModalNomina.vue';
import { usePage } from '@inertiajs/inertia-vue3';

var props = defineProps({
   
});

const user = usePage().props.value.user;
console.log(user)

let modalNomina = ref(false);

const showModalNomina = () =>
{
    modalNomina.value=true;
}

const closeModalNomina = () =>
{
    modalNomina.value = false;
}



</script>

<template>
    <Card style=" width:60rem;  margin:0.5rem; padding: 1.5rem;" >
        <h4 style="color:#26458D; font-weight:bolder">Nuestro Colaborador.</h4>
        <div>
            <p>
                <strong>Nombre:</strong> {{user.name}}
            </p>
        </div>
        <ButtonModal  @click="showModalNomina">Consultar</ButtonModal>
        <ModalNomina :show="modalNomina" @close="closeModalNomina" :user="user"></ModalNomina>
    </Card>
</template>

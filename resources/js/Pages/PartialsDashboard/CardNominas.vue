<script setup>

import { Inertia } from '@inertiajs/inertia'
import Card from '../../Components/Card.vue';
import { onMounted, reactive, ref, watch } from 'vue';
import moment from 'moment';
import ButtonModal from '../../Components/ButtonModal.vue';
import ModalNomina from './ModalNomina.vue';

var props = defineProps({
   
});

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
    <Card style=" width: 24rem;  margin:0.5rem; padding: 1.5rem;" >
        <h4 style="color:#26458D; font-weight:bolder">Recibos</h4>
        <ButtonModal  @click="showModalNomina">Consultar</ButtonModal>
        <ModalNomina :show="modalNomina" @close="closeModalNomina"></ModalNomina>
    </Card>
</template>

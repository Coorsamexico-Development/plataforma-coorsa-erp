<script setup>

import { Inertia } from '@inertiajs/inertia'
import Card from '../../Components/Card.vue';
import ButtonModal from '../../Components/ButtonModal.vue';
import { onMounted, reactive, ref, watch } from 'vue';
import moment from 'moment';
import ModalAddMenu from './ModalAddMenu.vue';

var props = defineProps({
    menus:Object
});
let fecha = ref("");
var now = moment().format("YYYY-MM-DD");
fecha.value = now;

let modalMenu = ref(false);

const abrirModalMenu = () => {
    modalMenu.value =true;
}

const closeModalMenu = () => {
    modalMenu.value = false;
}

</script>

<template>
    <Card style="padding:2rem; padding-right: 2rem; width: 24rem; margin:0.5rem" >
        <h4 style="color:#26458D; font-weight:bolder">Menú del dia</h4>
        <h5>Fecha: {{fecha}}</h5>
        {{menus[0].nombre}}
        <img  :src="menus[0].image">
        <p>
            {{menus[0].descripcion}} 
        </p>
        
       <ButtonModal @click="abrirModalMenu">Añadir nuevo menu</ButtonModal>
       <ModalAddMenu :show="modalMenu" @close="closeModalMenu"></ModalAddMenu>
    </Card>
</template>

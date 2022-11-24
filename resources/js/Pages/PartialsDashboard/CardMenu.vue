<script setup>

import { Inertia } from '@inertiajs/inertia'
import Card from '../../Components/Card.vue';
import ButtonModal from '../../Components/ButtonModal.vue';
import { onMounted, reactive, ref, watch } from 'vue';
import moment from 'moment';
import ModalAddMenu from './ModalAddMenu.vue';

var props = defineProps({
    menus: Object
});
let fecha = ref("");
var now = moment().format("YYYY-MM-DD");
fecha.value = now;

let modalMenu = ref(false);

const abrirModalMenu = () => {
    modalMenu.value = true;
}

const closeModalMenu = () => {
    modalMenu.value = false;
}

console.log(props.menus.length);

</script>

<template>
    <Card style="padding:2rem; padding-right: 2rem; width: 24rem; margin:0.5rem">
        <div style="display:flex; justify-content: center; align-items: center;">
            <div style="background-color:#dedede; width:5rem; display:flex; justify-content:center; border-radius: 10rem;">
                <img style="width:3rem;" src="../../../img/icono_1.png" />
            </div>
            <div style="margin:1rem">
                <h4 style="color:#26458D; font-weight:bolder;">Menú del dia</h4>
                <h5 style="color:#6e6e6e; font-weight: bolder;">Fecha: {{ fecha }}</h5>
            </div>
        </div>
        <div v-if="menus.length > 0">
            {{ menus[0].nombre }}
            <p style="color:#707070;">
                {{ menus[0].descripcion }}
            </p>
        </div>
        <div v-else>
            <h3 style="color:#707070; font-weight:bolder;">Aun no hay menú para el día de hoy.</h3>
        </div>

        <ButtonModal v-if="$page.props.can['menu.create']" @click="abrirModalMenu">Añadir nuevo menu</ButtonModal>
        <ModalAddMenu :show="modalMenu" @close="closeModalMenu"></ModalAddMenu>
    </Card>
</template>

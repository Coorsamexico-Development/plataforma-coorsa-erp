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

console.log(props.menus.length);

</script>

<template>
    <Card style="padding:2rem; padding-right: 2rem; width: 24rem; margin:0.5rem" >
        <h4 style="color:#26458D; font-weight:bolder">Menú del dia</h4>
       <div v-if="menus.length > 0">
          <h5>Fecha: {{fecha}}</h5>
            {{menus[0].nombre}}
             <p>
              {{menus[0].descripcion}} 
            </p>
         
       </div>
       <div v-else>
         <h5>Fecha: {{fecha}}</h5>
         <h3>Aun no hay menú :C</h3>
       </div>
        
       <ButtonModal @click="abrirModalMenu">Añadir nuevo menu</ButtonModal>
       <ModalAddMenu :show="modalMenu" @close="closeModalMenu"></ModalAddMenu>
    </Card>
</template>

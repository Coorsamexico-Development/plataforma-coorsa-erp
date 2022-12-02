<script setup>
import { usePage } from '@inertiajs/inertia-vue3';
import { onMounted, reactive, ref, watch } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import Card from '../../Components/Card.vue';
import ButtonIcon from '../../Components/ButtonIcon.vue';
import moment from 'moment';
import ButtonAdd from '@/Components/ButtonAdd.vue';
import ModalAddMenu from './ModalAddMenu.vue';

var props = defineProps({
   menus:Object
});

const user = usePage().props.value.user;
let fecha = ref("");
var now = moment().format("YYYY-MM-DD");
fecha.value = now;

let buttonSelected = ref(-1);

let modalMenu = ref(false);

const abrirModalMenu = () => {
    modalMenu.value = true;
}

const closeModalMenu = () => {
    modalMenu.value = false;
}


const cambiar = (id) => 
{
    buttonSelected.value = id;

    if (window.matchMedia("(min-width: 1800px)").matches) 
    {
      /* La pantalla tiene al menos 1800 píxeles de ancho */
      if(buttonSelected.value === -1)
        {
          let card_MV = document.getElementsByClassName('card_MV');
          card_MV[0].style.width = "40rem";
          
          let card_Videos = document.getElementsByClassName('card_videos');
          card_Videos[0].style.width = "40rem";
    
          let card_noticias = document.getElementsByClassName('card_noticias');
          card_noticias[0].style.width = "60rem";
    
          let carrusel_noticias = document.getElementsByClassName('carrusel_noticias');
          carrusel_noticias[0].style.width = "60rem";
    
        }
        
        if(buttonSelected.value === 1 )
        {
            let card_MV = document.getElementsByClassName('card_MV');
          card_MV[0].style.width = "38rem";
          
          let card_Videos = document.getElementsByClassName('card_videos');
          card_Videos[0].style.width = "38rem";
    
          let card_noticias = document.getElementsByClassName('card_noticias');
          card_noticias[0].style.width = "40rem";
    
          let carrusel_noticias = document.getElementsByClassName('carrusel_noticias');
          carrusel_noticias[0].style.width = "40rem";
    
        }
    }
    else
    {
        if(buttonSelected.value === -1)
        {
          let card_MV = document.getElementsByClassName('card_MV');
          card_MV[0].style.width = "40rem";
          
          let card_Videos = document.getElementsByClassName('card_videos');
          card_Videos[0].style.width = "40rem";
    
          let card_noticias = document.getElementsByClassName('card_noticias');
          card_noticias[0].style.width = "45rem";
    
          let carrusel_noticias = document.getElementsByClassName('carrusel_noticias');
          carrusel_noticias[0].style.width = "45rem";
    
    
        }
        
        if(buttonSelected.value === 1  || buttonSelected.value == 2)
        {
            let card_MV = document.getElementsByClassName('card_MV');
          card_MV[0].style.width = "36rem";
          
          let card_Videos = document.getElementsByClassName('card_videos');
          card_Videos[0].style.width = "36rem";
    
          let card_noticias = document.getElementsByClassName('card_noticias');
          card_noticias[0].style.width = "36rem";
    
          let carrusel_noticias = document.getElementsByClassName('carrusel_noticias');
          carrusel_noticias[0].style.width = "36rem";
    
        }

        if (window.matchMedia("(max-width: 1366px)").matches) 
        {
            /* La pantalla tiene al menos 1800 píxeles de ancho */
           if(buttonSelected.value === -1)
             {
               let card_MV = document.getElementsByClassName('card_MV');
               card_MV[0].style.width = "40rem";
               
               let card_Videos = document.getElementsByClassName('card_videos');
               card_Videos[0].style.width = "40rem";
         
               let card_noticias = document.getElementsByClassName('card_noticias');
               card_noticias[0].style.width = "36rem";
         
               let carrusel_noticias = document.getElementsByClassName('carrusel_noticias');
               carrusel_noticias[0].style.width = "36rem";
         
             }
             
             if(buttonSelected.value === 1 )
             {
                 let card_MV = document.getElementsByClassName('card_MV');
               card_MV[0].style.width = "28rem";
               
               let card_Videos = document.getElementsByClassName('card_videos');
               card_Videos[0].style.width = "28rem";
         
               let card_noticias = document.getElementsByClassName('card_noticias');
               card_noticias[0].style.width = "33rem";
         
               let carrusel_noticias = document.getElementsByClassName('carrusel_noticias');
               carrusel_noticias[0].style.width = "33rem";
         
             }
        }

    }

}

</script>
<template>
   <Card style="height:40.5rem; align-content: center;display: flex; align-items: center;">
     <div class="menu_icons">
        <ButtonIcon v-if="(buttonSelected === 1 || buttonSelected ===2)" class="flecha_contract" @click="cambiar(-1)" style="margin-top:-15rem; margin-bottom:10rem;">
            <svg style="fill:white; width:2rem; transform: rotate(180deg);" id="Capa_1" data-name="Capa 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 30 17">
                <path id="Trazado_104" data-name="Trazado 104" class="cls-1" d="M27.77,8.5c-.16,.55-.71,.91-1.28,.83-7.04-.01-14.08-.01-21.12,0h-.35c.11,.12,.18,.2,.26,.27,1.1,1.1,2.21,2.2,3.31,3.3,.29,.26,.39,.67,.28,1.04-.09,.37-.4,.65-.77,.71-.36,.08-.73-.04-.98-.32-1-1-2-2-3-2.99-.75-.74-1.49-1.48-2.24-2.23-.45-.36-.52-1.01-.16-1.46,.05-.06,.1-.11,.16-.16,1.73-1.73,3.47-3.45,5.2-5.18,.36-.44,1.01-.51,1.45-.15,.03,.02,.05,.04,.08,.07,.4,.4,.4,1.06,0,1.46-.02,.02-.05,.04-.07,.06-1.09,1.09-2.18,2.17-3.27,3.26-.07,.07-.14,.12-.22,.18l.02,.07h.28c7.05,0,14.1,0,21.15,0,.57-.08,1.12,.27,1.28,.83v.41Z"/>
            </svg>
        </ButtonIcon>
        <ButtonIcon @click="cambiar(1)" style="margin-bottom:3rem">
            <svg style="fill:white;width:2rem;" id="Capa_1" data-name="Capa 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 35 36">
              <path id="Trazado_48" data-name="Trazado 48" class="cls-1" d="M33.51,32.64c-.04,.1-.07,.19-.11,.29-.23,.86-.99,1.47-1.88,1.52-1.31,.07-2.63,.02-3.91,.02,.24-.52,.43-1.06,.57-1.61,.11-.66,.13-1.34,.05-2.01-.12-.91-.76-1.66-1.64-1.92-.16-.04-.28-.18-.28-.34-.5-4.65-3.55-8.63-7.9-10.33-.28-.12-.57-.22-.87-.3-.23-.06-.29-.16-.29-.39,.01-1.27,0-2.53,0-3.8V4.52c-.12-1.15,.66-2.19,1.79-2.41,.02,0,.03-.03,.05-.04h12.56c.63,.14,1.18,.53,1.52,1.09,.12,.24,.22,.49,.32,.75v28.74ZM25.36,14.35h3.49c.52,.06,1-.32,1.06-.84,.06-.52-.32-1-.84-1.06-.07,0-.14,0-.21,0h-6.91c-.52-.06-1,.32-1.06,.84-.06,.52,.32,1,.84,1.06,.07,0,.14,0,.21,0,1.14,0,2.28,0,3.42,0h0Zm0,3.8h3.49c.52,.06,1-.32,1.06-.84,.06-.52-.32-1-.84-1.06-.07,0-.14,0-.21,0-2.3,0-4.61,0-6.91,0-.52-.06-1,.32-1.06,.84-.06,.52,.32,1,.84,1.06,.07,0,.14,0,.21,0,1.14,0,2.28,0,3.42,0h0Zm.02-9.5c.39,0,.78,0,1.17,0,.52,0,.95-.42,.95-.95s-.42-.95-.95-.95c-.77,0-1.54,0-2.31,0-.52,0-.95,.42-.95,.95s.42,.95,.95,.95c.38,0,.76,0,1.14,0h0Z"/>
              <path id="Trazado_49" data-name="Trazado 49" class="cls-1" d="M1.03,31.12c.17-.31,.51-.49,.86-.45,7.88,0,15.77,.01,23.65,0,.65,0,.87,.2,.84,.85,0,.45-.06,.9-.19,1.33-.33,.96-1.24,1.61-2.25,1.61-.4,.03-.8,0-1.2,0H3.82c-1.33,.13-2.53-.79-2.75-2.1,0-.02-.02-.03-.04-.05v-1.21Z"/>
              <path id="Trazado_50" data-name="Trazado 50" class="cls-1" d="M12.75,19.27v-2.02h-.63c-.53,.03-.98-.37-1.01-.9,0-.02,0-.03,0-.05,0-.52,.41-.94,.93-.95,.02,0,.05,0,.07,0,1.07,0,2.13,0,3.2,0,.52-.04,.97,.35,1,.87,0,.03,0,.05,0,.08,0,.53-.43,.95-.95,.95-.02,0-.04,0-.06,0h-.62v1.98c5.48,.89,8.73,4.05,9.79,9.53H3.02c.59-5.07,4.65-9.03,9.73-9.49Zm-2.52,4.25c0-.33-.2-.64-.5-.78-.32-.18-.72-.15-1.01,.07-.94,.66-1.72,1.54-2.27,2.55-.27,.45-.13,1.03,.31,1.31,0,0,.01,0,.02,.01,.44,.26,1.01,.12,1.28-.32,.01-.02,.02-.03,.03-.05,.44-.77,1.03-1.44,1.75-1.97,.27-.19,.42-.5,.4-.83h0Zm2.49-.17v-.03c.15,0,.3,0,.44,0,.49-.05,.87-.46,.87-.96,0-.48-.36-.87-.84-.92-.35-.02-.7,0-1.04,.06-.46,.09-.77,.5-.74,.96,.01,.46,.37,.84,.83,.89,.16,0,.32,0,.47,0h0Z"/>
            </svg>
        </ButtonIcon>
        <ButtonIcon @click="cambiar(2)">
            <svg style="fill:white;width:2rem;"  id="Capa_1" data-name="Capa 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 15.5 15">
             <g id="Grupo_48" data-name="Grupo 48">
               <path id="Trazado_55" data-name="Trazado 55" class="cls-1" d="M4.37,8.26c1.88,1.76,4.81,1.75,6.69,0,.17,.1,.34,.2,.51,.31,1.97,1.28,3.17,3.46,3.19,5.81,.04,.3-.16,.58-.46,.62-.05,0-.1,0-.15,0H1.27c-.3,.04-.57-.18-.61-.47,0-.04,0-.09,0-.13,0-2.51,1.37-4.83,3.56-6.06,.03-.02,.07-.04,.1-.06,.01,0,.03-.01,.04-.01Z"/>
               <path id="Trazado_56" data-name="Trazado 56" class="cls-1" d="M7.7,.9c2.09,0,3.79,1.69,3.8,3.78-.04,2.08-1.72,3.77-3.8,3.81-2.09,0-3.78-1.7-3.79-3.79,0-2.09,1.7-3.79,3.79-3.8Z"/>
             </g>
           </svg>
        </ButtonIcon>
     </div>
     <!--Mostrar content-->
     <div v-if="(buttonSelected==1)" style="margin:2rem">
            <div style="">
                <h4 style="color:#26458D; font-weight:bolder;">Menú del dia</h4>
                <h5 style="color:#6e6e6e; font-weight: bolder;">Fecha: {{ fecha }} </h5>
            </div>
            <div v-if="menus.length > 0" style="display:flex; justify-content: center; align-items: center;">
                {{ menus[0].nombre }}
                 <p style="color:#707070;">
                   {{ menus[0].descripcion }}
                 </p>
            </div>
            <div v-else style="display:flex; justify-content: center; align-items: center;">
               <h3 style="color:#707070; font-weight:bolder;">Aun no hay menú para el día de hoy.</h3>
           </div>
            <div style="display:flex; justify-content: center; align-items: center; margin-top:2r em">
               <ButtonAdd  v-if="$page.props.can['menu.create']" @click="abrirModalMenu">Añadir nuevo menu</ButtonAdd>
               <ModalAddMenu :show="modalMenu" @close="closeModalMenu"></ModalAddMenu>
            </div>
     </div>
     <div v-if="(buttonSelected==2)" style="margin:2rem">
        <div >
            <h4 style="color:#26458D; font-weight:bolder">Nuestro Colaborador.</h4>
        </div>
        <div>
            <p><strong>Nombre: </strong>{{(user.name + ' '+ user.apellido_paterno + ' '+user.apellido_materno)}}</p>
            <p><strong>Puesto: </strong></p>
            <p><strong>Area: </strong></p>
        </div>
        <div>
            <h4 style="color:#26458D; font-weight:bolder">Recibos de nomina.</h4>
        </div>
     </div>
   </Card>
</template>

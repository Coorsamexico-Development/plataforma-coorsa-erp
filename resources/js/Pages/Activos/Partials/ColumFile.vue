<script setup>
import { Fancybox } from "@fancyapps/ui";
import "@fancyapps/ui/dist/fancybox.css";
3
var props = defineProps(
    {
        valor:Object,
        campo:Object,
        valore:Object
    }
);
</script>
<template>
   <div v-if="campo && valor">
    <div v-if="campo.idCampo == valor.tipo_activo_campo_id">
      <a  data-fancybox :href="valor.valor" >
        <button class="bg-[#0097F2] m-2 rounded-2xl pl-2 pr-2 p-1">
           <img class="w-8" src="../../../../img/archivo.svg" />
        </button>
      </a>
    </div>
   </div>
    <div v-if="valore">
      <a  data-fancybox :href="valore.valor" >
        <button class="bg-[#0097F2] m-2 rounded-2xl pl-2 pr-2 p-1">
           <img class="w-8" src="../../../../img/archivo.svg" />
        </button>
      </a>
    </div>
</template>
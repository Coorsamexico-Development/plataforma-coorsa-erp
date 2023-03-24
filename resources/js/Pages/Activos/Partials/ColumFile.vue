<script setup>
import { Fancybox } from "@fancyapps/ui";
import "@fancyapps/ui/dist/fancybox.css";
3
var props = defineProps(
    {
        columna:Object,
        valore:Object,
        fila:Object
    }
);
</script>
<template>
    <div v-if="columna && fila">
       <div v-if="valore">
          <div v-if="columna.id == valore.columna_id && fila.id == valore.fila_id">
            <div v-if="valore.valor !==null">
               <a v-if="valore.valor.endsWith('.pdf') || valore.valor.endsWith('.png') || valore.valor.endsWith('.jpg') || valore.valor.endsWith('.svg')" 
                  data-fancybox :href="valore.valor" >
                  <button class="bg-[#0097F2] m-2 rounded-2xl pl-2 pr-2 p-1">
                     <img class="w-8" src="../../../../img/archivo.svg" />
                  </button>
               </a>
               <a v-else :href="valore.valor" download>
                 <button class="bg-[#0097F2] m-2 rounded-2xl pl-2 pr-2 p-1">
                     <img class="w-8" src="../../../../img/archivo.svg" />
                  </button>
                </a>
            </div>
          </div>
          <div v-else>
              Sin archivos
          </div>
       </div>
       <div v-else>
          Sin arcivos
       </div>
    </div>
    <div v-else>
      <div v-if="valore.valor !==null">
               <a v-if="valore.valor.endsWith('.pdf') || valore.valor.endsWith('.png') || valore.valor.endsWith('.jpg') || valore.valor.endsWith('.svg')" 
                  data-fancybox :href="valore.valor" >
                  <button class="bg-[#0097F2] m-2 rounded-2xl pl-2 pr-2 p-1">
                     <img class="w-8" src="../../../../img/archivo.svg" />
                  </button>
               </a>
               <a v-else :href="valore.valor" download>
                 <button class="bg-[#0097F2] m-2 rounded-2xl pl-2 pr-2 p-1">
                     <img class="w-8" src="../../../../img/archivo.svg" />
                  </button>
                </a>
            </div>
          <div v-else>
              Sin archivos
          </div>
    </div>
</template>
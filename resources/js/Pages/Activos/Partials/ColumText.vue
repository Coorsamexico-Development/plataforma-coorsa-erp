<script setup>
import { object } from "@amcharts/amcharts5";


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
           {{ valore.valor }}
          </div>
       </div>
    </div>
    <div v-else>
       <div v-if="valore"> 
          {{ valore.valor }}
       </div>
    </div>
</template>
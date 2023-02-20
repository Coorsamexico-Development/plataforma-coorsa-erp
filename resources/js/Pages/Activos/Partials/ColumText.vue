<script setup>
import { object } from "@amcharts/amcharts5";


var props = defineProps(
    {
        valor:Object,
        campo:Object,
        valore:Object
    }
);
</script>
<template>
    <div v-if="valor && campo">
        <p v-if="campo.idCampo == valor.tipo_activo_campo_id">
          {{valor.valor}}
        </p>
    </div>
    <div v-if="valore">
        {{ valore.valor }}
    </div>
</template>
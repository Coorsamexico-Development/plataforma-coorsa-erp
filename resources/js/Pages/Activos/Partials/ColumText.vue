<script setup>

var props = defineProps(
    {
        valor:Object,
        campo:Object
    }
);
</script>
<template>
     <p v-if="campo.idCampo == valor.tipo_activo_campo_id">
        {{valor.valor}}
     </p>
</template>
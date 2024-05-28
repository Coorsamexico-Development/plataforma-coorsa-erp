<script setup>
import { watchEffect, ref, reactive, onMounted } from "vue";
import CardCi from "../Partials/CardCi.vue";

const props = defineProps({
    show: {
        type: Object,
        default: false,
    },
});
</script>
<template>
    <CardCi></CardCi>
</template>

<script setup>
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'
import { ref, computed, reactive, watch } from 'vue';

const date = ref();

</script>
<template>
    <div class="flex flex-row">
        <h1 class="text-2xl">Registro patronal mensual</h1>
        <VueDatePicker v-model="date" @update:model-value="nuevoAño" month-change-on-arrows year-picker teleport="true" />
    </div>
</template>
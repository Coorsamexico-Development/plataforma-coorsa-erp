<script setup>
import { ref } from 'vue';

const fechaAnterior = ref('');
const fechaActual = ref('');

const error = ref('');

const enviarFechas = () => {
  // Validar que las fechas no estén vacías
  if (fechaAnterior.value === '' || fechaActual.value === '') {
    error.value = 'Todos los campos son obligatorios';

    setTimeout(() => {
        error.value = ''
    }, 3000);
    return;
  }

  // Validar que la fecha actual no sea menor a la fecha anterior
  if (new Date(fechaActual.value) < new Date(fechaAnterior.value)) {
    error.value = 'La fecha actual no puede ser menor a la fecha anterior';
    setTimeout(() => {
      error.value = '';
    }, 3000);
    return;
  }

  error.value = ''; // Limpiar el error si las fechas son válidas
  
};

</script>

<template>
  <div class="max-w-lg mx-auto mt-20 p-6 shadow-lg bg-white rounded-lg">
    <h2 class="text-xl mb-5 font-bold text-gray-800 text-center">Ingresa la fecha de corte anterior y la fecha de corte actual</h2>

    <div v-if="error" class="text-red-500 text-center py-5">{{ error }}</div>

    <form @submit.prevent="enviarFechas" method="POST" class="space-y-4">
      <div class="flex flex-col md:flex-row md:items-center space-y-2 md:space-y-0 md:space-x-4">
        <label for="fechaAnterior" class="md:w-1/3 text-gray-700 font-medium">Fecha Corte Anterior</label>
        <input
          class="flex-1 p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-500 focus:border-blue-500"
          type="date"
          id="fechaAnterior"
          v-model="fechaAnterior"
        />
      </div>
      <div class="flex flex-col md:flex-row md:items-center space-y-2 md:space-y-0 md:space-x-4">
        <label for="fechaActual" class="md:w-1/3 text-gray-700 font-medium">Fecha Corte Actual</label>
        <input
          class="flex-1 p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-500 focus:border-blue-500"
          type="date"
          id="fechaActual"
          v-model="fechaActual"
        />
      </div>

      <div class="flex justify-end mt-4">
        <input
          type="submit"
          value="Siguiente"
          class="bg-blue-500 text-white hover:bg-blue-600 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 cursor-pointer"
        />
      </div>
    </form>
  </div>
</template>


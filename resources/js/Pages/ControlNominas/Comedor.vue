<script setup>
import { ref } from 'vue';
import axios from 'axios';

const fechaActual = ref('')
const diasLaborales = ref([]);
const totalConsumo = ref(0.0);
const selecciones = ref([]);
const valorCheck = ref(false)

// Función para formatear la fecha
const formatDate = (date) => {
    const d = new Date(date);
    let month = '' + (d.getMonth() + 1);
    let day = '' + d.getDate();
    const year = d.getFullYear();

    if (month.length < 2) month = '0' + month;
    if (day.length < 2) day = '0' + day;

    return [year, month, day].join('-');
};

// Establecer la fecha actual en el formato 'yyyy-mm-dd'
fechaActual.value = formatDate(new Date());

// Función para obtener los días laborales de la quincena actual
const obtenerDiasLaborales = (fecha) => {
    const fechaObj = new Date(fecha);
    const dia = fechaObj.getDate();
    const mes = fechaObj.getMonth();
    const año = fechaObj.getFullYear();

    // Determinar si la fecha actual está en la primera o segunda quincena del mes
    const inicioQuincena = dia <= 15 ? 1 : 16;
    const finQuincena = dia <= 15 ? 15 : new Date(año, mes + 1, 0).getDate();

    const dias = [];
    for (let i = inicioQuincena; i <= finQuincena; i++) {
        const fechaTemp = new Date(año, mes, i);
        const diaSemana = fechaTemp.getDay();
        if (diaSemana !== 0 && diaSemana !== 6) { // Excluir domingos (0) y sábados (6)
            dias.push(formatDate(fechaTemp));
        }
    }
    return dias;
};

// Actualizar días laborales basado en la fecha actual
diasLaborales.value = obtenerDiasLaborales(fechaActual.value);

// Inicializar selecciones con falsos
selecciones.value = new Array(diasLaborales.value.length).fill(false);

console.log(selecciones.value)

const contarSelecciones = () => {
    return selecciones.value.filter(seleccion => seleccion).length;
};

// Función para seleccionar o deseleccionar todas las fechas
const seleccionarTodas = (seleccionar) => {
    selecciones.value = selecciones.value.map(() => seleccionar);
    valorCheck.value = seleccionar

    if (!seleccionar) {
        totalConsumo.value = 0.0
        valorCheck.value = seleccionar
        document.querySelectorAll('input[data-group="comedor"]').forEach(radio =>{
            radio.checked = false;
        })
        return
    }
    enviarSelecciones();
};

// Actualizar selección y enviar al servidor
const actualizarSeleccion = (index) => {
    selecciones.value[index] = !selecciones.value[index];
    enviarSelecciones();
};

const enviarSelecciones = () => {
    console.log('Total de selecciones:', contarSelecciones());

    axios.post('/procesar-comedor', {
        selecciones: selecciones.value,
    })
    .then(response => {
        console.log('Datos procesados correctamente:', response.data);
        totalConsumo.value = response.data.total_consumo;
    })
    .catch(error => {
        console.error('Error al procesar los datos:', error);
    });
};
</script>

<template>
    <h2 class="text-center font-bold text-gray-700 mt-5 uppercase">Comedor</h2>

    <div class="max-w-screen-xl my-5 mx-auto flex flex-col md:flex-row gap-8"> 
        <div class="flex-1" v-if="diasLaborales.length > 0">
            <div class="mt-5 p-5 shadow-lg bg-white rounded-lg">
                <div class="flex flex-row gap-2">
                    <button @click="seleccionarTodas(true)" class="bg-blue-500 text-white hover:bg-blue-600 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 cursor-pointer mb-5">Seleccionar todas</button>
                    <button @click="seleccionarTodas(false)" class="bg-red-500 text-white hover:bg-red-600 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 cursor-pointer mb-5">Deseleccionar todas</button>
                </div>
                <table class="m-auto">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <th class="px-6 py-3">Dias Laborales</th>
                    <th class="px-6 py-3">Dias de Consumo</th>
                </thead>
                <tbody v-for="(diaLaboral,index) in diasLaborales" :key="index">
                    <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                    <td class="text-center py-2 text-gray-700 text-sm">{{ diaLaboral }}</td>
                    <td class="text-center"><input type="radio" data-group="comedor" :name="'dias_comedor-'+index" :checked="valorCheck" @change="actualizarSeleccion(index)" value="C1"></td>
                    </tr>
                </tbody>
                </table>
            </div>
        </div>
        <div class="flex-1 mt-5 p-5 shadow-lg bg-white rounded-lg">
      <div class="relative overflow-x-auto">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400"> 
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="text-center px-6 py-3">
                       Dias de consumo
                    </th>
                    <th scope="col" class="text-center px-6 py-3">
                        Total
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center px-6 py-3">{{ contarSelecciones() }}</td>
                    <td class="text-center px-6 py-3">
                        {{ totalConsumo }}
                    </td>
                </tr>
            </tbody>
        </table>
      </div>
    </div>
    </div>
</template>
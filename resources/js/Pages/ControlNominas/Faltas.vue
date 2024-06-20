<script setup>
    import { ref, onMounted,onUnmounted, watch } from 'vue';
    import axios from 'axios';

    const props = defineProps({
        idUsuario: {
            type: Number,
            required: true,
        }
    });

    onMounted(() => {
        const updateFechas = () => {
            fechaAnterior.value = localStorage.getItem('fechaAnterior');
            fechaActual.value = localStorage.getItem('fechaActual');
        };

        window.addEventListener('storage', updateFechas);
        window.addEventListener('storageChange', updateFechas); // Escucha el evento personalizado

        // Llamada inicial para asegurarse de que el gráfico se dibuje al montar el componente
        if (fechaAnterior.value && fechaActual.value) {
            dibujarGrafico();
        }

        // Limpiar el event listener cuando el componente se desmonte
        onUnmounted(() => {
            window.removeEventListener('storage', updateFechas);
            window.removeEventListener('storageChange', updateFechas);
        });
    });

    const fechaAnterior = ref(localStorage.getItem('fechaAnterior'))
    const fechaActual = ref(localStorage.getItem('fechaActual'))
    const diasLaborales = ref([]);
    const descuentoFaltas = ref(0.0);
    const selecciones = ref([]);
    const totalSelecciones = ref(0);
    
    const dibujarGrafico = async () => {
        try {
            const response = await axios.post('/dibujar-grafico', {
                fechaAnterior: fechaAnterior.value,
                fechaActual: fechaActual.value
            });

            console.log('Respuesta completa del servidor:', response);

            if (response.data && response.data.dias) {
                diasLaborales.value = response.data.dias;
            } else {
                console.error('La respuesta del servidor no contiene el formato esperado.');
            }

        } catch (err) {
            console.error('Error al solicitar los datos del gráfico:', err);
        }
    };

    
    watch([() => fechaAnterior.value, () => fechaActual.value], ([newFechaAnterior, newFechaActual]) => {
        fechaAnterior.value = newFechaAnterior;
        fechaActual.value = newFechaActual;

        // Solo llamar a dibujarGrafico si ambas fechas están establecidas
        if (fechaAnterior.value && fechaActual.value) {
            dibujarGrafico();
        }
    }, { immediate: true });

    const contarSelecciones = () => {
        return selecciones.value.filter(seleccion => seleccion).length;
    };

    // Actualizar selección y enviar al servidor
    const actualizarSeleccion = (index) => {
        selecciones.value[index] = !selecciones.value[index];
        totalSelecciones.value = contarSelecciones();

        console.log(totalSelecciones.value)

        axios.post(route('procesarFaltas',{
            idUsuario: props.idUsuario,
            selecciones: totalSelecciones.value
        }))
        .then(response => {
            console.log('Datos procesados correctamente:', response.data);
            descuentoFaltas.value = response.data.descuento_faltas
        })
        .catch(error => {
            console.error('Error al procesar los datos:', error);
        });
    };

</script>

<template>
    <h2 class="text-center font-bold text-gray-700 mt-5 uppercase">Faltas</h2>

    <div class="max-w-screen-xl my-5 mx-auto flex flex-col md:flex-row gap-8"> 
        <div class="flex-1" v-if="diasLaborales.length > 0">
            <div class="mt-5 p-5 shadow-lg bg-white rounded-lg">
                <table class="m-auto">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <th class="px-6 py-3">Dias Laborales</th>
                    <th class="px-6 py-3">Dias de Consumo</th>
                </thead>
                <tbody v-for="(diaLaboral,index) in diasLaborales">
                    <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                    <td class="text-center py-2 text-gray-700 text-sm">{{ diaLaboral }}</td>
                    <td class="text-center"><input type="radio" :name="'dias_comedor-'+index" @change="actualizarSeleccion(index)" value="C1"></td>
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
                        Dias faltantes
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
                        {{ descuentoFaltas }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    </div>
    </div>
</template>



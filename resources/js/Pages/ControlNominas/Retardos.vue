<script setup>
  import { ref, onMounted, onUnmounted,watch } from 'vue';
  import axios from 'axios';

  const props = defineProps({
      idUsuario: {
          type: Number,
          required: true,
      }
  });

  const diasLaborales = ref([]);
  const retardosSeleccionados = ref({});
  const descuentoTotal = ref({});
  const contadorRetardos = ref([]);
  const subtotalRetardos = ref([]);
  const unaHora = ref(0.0);

  const fechaAnterior = ref(localStorage.getItem('fechaAnterior'))
  const fechaActual = ref(localStorage.getItem('fechaActual'))

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

  const actualizarRetardos = (dia, tipoRetardo) => {
    retardosSeleccionados.value[dia] = tipoRetardo;
    procesarRetardos();
  };

  const procesarRetardos = () => {
    const conteoRetardos = {
      R1: 0,
      R2: 0,
      R3: 0,
      F: 0
    };

    Object.values(retardosSeleccionados.value).forEach((tipoRetardo) => {
      if (tipoRetardo) {
        conteoRetardos[tipoRetardo]++;
      }
    });

    axios.post('/procesar-retardos', {
      conteoRetardos,
      id: props.idUsuario
    })
    .then(response => {
      console.log('Datos procesados correctamente:', response.data);
      descuentoTotal.value = response.data.descuento_total;
      contadorRetardos.value = response.data.retardos;
      unaHora.value = response.data.una_hora;
      subtotalRetardos.value = response.data.subtotales;
    })
    .catch(error => {
      console.error('Error al procesar los datos:', error);
    });
  };

    // Nueva función para restablecer las selecciones
  const restablecerSeleccion = () => {
      retardosSeleccionados.value = {};
      descuentoTotal.value = 0;
      contadorRetardos.value = [0, 0, 0, 0];
      subtotalRetardos.value = [0, 0, 0, 0];
      unaHora.value = 0.0; 

      document.querySelectorAll('input[data-group="retardos"]').forEach(radio =>{
        radio.checked = false;
      })
  };

</script>

<template>
  <h2 class="text-center font-bold text-gray-700 mt-5 uppercase">Retardos / Faltas</h2>

    <div class="max-w-screen-xl my-5 mx-auto flex flex-col md:flex-row gap-8">
      <div class="flex-1" v-if="diasLaborales.length > 0">
        <div class="flex flex-row gap-2 mb-5">
            <button @click="restablecerSeleccion" class="bg-red-500 text-white hover:bg-yellow-600 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-2.5 cursor-pointer">Restablecer</button>
        </div>
        <div class="mt-5 p-5 shadow-lg bg-white rounded-lg">
          <table class="m-auto">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
              <th class="px-6 py-3">Dias Laborales</th>
              <th class="px-6 py-3">R1</th>
              <th class="px-6 py-3">R2</th>
              <th class="px-6 py-3">R3</th>
              <th class="px-6 py-3">Falta</th>
            </thead>
            <template v-for="(diaLaboral,index) in diasLaborales">
              <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                <td class="text-center py-2 text-gray-700 text-sm">{{ diaLaboral }}</td>
                <td class="text-center"><input type="radio" data-group="retardos" :name="'retardos-'+index" ref="retardo" value="R1" @change="actualizarRetardos(diaLaboral, 'R1')"></td>
                <td class="text-center"><input type="radio" data-group="retardos" :name="'retardos-'+index" ref="retardo" value="R2" @change="actualizarRetardos(diaLaboral, 'R2')"></td>
                <td class="text-center"><input type="radio" data-group="retardos" :name="'retardos-'+index" ref="retardo" value="R3" @change="actualizarRetardos(diaLaboral, 'R3')"></td>
                <td class="text-center"><input type="radio" data-group="retardos" :name="'retardos-'+index" ref="retardo" value="F" @change="actualizarRetardos(diaLaboral, 'F')"></td>
              </tr>
            </template>
          </table>
        </div>
      </div>
      <div class="flex-1 mt-5 p-5 shadow-lg bg-white rounded-lg">
        <div class="relative overflow-x-auto">
          <p class="font-bold text-sm mb-5 text-left rtl:text-right text-gray-600 dark:text-gray-500 uppercase">Descuento por Hora: <span class="font-thin">{{ unaHora }}</span></p>
          <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400"> 
              <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                  <tr>
                      <th scope="col" class="px-6 py-3">
                          Retardos / faltas
                      </th>
                      <th scope="col" class="px-6 py-3">
                          NO. Retardos / Faltas
                      </th>
                      <th scope="col" class="px-6 py-3">
                          Subtotal
                      </th>
                  </tr>
              </thead>
              <tbody>
                  <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                      <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                          R1
                      </th>
                      <td class="px-6 py-4">
                        {{ contadorRetardos[0] }}
                      </td>
                      <td class="px-6 py-4">
                          {{ subtotalRetardos[0] }}
                      </td>
                  </tr>
                  <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                      <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                          R2
                      </th>
                      <td class="px-6 py-4">
                        {{ contadorRetardos[1] }}
                      </td>
                      <td class="px-6 py-4">
                        {{ subtotalRetardos[1] }}
                      </td>
                  </tr>
                  <tr class="bg-white dark:bg-gray-800">
                      <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                          R3
                      </th>
                      <td class="px-6 py-4">
                        {{ contadorRetardos[2] }}
                      </td>
                      <td class="px-6 py-4">
                        {{ subtotalRetardos[2] }}
                      </td>
                  </tr>
                  <tr class="bg-white dark:bg-gray-800">
                      <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                          F
                      </th>
                      <td class="px-6 py-4">
                        {{ contadorRetardos[3] }}
                      </td>
                      <td class="px-6 py-4">
                        {{ subtotalRetardos[3] }}
                      </td>
                  </tr>
                  <tr class="bg-white dark:bg-gray-800">
                    <th class="text-center uppercase px-6 py-4 font-bold text-gray-900 whitespace-nowrap dark:text-white" colspan="2">Descuento Retardos</th>
                    <td class="px-6 py-4" v-if="descuentoTotal > 0">{{ descuentoTotal }}</td>
                  </tr>
              </tbody>
          </table>
        </div>
      </div>
  </div>
</template>


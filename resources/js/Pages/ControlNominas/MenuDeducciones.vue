<script setup>
    import { ref, onMounted } from 'vue';
    import Comedor from './Comedor.vue';
    import Retardos from './Retardos.vue';

    const props = defineProps({
        usuario: {
            type: Object,
            required: true,
        }
    });

    const infoUsuario = ref(props.usuario);
    const visibleComponent = ref('retardos'); 

    const fechaAnterior = ref('')
    const fechaActual = ref('')

    const fechaAnteriorLs = ref(localStorage.getItem('fechaAnterior'))
    const fechaActualLs = ref(localStorage.getItem('fechaActual'))

    //Totales
    const totalRetardo = ref('')
    const totalComedor = ref('')

    const error = ref('')

    onMounted(() => {
        infoUsuario

        const updateFechas = () => {
            fechaAnteriorLs.value = localStorage.getItem('fechaAnterior');
            fechaActualLs.value = localStorage.getItem('fechaActual');
        };

        window.addEventListener('storage', updateFechas);
        window.addEventListener('storageChange', updateFechas);
    });

    const enviarFechas = async () => {
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

        error.value = ''; 

        localStorage.setItem('fechaAnterior', fechaAnterior.value);
        localStorage.setItem('fechaActual', fechaActual.value);

        window.dispatchEvent(new Event('storageChange'));
    };

</script>

<template>
    <div class="max-w-screen-2xl lg:max-w-screen-full mt-5 mx-auto flex flex-col md:flex-row gap-4">
        <main class="flex-1">
            <a href="/control-nominas"><button type="button" class="flex-1 mx-5 py-2.5 px-5 me-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Regresar</button></a>

            <div class="flex-1 mx-auto max-w-sm my-4 py-4 px-4 shadow-lg bg-white rounded-lg">
                <h2 class="text-sm mb-2 font-bold text-gray-700 text-center uppercase">Información Usuario</h2>
                <div v-if="infoUsuario">
                <p class="font-bold uppercase text-sm text-gray-700">NO. Empleado: <span class="font-thin">{{ infoUsuario.numero_empleado }}</span></p>
                <p class="font-bold uppercase text-sm text-gray-700">Nombre: <span class="font-thin">{{ infoUsuario.name + " " + infoUsuario.apellido_paterno + " " + infoUsuario.apellido_materno}}</span></p>
                <p class="font-bold uppercase text-sm text-gray-700">Salario Bruto: <span class="font-thin">{{ infoUsuario.salario_bruto }}</span></p>
                <p class="font-bold uppercase text-sm text-gray-700">Salario Imss <span class="font-thin">{{ infoUsuario.salario_imss }}</span></p>
                <p class="font-bold uppercase text-sm text-gray-700">Salario Diario: <span class="font-thin">{{ infoUsuario.salario_diario }}</span></p>
                </div>
            </div>

            <div class="max-w-lg mx-auto mt-5 p-5 shadow-lg bg-white rounded-lg">
                <h2 class="text-sm mb-5 font-bold text-gray-700 text-center uppercase">Ingresa la fecha de corte anterior y la fecha de corte actual</h2>

                <div v-if="error" class="text-red-500 text-center py-5">{{ error }}</div>

                <form @submit.prevent="enviarFechas" method="POST" class="space-y-4">
                <div class="flex flex-col md:flex-row md:items-center space-y-2 md:space-y-0 md:space-x-4">
                    <label for="fechaAnterior" class="md:w-1/3 text-gray-700 text-sm font-medium uppercase">Fecha Corte Anterior</label>
                    <input
                    class="flex-1 p-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-500 focus:border-blue-500"
                    type="date"
                    id="fechaAnterior"
                    v-model="fechaAnterior"
                    />
                </div>
                <div class="flex flex-col md:flex-row md:items-center space-y-2 md:space-y-0 md:space-x-4">
                    <label for="fechaActual" class="md:w-1/3 text-gray-700 text-sm font-medium uppercase">Fecha Corte Actual</label>
                    <input
                    class="flex-1 p-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-500 focus:border-blue-500"
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

            <div class="flex-1 mx-auto max-w-sm my-4 py-4 px-4 shadow-lg bg-white rounded-lg">
                <h2 class="text-sm mb-5 font-bold text-gray-700 text-center uppercase">Fechas seleccionadas</h2>
                <p class="font-bold">Fecha Anterior: <span class="font-thin">{{ fechaAnteriorLs }}</span></p>
                <p class="font-bold">Fecha Actual: <span class="font-thin">{{ fechaActualLs }}</span></p>
            </div>
        </main>

        <div class="flex-1 mx-auto">
            <div>
                <Retardos 
                    v-show="visibleComponent === 'retardos'" 
                    :idUsuario="infoUsuario.id"
                />
                
                <Comedor 
                    v-show="visibleComponent === 'comedor'"
                />
            </div>
        </div>
        <aside class="w-80 flex-shrink-0 bg-gray-100 p-5 shadow-lg rounded-lg">
            <h2 class="text-lg font-bold text-gray-700 text-center uppercase mb-5">Total Deducciones</h2>
            <div class="space-y-4">
                <div class="p-4 bg-white shadow rounded-lg">
                    <p>
                        Total retardos: <span>0.00</span>
                    </p>
                </div>

                <div class="p-4 bg-white shadow rounded-lg">
                    <p>Total Comedor: <span>0.00</span></p>
                </div>

                <div class="p-4 bg-white shadow rounded-lg">
                    <p>Total Faltas: <span>0.00</span></p>
                </div>

                <div class="p-4 mt-10 bg-white shadow rounded-lg">
                    <p>Total: <span>0.00</span></p>
                </div>
            </div>
        </aside>
    </div>
</template>
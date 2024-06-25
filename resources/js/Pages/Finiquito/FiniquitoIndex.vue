<template>
    <div class="max-w-lg p-6 mx-auto mt-20 bg-gray-100 rounded-lg shadow-md">
        <h1 class="mb-6 text-2xl font-bold text-center">
            Calculadora Finiquito
        </h1>
        <form @submit.prevent="buscarUsuario" class="flex gap-2 mb-6">
            <input v-model="fecha" type="date" />
            <input
                v-model="query"
                type="text"
                placeholder="Nombre o ID del usuario"
                class="flex-1 p-2 border border-gray-300 rounded-l-lg focus:outline-none"
            />
            <button
                type="submit"
                class="px-4 py-2 text-white bg-blue-500 rounded-r-lg hover:bg-blue-600 focus:outline-none"
            >
                Buscar
            </button>
        </form>

        <div v-if="$page.props.usuario">
            <h2 class="font-bold">Informacion Usuario</h2>
            <h2>Usuario: {{ $page.props.usuario.name }}</h2>
            <p>Salario Bruto: {{ $page.props.usuario.salario_bruto }}</p>
            <P>Salario Imss: {{ $page.props.usuario.salario_imss }}</P>
            <p>Salario Diario: {{ $page.props.usuario.salario_diario }}</p>
            <p>Fondo Ahorro: {{ $page.props.usuario.fondo_ahorro }}</p>
            <p>Fecha Ingreso: {{ $page.props.usuario.fecha_ingreso_real }}</p>
        </div>

        <hr class="m-5" />

        <div v-if="$page.props.finiquito">
            <h2 class="font-bold">Finiquito Calculado</h2>
            <p class="font-bold text-red-500">
                Fecha Baja: <span>{{ $page.props.finiquito.fecha_baja }}</span>
            </p>
            <p>Vacaciones: {{ $page.props.finiquito.dias_vacaciones }}</p>
            <hr class="m-3" />
            <p>
                Aguinaldo Proporcional:
                {{ $page.props.finiquito.aguinaldo_proporcional }}
            </p>
            <p>Isr Aguinaldo: {{ $page.props.finiquito.isr_aguinaldo }}</p>
            <p class="font-bold">
                Aguinaldo Neto: {{ $page.props.finiquito.aguinaldo_neto }}
            </p>
            <hr class="m-3" />
            <!-- <p>Vacaciones No Tomadas: {{ $page.props.finiquito.vacaciones_no_tomadas }}</p> -->
            <p>Meses Trabajados: {{ $page.props.finiquito.meses_restantes }}</p>
            <p>
                Periodo Vacaciones:
                {{ $page.props.finiquito.periodo_vacaciones }}
            </p>
            <p class="font-bold">
                Vacaciones: {{ $page.props.finiquito.vacaciones }}
            </p>
            <hr class="m-3" />
            <p>
                Prima Vacacional: {{ $page.props.finiquito.prima_vacacional }}
            </p>
            <p>Isr prima: {{ $page.props.finiquito.isr_prima }}</p>
            <p class="font-bold">
                Prima Vacacional Neta:
                {{ $page.props.finiquito.prima_vacacional_neta }}
            </p>
            <hr class="m-3" />
            <p class="font-bold">
                Fondo de Ahorro: {{ $page.props.finiquito.fondo_ahorro }}
            </p>
            <hr class="m-3" />
            <p class="font-bold">Total: {{ $page.props.finiquito.total }}</p>
        </div>
    </div>
</template>

<script>
import { ref, onMounted } from "vue";
import { Inertia } from "@inertiajs/inertia";
import axios from "axios";

export default {
    setup() {
        const query = ref("");
        const fecha = ref("");
        const usuario = ref(null);
        const finiquito = ref(null);

        // Inicializar los valores reactivos
        const resetValues = () => {
            query.value = "";
            fecha.value = "";
            usuario.value = null;
            finiquito.value = null;
        };

        window.addEventListener("onLoad", () => {
            resetValues();
            // sessionStorage.setItem('isReloading', 'true');
        });

        const buscarUsuario = () => {
            // Validar campos antes de realizar la búsqueda
            if (!fecha.value || !query.value) {
                alert("Por favor complete todos los campos obligatorios.");
                return;
            }

            Inertia.get(
                "/finiquito",
                { query: query.value, fecha: fecha.value },
                {
                    onSuccess: (page) => {
                        console.log("Usuario encontrado:", page.props.usuario);
                        usuario.value = page.props.usuario;
                        finiquito.value = page.props.finiquito;
                    },
                }
            );
        };

        // , { usuario_id: usuario.value.id }
        const calcularFiniquito = () => {
            Inertia.post(
                "/finiquitocontroller",
                { usuario_id: usuario.value.id },
                {
                    onSuccess: (page) => {
                        console.log(
                            "Finiquito del usuario: ",
                            page.props.finiquito
                        );
                        finiquito.value = page.props.finiquito;
                    },
                }
            );
        };

        return {
            fecha,
            query,
            usuario,
            finiquito,
            buscarUsuario,
            calcularFiniquito,
        };
    },
};
</script>

<script setup>
import { watchEffect, ref, reactive, onMounted } from "vue";
import CardCi from "../Partials/CardCi.vue";
import TablaBajas from "./Partials/TablaBajas.vue";
import GraficaBarrasCXP from "../CXP/Partials/GraficaBarrasCXP.vue";
import RiesgoRadarNomina from "../Nominas/Partials/RiesgoRadarNomina.vue";
import GraficaPorcentaje from "../Partials/GraficaPorcentaje.vue";
import ModalAddBajas from "./Modals/ModalAddBajas.vue";
import Titulos from "../Partials/Titulos.vue";
import GraficaLineas from "../Partials/GraficaLineas.vue";
import DatePicker from "@/Components/DatePicker.vue";

const props = defineProps({
    show: {
        type: Object,
        default: false,
    },
});

const tabla = reactive({
    atributos: [],
    parametros: [],
    data: [],
});

const graphBar = reactive({});
const graphLine = ref({});
const graphPorcentaje = ref(0);
const riesgoRadar = ref(0);
const modalAddAlta = ref(false);
const position = ref();
const date = ref(null);

watchEffect(() => (props.show ? getData() : null));
function getData() {
    axios
        .get(route("dataBajas", { date: date.value }))
        .then(({ data }) => {
            tabla.atributos = data.atributos;
            tabla.parametros = data.parametros;
            tabla.data = data.data;
            if (data.data.length !== 0)
                Object.values(data.data).forEach((element) => {
                    switch (element[0].atributo) {
                        case 27:
                            graphBar.Nombre = {
                                value: element[0].value,
                                name: "Formato de Baja",
                            };
                            break;
                        case 28:
                            graphBar.NSS = {
                                value: element[0].value,
                                name: "Autorizacion de Baja",
                            };
                            break;
                        case 29:
                            graphBar.RFC = {
                                value: element[0].value,
                                name: "Datos del Formato de Baja",
                            };
                            break;
                        case 30:
                            graphBar.Ingreso = {
                                value: element[0].value,
                                name: "Correo de solicitud de Baja",
                            };
                            break;
                        case 31:
                            graphBar.d = {
                                value: element[0].value,
                                name: "Baja de IDSE",
                            };
                            break;
                        case 32:
                            graphBar.e = {
                                value: element[0].value,
                                name: "Carta de Renuncia",
                            };
                            break;
                        case 33:
                            graphBar.f = {
                                value: element[0].value,
                                name: "Baja de INTRANET",
                            };
                            break;
                        case 34:
                            graphBar.g = {
                                value: element[0].value,
                                name: "Formato de Baja P&G",
                            };
                            break;
                        case 35:
                            graphBar.h = {
                                value: element[0].value,
                                name: "Correo para solicitud de baja P&G",
                            };
                            break;
                    }
                });
            else {
                graphBar.Nombre = {
                    value: 0,
                    name: "Formato de Baja",
                };
                graphBar.NSS = {
                    value: 0,
                    name: "Autorizacion de Baja",
                };
                graphBar.RFC = {
                    value: 0,
                    name: "Datos del Formato de Baja",
                };
                graphBar.Ingreso = {
                    value: 0,
                    name: "Correo de solicitud de Baja",
                };
                graphBar.d = {
                    value: 0,
                    name: "Baja de IDSE",
                };
                graphBar.e = {
                    value: 0,
                    name: "Carta de Renuncia",
                };
                graphBar.f = {
                    value: 0,
                    name: "Baja de INTRANET",
                };
                graphBar.g = {
                    value: 0,
                    name: "Formato de Baja P&G",
                };
                graphBar.h = {
                    value: 0,
                    name: "Correo para solicitud de baja P&G",
                };
            }
            graphPorcentaje.value = data.dataPorcentaje;
            graphLine.value = data.garphLine;
            riesgoRadar.value = data.dataRadar;
            date.value = data.paramsFecha;
        })
        .catch((err) => console.log(err.response ?? err));
}
onMounted(() =>
    Echo.channel("dataBajas").listen("SuaEvent", () =>
        props.show ? getData() : null
    )
);
</script>
<template>
    <CardCi>
        <div class="w-fit">
            <DatePicker
                label="Mes visualizado"
                :minDate="null"
                :dates="date"
                @selectDate="
                    (e) => {
                        date = e;
                        getData();
                    }
                "
            />
        </div>
        <div class="flex items-center justify-between gap-2">
            <div class="grid w-7/12">
                <div class="flex justify-between">
                    <GraficaPorcentaje :porcentaje="graphPorcentaje" />
                    <RiesgoRadarNomina :riesgo="riesgoRadar" />
                </div>
                <div class="grid w-full justify-items-center">
                    <Titulos
                        value="Rango porcentual de cumplimiento"
                        class="text-[20px] w-fit"
                    />
                    <GraficaBarrasCXP :data="graphBar" />
                </div>
            </div>
            <div class="grid w-5/12">
                <div class="grid w-full justify-items-center">
                    <Titulos value="Promedio" class="text-[16px] w-fit" />
                    <GraficaLineas :data="graphLine" />
                </div>
                <TablaBajas
                    :data="tabla"
                    @add="
                        (e) => {
                            position = e;
                            modalAddAlta = true;
                        }
                    "
                />
            </div>
        </div>
    </CardCi>
    <ModalAddBajas
        :show="modalAddAlta"
        @close="modalAddAlta = false"
        :position="position"
        maxWidth="6xl"
    />
</template>

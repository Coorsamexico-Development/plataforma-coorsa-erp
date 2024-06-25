<script setup>
import { watchEffect, ref, reactive, onMounted } from "vue";
import CardCi from "../Partials/CardCi.vue";
import TablaAltas from "./Partials/TablaAltas.vue";
import GraficaBarrasCXP from "../CXP/Partials/GraficaBarrasCXP.vue";
import RiesgoRadarNomina from "../Nominas/Partials/RiesgoRadarNomina.vue";
import GraficaPorcentaje from "../Partials/GraficaPorcentaje.vue";
import ModalAddAltas from "./Modals/ModalAddAltas.vue";
import Titulos from "../Partials/Titulos.vue";
import GraficaLineas from "../Partials/GraficaLineas.vue";

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

watchEffect(() => (props.show ? getData() : null));
function getData() {
    axios
        .get(route("dataAltas"))
        .then(({ data }) => {
            tabla.atributos = data.atributos;
            tabla.parametros = data.parametros;
            tabla.data = data.data;
            Object.values(data.data).forEach((element) => {
                switch (element[0].atributo) {
                    case 9:
                        graphBar.Nombre = {
                            value: element[0].value,
                            name: "RFC",
                        };
                        break;
                    case 19:
                        graphBar.NSS = {
                            value: element[0].value,
                            name: "CURP",
                        };
                        break;
                    case 7:
                        graphBar.RFC = {
                            value: element[0].value,
                            name: "Nombre",
                        };
                        break;
                    case 20:
                        graphBar.Ingreso = {
                            value: element[0].value,
                            name: "Formato de Alta",
                        };
                        break;
                    case 21:
                        graphBar.d = {
                            value: element[0].value,
                            name: "Enviado en tiempo y forma",
                        };
                        break;
                    case 22:
                        graphBar.e = {
                            value: element[0].value,
                            name: "Registro en plataforma RH",
                        };
                        break;
                    case 23:
                        graphBar.f = {
                            value: element[0].value,
                            name: "Documentacion correspondiente",
                        };
                        break;
                    case 24:
                        graphBar.g = {
                            value: element[0].value,
                            name: "Clasificacion de riesgo",
                        };
                        break;
                    case 25:
                        graphBar.h = {
                            value: element[0].value,
                            name: "Formato Autorizado",
                        };
                        break;
                    case 26:
                        graphBar.i = {
                            value: element[0].value,
                            name: "CFDI",
                        };
                        break;
                }
            });
            graphPorcentaje.value = data.dataPorcentaje;
            graphLine.value = data.garphLine;
            riesgoRadar.value = data.dataRadar;
        })
        .catch((err) => console.log(err.response ?? err));
}
onMounted(() =>
    Echo.channel("dataAltas").listen("SuaEvent", () =>
        props.show ? getData() : null
    )
);
</script>
<template>
    <CardCi>
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
                <TablaAltas
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
    <ModalAddAltas
        :show="modalAddAlta"
        @close="modalAddAlta = false"
        :position="position"
        maxWidth="6xl"
    />
</template>

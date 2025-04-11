<script setup>
import { watchEffect, ref, reactive, onMounted } from "vue";
import CardCi from "../Partials/CardCi.vue";
import TablaNomina from "./Partials/TablaNomina.vue";
import Titulos from "../Partials/Titulos.vue";
import ModalAddNomina from "./Modals/ModalAddNomina.vue";
import GraficaBarrasNomina from "./Partials/GraficaBarrasNomina.vue";
import GraficaLineasNomina from "./Partials/GraficaLineasNomina.vue";
import RiesgoRadarNomina from "./Partials/RiesgoRadarNomina.vue";
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
const riesgoRadar = ref(0);
const date = ref(null);

const modalAddNomina = ref(false);
const position = ref();

watchEffect(() => (props.show ? getData() : null));

function getData() {
    axios
        .get(route("dataNomina", { date: date.value }))
        .then(({ data }) => {
            tabla.atributos = data.atributos;
            tabla.parametros = data.parametros;
            tabla.data = data.data;
            if (data.data.length != 0)
                Object.values(data.data).forEach((element) => {
                    switch (element[0].atributo) {
                        case 7:
                            graphBar.Nombre = element[0].value;
                            break;
                        case 8:
                            graphBar.NSS = element[0].value;
                            break;
                        case 9:
                            graphBar.RFC = element[0].value;
                            break;
                        case 10:
                            graphBar.Ingreso = element[0].value;
                            break;
                        case 11:
                            graphBar.Puesto = element[0].value;
                            break;
                        case 12:
                            graphBar.Infonavit = element[0].value;
                            break;
                        case 13:
                            graphBar.Fonacot = element[0].value;
                            break;
                        case 14:
                            graphBar.Bancos = element[0].value;
                            break;
                    }
                });
            else {
                graphBar.Nombre = 0;
                graphBar.NSS = 0;
                graphBar.RFC = 0;
                graphBar.Ingreso = 0;
                graphBar.Puesto = 0;
                graphBar.Infonavit = 0;
                graphBar.Fonacot = 0;
                graphBar.Bancos = 0;
            }
            date.value = data.paramsFecha;
            graphLine.value = data.garphLine;
            riesgoRadar.value = data.dataRadar;
        })
        .catch((err) => console.log(err.response ?? err));
}

onMounted(() =>
    Echo.channel("NominasData").listen("SuaEvent", () =>
        props.show ? getData() : null
    )
);
</script>
<template>
    <CardCi>
        <div class="grid">
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
            <div class="flex items-center justify-between">
                <div class="grid w-full justify-items-center">
                    <Titulos value="Promedio" class="text-[20px]" />
                    <GraficaLineasNomina :data="graphLine" />
                </div>
                <div class="grid w-full justify-items-center">
                    <Titulos value="Riesgo Inherente" class="text-[20px]" />
                    <RiesgoRadarNomina :riesgo="riesgoRadar" />
                </div>
            </div>
            <div class="flex items-center justify-between">
                <div class="grid w-full justify-items-center">
                    <Titulos
                        value="Rango Porcentual de Cumplimiento"
                        class="text-[20px]"
                    />
                    <GraficaBarrasNomina :data="graphBar" />
                </div>
                <div class="grid w-full justify-items-center">
                    <TablaNomina
                        :data="tabla"
                        @add="
                            (e) => {
                                position = e;
                                modalAddNomina = true;
                            }
                        "
                    />
                </div>
            </div>
        </div>
    </CardCi>

    <ModalAddNomina
        :show="modalAddNomina"
        @close="modalAddNomina = false"
        :position="position"
        maxWidth="4xl"
    />
</template>

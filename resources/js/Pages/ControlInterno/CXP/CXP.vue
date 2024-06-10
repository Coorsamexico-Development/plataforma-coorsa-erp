<script setup>
import { watchEffect, ref, reactive, onMounted } from "vue";
import CardCi from "../Partials/CardCi.vue";
import Titulos from "../Partials/Titulos.vue";
import TablaCxp from "./Partials/TablaCxp.vue";
import ModalAddCXP from "./Modals/ModalAddCXP.vue";
import GraficaLineasNomina from "../Nominas/Partials/GraficaLineasNomina.vue";
import RiesgoRadarNomina from "../Nominas/Partials/RiesgoRadarNomina.vue";
import GraficaBarrasCXP from "./Partials/GraficaBarrasCXP.vue";

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
const modalAddCXP = ref(false);
const position = ref();

watchEffect(() => (props.show ? getData() : null));

function getData() {
    axios
        .get(route("dataCXP"))
        .then(({ data }) => {
            tabla.atributos = data.atributos;
            tabla.parametros = data.parametros;
            tabla.data = data.data;
            Object.values(data.data).forEach((element) => {
                switch (element[0].atributo) {
                    case 15:
                        graphBar.Nombre = {
                            value: element[0].value,
                            name: "Solicitud autorizasdas con fecha de de programacion de pago",
                        };
                        break;
                    case 16:
                        graphBar.NSS = {
                            value: element[0].value,
                            name: "Fecha Solicitud vs Fecha Programacion",
                        };
                        break;
                    case 17:
                        graphBar.RFC = {
                            value: element[0].value,
                            name: "Fecha Programacion vs Fecha Pago",
                        };
                        break;
                    case 18:
                        graphBar.Ingreso = {
                            value: element[0].value,
                            name: "Pagos Autorizado vs Estado de cuenta",
                        };
                        break;
                }
            });
            graphLine.value = data.garphLine;
            riesgoRadar.value = data.dataRadar;
        })
        .catch((err) => console.log(err.response ?? err));
}
onMounted(() =>
    Echo.channel("dataCXP").listen("SuaEvent", () =>
        props.show ? getData() : null
    )
);
</script>
<template>
    <CardCi>
        <div class="grid content-between">
            <div class="flex items-center justify-between">
                <div class="grid w-full justify-items-center">
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
                        value="Rango porcentual de cumplimiento "
                        class="text-[20px]"
                    />
                    <TablaCxp
                        :data="tabla"
                        @add="
                            (e) => {
                                position = e;
                                modalAddCXP = true;
                            }
                        "
                    />
                </div>
                <div class="grid w-full justify-items-center">
                    <GraficaBarrasCXP :data="graphBar" />
                </div>
            </div>
        </div>

        <ModalAddCXP
            :show="modalAddCXP"
            @close="modalAddCXP = false"
            :position="position"
            maxWidth="3xl"
        />
    </CardCi>
</template>

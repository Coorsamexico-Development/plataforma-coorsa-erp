<script setup>
import { watchEffect, ref, reactive, onMounted } from "vue";
import CardCi from "../Partials/CardCi.vue";
import Titulos from "../Partials/Titulos.vue";
import TablaCxp from "./Partials/TablaCxp.vue";

const props = defineProps({
    show: {
        type: Object,
        default: false,
    },
});

const tabla = reactive({
    atributos: [],
    parametros: [],
    value: [],
});
</script>
<template>
    <CardCi>
        <CardCi>
            <div class="grid">
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
                                    modalAddNomina = true;
                                }
                            "
                        />
                    </div>
                    <div class="grid w-full justify-items-center">
                        <GraficaBarrasNomina :data="graphBar" />
                    </div>
                </div>
            </div>
        </CardCi>
    </CardCi>
</template>

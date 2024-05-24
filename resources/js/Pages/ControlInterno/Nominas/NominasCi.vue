<script setup>
import { watchEffect, ref, reactive, onMounted } from "vue";
import CardCi from "../Partials/CardCi.vue";
import TablaNomina from "./Partials/TablaNomina.vue";
import Titulos from "../Partials/Titulos.vue";
import ModalAddNomina from "./Modals/ModalAddNomina.vue";

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

const modalAddNomina = ref(false);
const position = ref();

watchEffect(() => (props.show ? getData() : null));

function getData() {
    axios
        .get(route("dataNomina"))
        .then(({ data }) => {
            tabla.atributos = data.atributos;
            tabla.parametros = data.parametros;
        })
        .catch((err) => console.log(err.response ?? err));
}
</script>
<template>
    <CardCi>
        <div class="grid">
            <div class="flex items-center justify-between">
                <div class="grid w-full justify-items-center">
                    <Titulos value="Promedio" class="text-[28px]" />
                </div>
            </div>
            <div class="flex items-center justify-between">
                <div class="grid w-full justify-items-center">
                    <Titulos
                        value="Rango Porcentual de Cumplimiento"
                        class="text-[20px]"
                    />
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

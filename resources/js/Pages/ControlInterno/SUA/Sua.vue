<script setup>
import { watchEffect, ref, reactive, onMounted } from "vue";
import CardCi from "../Partials/CardCi.vue";
import GraficaSua from "./Partials/GraficaSua.vue";
import TablaSua from "./Partials/TablaSua.vue";
import Add from "@/Iconos/Add.vue";
import ModalEvolucionImss from "./Modals/ModalEvolucionImss.vue";
import ModalEvolucionColab from "./Modals/ModalEvolucionColab.vue";
import Titulos from "../Partials/Titulos.vue";

const props = defineProps({
    show: {
        type: Object,
        default: false,
    },
});

watchEffect(() => (props.show ? getData() : null));

const T1 = reactive({ añoMeses: 0, atributos: 0, dataTable: 0 });
const T2 = reactive({ añoMeses: 0, atributos: 0, dataTable: 0 });
const modalImssShow = ref(false);
const modalColabShow = ref(false);
const position = ref();

function getData() {
    axios
        .get(route("dataSua"))
        .then(({ data }) => {
            T1.añoMeses = data.añoMeses;
            T1.atributos = data.atributos;
            T1.dataTable = data.data;
            T2.añoMeses = data.añoMesesT2;
            T2.atributos = data.atributosT2;
            T2.dataTable = data.dataT2;
        })
        .catch((err) => console.log(err.response ?? err));
}
onMounted(() =>
    Echo.channel("EvolucionImss").listen("SuaEvent", () =>
        props.show ? getData() : null
    )
);
</script>
<template>
    <CardCi>
        <div class="grid content-center gap-1 justify-items-center">
            <Titulos
                value="Evolucion de colaboradores inactivos vs imss"
                class="text-[28px]"
            />
            <div class="flex items-center w-full h-fit">
                <TablaSua :tabla="T1" titulo="Evolucion" />
                <button
                    class="w-[4rem] h-[4rem] transition-all duration-200 -rotate-90 active:scale-90 -ml-4"
                    @click="
                        position = $event.target.getBoundingClientRect();
                        modalImssShow = true;
                    "
                >
                    <Add />
                </button>
            </div>
        </div>
        <div class="flex items-center gap-5">
            <div class="grid w-5/12 gap-1 justify-items-center">
                <Titulos
                    value="Evolucion de colaboradores inactivos vs imss"
                    class="text-[20px]"
                />
                <div class="flex items-center w-full overflow-hidden">
                    <TablaSua :tabla="T2" />
                    <button
                        class="w-[4rem] h-[4rem] transition-all duration-200 -rotate-90 active:scale-90 -ml-3"
                        @click="
                            position = $event.target.getBoundingClientRect();
                            modalColabShow = true;
                        "
                    >
                        <Add />
                    </button>
                </div>
            </div>
            <div class="grid w-6/12 gap-1 justify-items-center">
                <Titulos
                    value="Evolucion de colaboradores inactivos vs imss"
                    class="text-[20px]"
                />
                <GraficaSua :data="T2.dataTable" class="" />
            </div>
        </div>
        <div class="flex items-end justify-center">
            <span class="font-semibold">
                El porcentaje del monto que sobre pasa la cantidad que se debe
                cubrir mensualmente debe ser por debajo del 8%
            </span>
        </div>
    </CardCi>
    <ModalEvolucionImss
        :show="modalImssShow"
        @close="modalImssShow = false"
        :position="position"
        maxWidth="lg"
    />
    <ModalEvolucionColab
        :show="modalColabShow"
        @close="modalColabShow = false"
        :position="position"
        maxWidth="lg"
    />
</template>

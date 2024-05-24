<script setup>
import { watchEffect, ref, reactive, onMounted } from "vue";
import CardCi from "../Partials/CardCi.vue";
import GraficaSua from "./Partials/GraficaSua.vue";
import TablaSua from "./Partials/TablaSua.vue";
import Add from "@/Iconos/Add.vue";
import ModalEvolucionImss from "./Modals/ModalEvolucionImss.vue";
import ModalEvolucionColab from "./Modals/ModalEvolucionColab.vue";

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
/* onMounted(
    Echo.channel("EvolucionImss").listen("SuaEvent", function (data) {
        getData();
    })
); */
</script>
<template>
    <CardCi>
        <div class="flex items-center gap-1 h-fit">
            <TablaSua :tabla="T1" />
            <button
                class="w-10 h-10 transition-all duration-200 -rotate-90 active:scale-90"
                @click="
                    position = $event.target.getBoundingClientRect();
                    modalImssShow = true;
                "
            >
                <Add />
            </button>
        </div>
        <div class="flex items-center justify-between gap-10">
            <div class="flex items-center w-1/2 overflow-hidden">
                <TablaSua :tabla="T2" />
                <button
                    class="w-10 h-10 transition-all duration-200 -rotate-90 active:scale-90"
                    @click="
                        position = $event.target.getBoundingClientRect();
                        modalColabShow = true;
                    "
                >
                    <Add />
                </button>
            </div>
            <GraficaSua :data="T2.dataTable" />
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

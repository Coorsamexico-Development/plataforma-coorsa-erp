<script setup>
import { onMounted, reactive, ref } from "vue";
import { months } from "../../utils/index.js";
import AppLayout from "@/Layouts/AppLayout.vue";
import GraphBar from "./Partials/GraphBar.vue";
import GraphBarCategory from "./Partials/GraphBarCategory.vue";
import FormModal from "./Modals/FormModal.vue";
import DatePicker from "@/Components/DatePicker.vue";
import moment from "moment";
import FormComportamiento from "./Modals/FormComportamiento.vue";

const position = ref(null);
const show = ref(false);
const table = ref(null);
const rutaAdd = ref(null);
const channel = ref(null);

const date = reactive({
    month: moment().format("MM"),
    year: moment().format("YYYY"),
    full: moment().format("MM-YYYY"),
});

function showModalSitio(evt, tableId, add, chanel) {
    position.value = evt.target.getBoundingClientRect();
    show.value = true;
    table.value = tableId;
    rutaAdd.value = add;
    channel.value = chanel;
}

function selectDate(evt) {
    date.full = evt;
    date.month = evt.split("-")[0];
    date.year = evt.split("-")[1];
}

onMounted(() => {
    console.log("mounted");
    const interval = setInterval(() => {
        countTable();
        if (countTables.value > 8) clearInterval(interval);
    }, 1);
});

const countTables = ref(0);
function countTable() {
    countTables.value++;
    console.log(countTables.value);
}
</script>
<template>
    <AppLayout title="SHE">
        <div class="grid gap-2 p-4">
            <div class="grid gap-2 lg:grid-cols-2 xl:grid-cols-4">
                <GraphBar
                    ruta="getTableCDUQ"
                    title="CDU Quintas"
                    table="1"
                    @addClick="
                        (e) => {
                            showModalSitio(
                                e,
                                1,
                                'addSeriesSitios',
                                'graphCDUQ'
                            );
                        }
                    "
                    channel="graphCDUQ"
                    v-if="countTables > 0"
                />
                <GraphBar
                    ruta="getTableCDUQ"
                    title="CDU Operaciones"
                    table="2"
                    @addClick="
                        (e) => {
                            showModalSitio(
                                e,
                                2,
                                'addSeriesSitios',
                                'graphCDUO'
                            );
                        }
                    "
                    channel="graphCDUO"
                    v-if="countTables > 1"
                />
                <GraphBar
                    ruta="getTableCDUQ"
                    title="WLM GDL"
                    table="3"
                    @addClick="
                        (e) => {
                            showModalSitio(e, 3, 'addSeriesSitios', 'graphWML');
                        }
                    "
                    channel="graphWML"
                    v-if="countTables > 2"
                />
                <GraphBar
                    ruta="getTableCDUQ"
                    title="Procter"
                    table="4"
                    @addClick="
                        (e) => {
                            showModalSitio(
                                e,
                                4,
                                'addSeriesSitios',
                                'graphPorcter'
                            );
                        }
                    "
                    channel="graphPorcter"
                    v-if="countTables > 3"
                />
            </div>
            <div class="grid gap-2 lg:grid-cols-2">
                <GraphBar
                    ruta="getTableCDUQ"
                    title="Capacitacion"
                    table="5"
                    @addClick="
                        (e) => {
                            showModalSitio(
                                e,
                                5,
                                'addCapacitacion',
                                'graphCapa'
                            );
                        }
                    "
                    channel="graphCapa"
                    v-if="countTables > 4"
                />
                <GraphBar
                    ruta="getTableCDUQ"
                    title="Yard Safety"
                    table="6"
                    @addClick="
                        (e) => {
                            showModalSitio(e, 6, 'addSeafty', 'graphSeafty');
                        }
                    "
                    channel="graphSeafty"
                    v-if="countTables > 5"
                />
            </div>
            <div class="grid gap-2 lg:grid-cols-2 xl:grid-cols-3">
                <div
                    class="col-start-2 place-self-center text-[1.5rem] font-semibold uppercase"
                >
                    <span
                        >Comportamiento
                        <span class="font-bold">{{
                            months[date.month - 1]
                        }}</span>
                    </span>
                </div>
                <div class="self-center col-start-3 justify-self-end">
                    <DatePicker
                        label="Mes visualizado"
                        v-model="date.full"
                        :maxDate="null"
                        @selectDate="(e) => selectDate(e)"
                        modelType="MM-yyyy"
                    />
                </div>
                <GraphBarCategory
                    :filters="date"
                    table="7"
                    title="Acto Inseguro WLM GDL"
                    @addClick="
                        (e) => {
                            showModalSitio(e, 7, 'addActoIns', 'graphInsGDL');
                        }
                    "
                    channel="graphInsGDL"
                    v-if="countTables > 6"
                />
                <GraphBarCategory
                    :filters="date"
                    table="8"
                    title="Acto Inseguro Procter"
                    @addClick="
                        (e) => {
                            showModalSitio(
                                e,
                                8,
                                'addActoIns',
                                'graphInsProcter'
                            );
                        }
                    "
                    channel="graphInsProcter"
                    v-if="countTables > 7"
                />
                <GraphBarCategory
                    :filters="date"
                    table="9"
                    title="Acto Inseguro CDU"
                    @addClick="
                        (e) => {
                            showModalSitio(e, 9, 'addActoIns', 'graphInsCdu');
                        }
                    "
                    channel="graphInsCdu"
                    v-if="countTables > 8"
                />
            </div>
        </div>
        <FormModal
            :show="show"
            :position="position"
            @close="show = false"
            maxWidth="xl"
            :table="table"
            :rutaAdd="rutaAdd"
            :channel="channel"
            v-if="[1, 2, 3, 4, 5, 6].includes(table)"
        />

        <FormComportamiento
            :show="show"
            :position="position"
            @close="show = false"
            maxWidth="xl"
            :table="table"
            :rutaAdd="rutaAdd"
            :channel="channel"
            v-if="[7, 8, 9].includes(table)"
            :date="date"
        />
    </AppLayout>
</template>

<script setup>
import { reactive, ref } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import GraphBar from "./Partials/GraphBar.vue";
import GraphLine from "./Partials/GraphLine.vue";
import FormSitios from "./Modals/FormSitios.vue";
import FormModal from "./Modals/FormModal.vue";

const position = ref(null);
const show = ref(false);
const table = ref(null);
const rutaAdd = ref(null);
const channel = ref(null);

function showModalSitio(evt, tableId, add, chanel) {
    position.value = evt.target.getBoundingClientRect();
    show.value = true;
    table.value = tableId;
    rutaAdd.value = add;
    channel.value = chanel;
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
                />
                <GraphBar
                    ruta="getTableCDUQ"
                    title="CDU Operaiones"
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
                />
                <GraphBar
                    ruta="getTableCDUQ"
                    title="Seafty Yard"
                    table="6"
                    @addClick="
                        (e) => {
                            showModalSitio(e, 6, 'addSeafty', 'graphSeafty');
                        }
                    "
                    channel="graphSeafty"
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
        />
    </AppLayout>
</template>

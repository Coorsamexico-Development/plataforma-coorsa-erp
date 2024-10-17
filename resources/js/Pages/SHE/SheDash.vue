<script setup>
import { ref } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import GraphBar from "./Partials/GraphBar.vue";
import GraphLine from "./Partials/GraphLine.vue";
import FormSitios from "./Modals/FormSitios.vue";
import FormModal from "./Modals/FormModal.vue";

const position = ref(null);
const show = ref(false);
const graph = ref(null);

function showModalSitio(evt, grafica) {
    position.value = evt.target.getBoundingClientRect();
    show.value = true;
    graph.value = grafica;
}
</script>
<template>
    <AppLayout title="SHE">
        <div class="grid gap-2 p-4 lg:grid-cols-2">
            <GraphBar
                ruta="getTableSitio"
                title="Sitios"
                @addClick="
                    (e) => {
                        showModalSitio(e, 'sitios');
                    }
                "
                graph="sitio"
            />
            <GraphLine ruta="getTableSitioLine" title="Parametros sitios" />
            <GraphBar
                ruta="getTableAnalista"
                title="Analistas"
                @addClick="
                    (e) => {
                        showModalSitio(e, 'analistas');
                    }
                "
                graph="analista"
            />
            <GraphBar
                ruta="getTableSeafty"
                title="Seafty Yard"
                @addClick="
                    (e) => {
                        showModalSitio(e, 'seafty');
                    }
                "
                graph="seafty"
            />
        </div>
        <FormSitios
            :show="show"
            :position="position"
            @close="show = false"
            v-if="graph == 'sitios'"
            maxWidth="4xl"
        />
        <FormModal
            :show="show"
            :position="position"
            @close="show = false"
            v-if="graph == 'analistas'"
            maxWidth="md"
            rutaSeries="getAnalistas"
            rutaAdd="addAnalistaData"
        />
        <FormModal
            :show="show"
            :position="position"
            @close="show = false"
            v-if="graph == 'seafty'"
            maxWidth="md"
            rutaSeries="getSeafty"
            rutaAdd="addSeaftyData"
        />
    </AppLayout>
</template>

<script setup>
import { onBeforeUpdate, ref } from "vue";
const props = defineProps(["rubTot", "meses"]);
const auxArray = ref([]);

onBeforeUpdate(() => {
    const i = props.meses.length;
    let auxObj = [];
    for (let a = 0; a < props.meses.length; a++) {
        auxObj = {
            mes: new Date(
                "2023-0" + (Number(props.meses[a].date.split("-")[1]) + 1)
            ).toLocaleDateString("es-MX", {
                month: "long",
            }),
            riesgo: props.rubTot[a].calificacion,
            sAlta: props.rubTot[a + i].calificacion,
            riesgo2: props.rubTot[a + i * 2].calificacion,
            cotStra: props.rubTot[a + i * 3].calificacion,
            riesIn: props.rubTot[a + i * 4].calificacion,
        };

        auxArray.value[a] = auxObj;
    }
});
</script>
<template>
    <table
        class="w-full overflow-hidden text-sm text-left text-gray-500 rounded-lg shadow-xl dark:text-gray-400"
    >
        <thead
            class="text-xs text-white uppercase bg-[#3A7D7F] dark:bg-gray-700 dark:text-gray-400"
        >
            <tr>
                <th scope="col" class="px-6 py-3 rounded-tl-lg"></th>
                <th scope="col" class="px-6 py-3">Riesgo Correcto</th>
                <th scope="col" class="px-6 py-3">Sin Alta en IMSS</th>
                <th scope="col" class="px-6 py-3">En 2 Riesgos</th>
                <th scope="col" class="px-6 py-3">Cotizando sin Trabajar</th>
                <th scope="col" class="px-6 py-3 rounded-tr-lg">
                    Riesgo Incorrecto
                </th>
            </tr>
        </thead>
        <tbody>
            <tr
                class="overflow-hidden bg-white dark:bg-gray-800 even:bg-[#3A7D7F]/5"
                v-for="rubro in auxArray"
                :key="id"
            >
                <th
                    scope="row"
                    class="px-6 py-4 font-medium text-gray-900 capitalize whitespace-nowrap dark:text-white"
                >
                    {{ rubro.mes }}
                </th>
                <td class="px-6 py-4">
                    {{ rubro.riesgo }}
                </td>
                <td class="px-6 py-4">
                    {{ rubro.sAlta }}
                </td>
                <td class="px-6 py-4">
                    {{ rubro.riesgo2 }}
                </td>
                <td class="px-6 py-4">
                    {{ rubro.cotStra }}
                </td>
                <td class="px-6 py-4">
                    {{ rubro.riesIn }}
                </td>
            </tr>
        </tbody>
    </table>
</template>

<script setup>
import { formatMoney } from "@/utils/formatMoney.js";
import TablaCi from "@/Pages/ControlInterno/Partials/TablaCi.vue";
import ThCi from "@/Pages/ControlInterno/Partials/ThCi.vue";
import TdCi from "@/Pages/ControlInterno/Partials/TdCi.vue";

defineProps({
    tabla: {
        type: Object,
        required: true,
    },
    titulo: {
        type: String,
        default: "Titulo",
    },
});
const money = [1, 2, 3, 6];
</script>
<template>
    <TablaCi class="w-2/12 border-separate border-spacing-1 text-end">
        <tbody>
            <tr>
                <th scope="row" rowspan="1" class="text-[20px] min-w-fit w-fit">
                    Periodos
                </th>
                <!-- Alinea las columnas -->
                <th scope="row" class="py-9"></th>
            </tr>
            <tr v-for="(ats, index) in tabla.atributos" :key="index">
                <ThCi
                    scope="row"
                    rowspan="1"
                    class="px-3 py-1 bg-white rounded-lg min-w-fit w-fit"
                >
                    {{ ats.name }}
                </ThCi>
            </tr>
        </tbody>
    </TablaCi>
    <div class="w-10/12 overflow-x-auto max-w-10/12">
        <TablaCi
            class="w-full text-center border-separate table-auto border-spacing-1"
        >
            <thead>
                <tr>
                    <template
                        v-for="(añoMes, index) in tabla.añoMeses"
                        :key="index"
                    >
                        <ThCi
                            :colspan="Object.keys(añoMes).length"
                            class="text-[20px] text-center bg-white rounded-lg"
                        >
                            {{ index }}
                        </ThCi>
                    </template>
                </tr>
                <tr>
                    <template
                        v-for="(añoMes, index) in tabla.añoMeses"
                        :key="index"
                    >
                        <template v-for="(index, mes) in añoMes" :key="index">
                            <ThCi class="px-3 py-1 bg-white rounded-lg">
                                {{ mes }}
                            </ThCi>
                        </template>
                    </template>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(data, index) in tabla.dataTable" :key="index">
                    <template v-for="(value, index) in data" :key="index">
                        <TdCi class="px-3 py-1 bg-white rounded-lg">
                            {{
                                money.includes(value.atributo)
                                    ? formatMoney(value.value)
                                    : value.atributo == 4
                                    ? `${value.value}%`
                                    : Number(value.value).toFixed(0)
                            }}
                        </TdCi>
                    </template>
                </tr>
            </tbody>
        </TablaCi>
    </div>
</template>

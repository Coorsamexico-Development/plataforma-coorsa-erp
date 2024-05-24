<script setup>
import { formatMoney } from "@/utils/formatMoney.js";
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
    <table class="w-2/12 border-separate border-spacing-1 text-end">
        <tbody>
            <tr>
                <th scope="row" rowspan="1" class="text-[20px] min-w-fit w-fit">
                    Periodos
                </th>
                <!-- Alinea las columnas -->
                <th scope="row" class="py-9"></th>
            </tr>
            <tr v-for="(ats, index) in tabla.atributos" :key="index">
                <th
                    scope="row"
                    rowspan="1"
                    class="px-3 py-1 bg-white rounded-lg min-w-fit w-fit"
                >
                    {{ ats.name }}
                </th>
            </tr>
        </tbody>
    </table>
    <div class="w-10/12 overflow-x-auto max-w-10/12">
        <table
            class="w-full text-center border-separate table-auto border-spacing-1"
        >
            <thead>
                <tr>
                    <template
                        v-for="(añoMes, index) in tabla.añoMeses"
                        :key="index"
                    >
                        <th
                            :colspan="Object.keys(añoMes).length"
                            class="text-[20px] text-center bg-white rounded-lg"
                        >
                            {{ index }}
                        </th>
                    </template>
                </tr>
                <tr>
                    <template
                        v-for="(añoMes, index) in tabla.añoMeses"
                        :key="index"
                    >
                        <template v-for="(index, mes) in añoMes" :key="index">
                            <th class="px-3 py-1 bg-white rounded-lg">
                                {{ mes }}
                            </th>
                        </template>
                    </template>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(data, index) in tabla.dataTable" :key="index">
                    <template v-for="(value, index) in data" :key="index">
                        <td class="px-3 py-1 bg-white rounded-lg">
                            {{
                                money.includes(value.atributo)
                                    ? formatMoney(value.value)
                                    : value.atributo == 4
                                    ? `${value.value}%`
                                    : Number(value.value).toFixed(0)
                            }}
                        </td>
                    </template>
                </tr>
            </tbody>
        </table>
    </div>
</template>

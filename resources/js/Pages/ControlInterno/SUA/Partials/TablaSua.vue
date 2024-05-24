<script setup>
import { formatMoney } from "@/utils/formatMoney.js";
defineProps({
    tabla: {
        type: Object,
        required: true,
    },
});
const money = [1, 2, 3, 6];
</script>
<template>
    <table class="w-2/12 border-separate border-spacing-1 text-end">
        <tbody>
            <tr>
                <th scope="row" rowspan="1" class="text-[20px]">Periodos</th>
                <!-- Alinea las columnas -->
                <th class="p-6"></th>
            </tr>
            <tr v-for="(ats, index) in tabla.atributos" :key="index">
                <th scope="row" rowspan="1">
                    {{ ats.name }}
                </th>
            </tr>
        </tbody>
    </table>
    <div class="w-10/12 overflow-x-auto max-w-10/12">
        <table
            class="w-full text-left border-separate table-auto border-spacing-1"
        >
            <thead>
                <template
                    v-for="(añoMes, index) in tabla.añoMeses"
                    :key="index"
                >
                    <tr>
                        <th colspan="12" class="text-[20px] text-center">
                            {{ index }}
                        </th>
                    </tr>
                    <tr>
                        <template v-for="(index, mes) in añoMes" :key="index">
                            <th class="px-1">{{ mes }}</th>
                        </template>
                    </tr>
                </template>
            </thead>
            <tbody>
                <tr v-for="(data, index) in tabla.dataTable" :key="index">
                    <template v-for="(value, index) in data" :key="index">
                        <td class="px-1">
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

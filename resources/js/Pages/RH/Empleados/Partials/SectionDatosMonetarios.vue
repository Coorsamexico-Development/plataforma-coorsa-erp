<script setup>
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import { computed, watchEffect, ref } from "vue";
import { useForm, usePage } from "@inertiajs/inertia-vue3";
import axios from "axios";
import { formatMoney } from "@/utils/formatMoney.js";

const props = defineProps({
    form: {
        type: Object,
        required: true,
    },
});

let uma = ref(0);
let aguinaldo = ref(0);
let vacaciones = ref(0);
let deducible = ref(0);
let salarioMinimo = ref(0);
let factor = computed(() =>
    Number((365 + aguinaldo.value + vacaciones.value * 0.25) / 365).toFixed(4)
);
const diasMes = 365 / 12;
let salarioMinimoMes = computed(
    () => salarioMinimo.value * factor.value * diasMes
);
let isr = ref(0);

/*Calcular salario*/
const calculoSalario = async () => {
    const salarioBrutoCompuesto = props.form.salario_bruto;
    let valesDespensa = diasMes * uma.value * deducible.value;
    let sueldoImss = (salarioBrutoCompuesto - valesDespensa) / 1.22;
    const salarioMinimoMes = salarioMinimo * factor * diasMes;
    let premioAsist = sueldoImss * 0.1;
    let premioPunt = sueldoImss * 0.1;
    const fondoAhorro = sueldoImss * 0.02;

    if (salarioMinimoMes >= sueldoImss) {
        sueldoImss = salarioMinimoMes;
        const diff = salarioBrutoCompuesto - sueldoImss;
        valesDespensa = diff / 3;
        premioAsist = diff / 3;
        premioPunt = diff / 3;
    }

    const salarioDiario = sueldoImss / diasMes;

    props.form.salario_diario = salarioDiario.toFixed(2);
    props.form.salario_imss = sueldoImss.toFixed(2);
    props.form.bono_puntualidad = premioPunt.toFixed(2);
    props.form.bono_asistencia = premioAsist.toFixed(2);
    props.form.fondo_ahorro = fondoAhorro.toFixed(2);
    props.form.despensa = valesDespensa.toFixed(2);
    return true;
};

const canEdit = computed(() => {
    return usePage().props.value.can["edit-users"];
});

const salarioDiaInt = computed(() =>
    (props.form.salario_diario * factor.value).toFixed(2)
);

const aportacionImss = computed(() => {
    const minimo = (uma.value * 3).toFixed(2);
    var diff = 0;
    if (minimo < salarioDiaInt.value) diff = salarioDiaInt.value - minimo;
    const aportacion = salarioDiaInt.value * 30 * 0.02375 + diff * 0.004;
    return aportacion.toFixed(2);
});

const salarioNeto = computed(() => {
    return (props.form.salario_imss - isr.value - aportacionImss.value).toFixed(
        2
    );
});

watchEffect(async () => {
    const { data } = await axios.post(
        route("getVariablesNomina", { userId: props.form.numero_empleado })
    );
    uma.value = data.uma;
    aguinaldo.value = data.aguinaldo;
    vacaciones.value = data.vacaciones;
    deducible.value = data.deducible;
    salarioMinimo.value = data.salarioMinimo;
    const { data: dataIsr } = await axios.post(
        route("getIsrCalculado", { usuario: props.form.numero_empleado })
    );
    isr.value = dataIsr;
});

function calculoSalarioMinimo() {
    const salarioBruto = props.form.salario_bruto;

    if (salarioBruto < salarioMinimoMes) {
        props.form.salario_bruto = salarioMinimoMes.toFixed(2);
        calculoSalario();
    }
}
</script>

<template>
    <div class="border-b tab">
        <div class="relative border-l-2 border-transparent">
            <input
                checked
                class="absolute z-10 w-full h-10 opacity-0 cursor-pointer top-6"
                type="checkbox"
                id="datosMonetarios"
            />
            <header
                class="flex items-center justify-between p-5 pl-8 pr-8 cursor-pointer select-none tab-label"
                for="datosMonetarios"
            >
                <span class="text-xl font-thin text-grey-darkest">
                    Monetarios
                </span>
                <div
                    class="flex items-center justify-center border rounded-full border-grey w-7 h-7 test"
                >
                    <svg
                        aria-hidden="true"
                        class="w-full"
                        data-reactid="266"
                        fill="none"
                        height="24"
                        stroke="#606F7B"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        viewbox="0 0 24 24"
                        width="24"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </div>
            </header>
            <div class="tab-content">
                <div class="pb-5 pl-8 pr-8">
                    <div class="grid gap-4">
                        <div class="flex justify-between gap-x-4">
                            <div class="w-full">
                                <InputLabel
                                    for="salario_diario"
                                    value="Salario Diario:*"
                                />
                                <TextInput
                                    id="salario_diario"
                                    type="text"
                                    disabled
                                    class="block w-full mt-1"
                                    :placeholder="formatMoney(0)"
                                    v-model="props.form.salario_diario"
                                />
                            </div>
                            <div class="w-full">
                                <InputLabel
                                    for="salario_diario_int"
                                    value="Salario Diario Integrado:*"
                                />
                                <TextInput
                                    id="salario_diario_int"
                                    type="text"
                                    disabled
                                    class="block w-full mt-1"
                                    :placeholder="formatMoney(0)"
                                    v-model="salarioDiaInt"
                                />
                            </div>
                            <div class="w-full">
                                <InputLabel
                                    for="salario_bruto"
                                    value="Salario Bruto Compuesto:*"
                                />
                                <TextInput
                                    v-if="canEdit"
                                    id="salario_bruto"
                                    type="number"
                                    v-model="props.form.salario_bruto"
                                    class="block w-full mt-1"
                                    :placeholder="formatMoney(0)"
                                    @keyup="calculoSalario()"
                                    :min="salarioMinimoMes"
                                    @change="calculoSalarioMinimo()"
                                />
                                <p v-else class="block w-full mt-1">
                                    {{ props.form.salario_bruto }}
                                </p>
                                <InputError
                                    :message="props.form.errors.salario_bruto"
                                    class="mt-2"
                                />
                            </div>
                        </div>
                        <div class="flex justify-between gap-x-4">
                            <div class="w-full">
                                <InputLabel
                                    for="salario_neto"
                                    value="Salario Neto:"
                                />
                                <TextInput
                                    id="salario_neto"
                                    type="text"
                                    v-model="salarioNeto"
                                    disabled
                                    class="block w-full mt-1"
                                    :placeholder="formatMoney(0)"
                                />
                                <InputError
                                    :message="props.form.errors.salario_neto"
                                    class="mt-2"
                                />
                            </div>
                            <div class="w-full">
                                <InputLabel
                                    for="salario_imss"
                                    value="Salario IMSS:*"
                                />
                                <TextInput
                                    id="salario_imss"
                                    type="text"
                                    v-model="props.form.salario_imss"
                                    disabled
                                    class="block w-full mt-1"
                                    :placeholder="formatMoney(0)"
                                />
                                <InputError
                                    :message="props.form.errors.salario_imss"
                                    class="mt-2"
                                />
                            </div>
                            <div class="w-full">
                                <InputLabel
                                    for="fondo_ahorro"
                                    value="Fondo de Ahorro:*"
                                />
                                <TextInput
                                    id="fondo_ahorro"
                                    type="text"
                                    v-model="props.form.fondo_ahorro"
                                    class="block w-full mt-1"
                                    :placeholder="formatMoney(0)"
                                    disabled
                                />
                                <InputError
                                    :message="props.form.errors.fondo_ahorro"
                                    class="mt-2"
                                />
                            </div>
                        </div>

                        <div class="flex justify-between gap-x-4">
                            <div class="w-full">
                                <InputLabel
                                    for="bono_puntualidad"
                                    value="Bono de Puntualidad:*"
                                />
                                <TextInput
                                    id="bono_puntualidad"
                                    type="text"
                                    v-model="props.form.bono_puntualidad"
                                    class="block w-full mt-1"
                                    :placeholder="formatMoney(0)"
                                    disabled
                                />
                                <InputError
                                    :message="
                                        props.form.errors.bono_puntualidad
                                    "
                                    class="mt-2"
                                />
                            </div>
                            <div class="w-full">
                                <InputLabel
                                    for="bono_asistencia"
                                    value="Bono de Asistencia:*"
                                />
                                <TextInput
                                    id="bono_asistencia"
                                    type="text"
                                    v-model="props.form.bono_asistencia"
                                    class="block w-full mt-1"
                                    :placeholder="formatMoney(0)"
                                    disabled
                                />
                                <InputError
                                    :message="props.form.errors.bono_asistencia"
                                    class="mt-2"
                                />
                            </div>
                            <div class="w-full">
                                <InputLabel for="despensa" value="Despensa:*" />
                                <TextInput
                                    id="despensa"
                                    disabled
                                    v-if="canEdit"
                                    type="number"
                                    v-model="props.form.despensa"
                                    class="block w-full mt-1"
                                    :placeholder="formatMoney(0)"
                                    @keyup="calculoSalario()"
                                />
                                <p v-else class="block w-full mt-1">
                                    {{ props.form.despensa }}
                                </p>
                                <InputError
                                    :message="props.form.errors.despensa"
                                    class="mt-2"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

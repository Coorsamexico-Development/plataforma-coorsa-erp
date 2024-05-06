<script setup>
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import { computed, watchEffect } from "vue";
import { useForm, usePage } from "@inertiajs/inertia-vue3";
import axios from "axios";

const props = defineProps({
    form: {
        type: Object,
        required: true,
    },
});

let uma;
let aguinaldo;
let vacaciones;
let deducible;
let salarioMinimo;
let factor;
let diasMes;
let salarioMinimoMes;

/*Calcular salario*/
const calculoSalario = async () => {
    const salarioBruto = props.form.salario_bruto;
    let valesDespensa = diasMes * uma * deducible;
    let sueldoImss = (salarioBruto - valesDespensa) / 1.22;
    const salarioMinimoMes = salarioMinimo * factor * diasMes;
    let premioAsist = sueldoImss * 0.1;
    let premioPunt = sueldoImss * 0.1;
    const fondoAhorro = sueldoImss * 0.02;

    if (salarioMinimoMes >= sueldoImss) {
        sueldoImss = salarioMinimoMes;
        const diff = salarioBruto - sueldoImss;
        valesDespensa = diff / 3;
        premioAsist = diff / 3;
        premioPunt = diff / 3;
    }

    const salarioDiario = sueldoImss / (factor * diasMes);
    const salarioDiaInt = salarioDiario * factor;

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

watchEffect(async () => {
    const { data } = await axios.post(
        route("getVariablesNomina", { userId: props.form.numero_empleado })
    );

    uma = data.uma;
    aguinaldo = data.aguinaldo;
    vacaciones = data.vacaciones;
    deducible = data.deducible;
    salarioMinimo = data.salarioMinimo;
    factor = (365 + aguinaldo + vacaciones * 0.25) / 365;
    diasMes = 365 / 12;
    salarioMinimoMes = salarioMinimo * factor * diasMes;
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
                        class=""
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
                    <div class="grid grid-cols-6 gap-4">
                        <div class="col-span-2">
                            <InputLabel
                                for="salario_diario"
                                value="Salario Diario:*"
                            />
                            <TextInput
                                id="salario_diario"
                                type="text"
                                disabled
                                class="block w-full mt-1"
                                placeholder="$ 00.0"
                                v-model="props.form.salario_diario"
                            />
                        </div>
                        <div class="col-span-2">
                            <InputLabel
                                for="salario_bruto"
                                value="Salario Bruto:*"
                            />
                            <TextInput
                                v-if="canEdit"
                                id="salario_bruto"
                                type="number"
                                v-model="props.form.salario_bruto"
                                class="block w-full mt-1"
                                placeholder="$ 00.0"
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
                        <div class="col-span-2">
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
                                placeholder="$ 00.0"
                            />
                            <InputError
                                :message="props.form.errors.salario_imss"
                                class="mt-2"
                            />
                        </div>
                        <div class="col-span-3">
                            <InputLabel
                                for="fondo_ahorro"
                                value="Fondo de Ahorro:*"
                            />
                            <TextInput
                                id="fondo_ahorro"
                                type="text"
                                v-model="props.form.fondo_ahorro"
                                class="block w-full mt-1"
                                placeholder="$ 00.0"
                                disabled
                            />
                            <InputError
                                :message="props.form.errors.fondo_ahorro"
                                class="mt-2"
                            />
                        </div>
                        <div class="col-span-3">
                            <InputLabel
                                for="bono_puntualidad"
                                value="Bono de Puntualidad:*"
                            />
                            <TextInput
                                id="bono_puntualidad"
                                type="text"
                                v-model="props.form.bono_puntualidad"
                                class="block w-full mt-1"
                                placeholder="$ 00.0"
                                disabled
                            />
                            <InputError
                                :message="props.form.errors.bono_puntualidad"
                                class="mt-2"
                            />
                        </div>
                        <div class="col-span-3">
                            <InputLabel
                                for="bono_asistencia"
                                value="Bono de Asistencia:*"
                            />
                            <TextInput
                                id="bono_asistencia"
                                type="text"
                                v-model="props.form.bono_asistencia"
                                class="block w-full mt-1"
                                placeholder="$ 00.0"
                                disabled
                            />
                            <InputError
                                :message="props.form.errors.bono_asistencia"
                                class="mt-2"
                            />
                        </div>
                        <div class="col-span-3">
                            <InputLabel for="despensa" value="Despensa:*" />
                            <TextInput
                                id="despensa"
                                disabled
                                v-if="canEdit"
                                type="number"
                                v-model="props.form.despensa"
                                class="block w-full mt-1"
                                placeholder="$ 00.0"
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
</template>

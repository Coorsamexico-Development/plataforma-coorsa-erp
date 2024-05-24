<script setup>
import { ref, watchEffect } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import BotonCi from "@/Pages/ControlInterno/Partials/BotonCI.vue";
import Sua from "./SUA/Sua.vue";
import DahsboardCi from "./Dashboard/DahsboardCi.vue";
import NextTime from "./NextTime.vue";
import NominasCi from "./Nominas/NominasCi.vue";

defineProps({
    nominas: {
        type: Object,
    },
});

const menu = ref(0);
</script>

<template>
    <AppLayout title="Dashboard" :nominas="nominas">
        <section class="p-2 pt-8 objetivo_auditoria sm:p-8">
            <div
                class="mr-0 sm:text-center sm:mr-96 sm:pt-8"
                style="font-family: 'Montserrat'"
            >
                <h1
                    class="text-xl font-semibold text-white sm:text-4xl sm:mr-12"
                >
                    Objetivo del área
                </h1>
                <span
                    class="w-16 h-1 bg-[#EC2944] mt-4 sm:ml-96"
                    style="display: block"
                ></span>
            </div>
            <div class="mr-0 sm:mr-12 sm:pl-16">
                <p
                    class="mt-6 mb-16 text-base text-white sm:text-xl sm:ml-72"
                    style="font-family: 'Montserrat'; line-height: 1.8"
                >
                    Dentro de Control Interno, nos encargamos de la creación y
                    seguimiento del cumplimiento de los procesos, políticas,
                    manuales, normas y métodos estratégicos de la empresa, todo
                    con la finalidad de llegar al plan estratégico de esta, para
                    poder lograrlo se realizan evaluaciones continuamente a las
                    distintas áreas que la conforman.
                </p>
            </div>
        </section>
        <section
            class="flex justify-between w-screen h-screen gap-[20px] p-[20px]"
        >
            <div
                class="grid content-start w-1/12 py-[10px] bg-[#F2F2F2] rounded-xl drop-shadow-[1px_1px_5px_#d4d4d4] h-fit"
            >
                <BotonCi
                    value="Dashboard"
                    @click="menu = 0"
                    :disabled="menu === 0"
                />
                <BotonCi value="SUA" @click="menu = 1" :disabled="menu === 1" />
                <BotonCi
                    value="Nominas"
                    @click="menu = 2"
                    :disabled="menu === 2"
                />
                <BotonCi value="CXP" @click="menu = 3" :disabled="menu === 3" />
                <BotonCi
                    value="Atas"
                    @click="menu = 4"
                    :disabled="menu === 4"
                />
                <BotonCi
                    value="Bajas"
                    @click="menu = 5"
                    :disabled="menu === 5"
                />
                <BotonCi
                    value="Maniobras"
                    @click="menu = 6"
                    :disabled="menu === 6"
                />
            </div>
            <div class="w-11/12">
                <DahsboardCi
                    v-if="menu === 0"
                    :show="menu === 0"
                    @menu="(e) => (menu = e)"
                />
                <Sua v-if="menu === 1" :show="menu === 1" />
                <!-- <NominasCi v-if="menu === 2" :show="menu === 2" /> -->
                <NextTime
                    v-if="[2, 3, 4, 5, 6].includes(menu)"
                    :show="[2, 3, 4, 5, 6].includes(menu)"
                    @menu="menu = 0"
                />
            </div>
        </section>
    </AppLayout>
</template>

<script setup>
import { ref, watchEffect } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import BotonCi from "@/Pages/ControlInterno/Partials/BotonCI.vue";
import Sua from "./SUA/Sua.vue";

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
            </div>
            <div class="w-11/12">
                <Sua v-if="menu === 1" :show="menu === 1" />
            </div>
        </section>
    </AppLayout>
</template>

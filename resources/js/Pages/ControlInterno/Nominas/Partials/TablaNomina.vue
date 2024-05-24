<script setup>
import TablaCi from "@/Pages/ControlInterno/Partials/TablaCi.vue";
import ThCi from "@/Pages/ControlInterno/Partials/ThCi.vue";
import TdCi from "@/Pages/ControlInterno/Partials/TdCi.vue";
import Add from "@/Iconos/Add.vue";

defineProps({
    data: {
        type: Object,
        required: true,
    },
});

defineEmits(["add"]);
</script>
<template>
    <TablaCi>
        <thead>
            <tr>
                <th class="px-2"></th>
                <template
                    v-for="(parametro, index) in data.parametros"
                    :key="index"
                >
                    <ThCi>{{ parametro.name }}</ThCi>
                </template>
                <ThCi>
                    <button
                        class="grid content-center w-8 h-8 transition-all duration-200 rotate-180 active:scale-90"
                        @click="
                            $emit('add', $event.target.getBoundingClientRect())
                        "
                    >
                        <Add />
                    </button>
                </ThCi>
            </tr>
        </thead>
        <tbody>
            <template v-for="(atributo, index) in data.atributos" :key="index">
                <tr>
                    <ThCi>{{ atributo.name }}</ThCi>
                    <template v-for="(datos, index) in data.data" :key="index">
                        <TdCi></TdCi>
                    </template>
                </tr>
            </template>
        </tbody>
    </TablaCi>
</template>

<script setup>
import CardCi from "../Partials/CardCi.vue";
import ButtonDashCI from "./Partials/ButtonDashCI.vue";

const props = defineProps({
    show: {
        type: Object,
        default: false,
    },
});

defineEmits(["menu"]);
</script>
<template>
    <CardCi>
        <div class="grid items-center">
            <div class="grid grid-cols-3">
                <div class="grid place-content-center">
                    <ButtonDashCI value="SUA" @click="$emit('menu', 1)" />
                    <div></div>
                </div>
                <div class="grid place-content-center">
                    <ButtonDashCI value="Nominas" @click="$emit('menu', 2)" />
                    <div></div>
                </div>
                <div class="grid place-content-center">
                    <ButtonDashCI value="CXP" @click="$emit('menu', 3)" />
                    <div></div>
                </div>
            </div>
            <div class="grid grid-cols-3">
                <div class="grid place-content-center">
                    <ButtonDashCI value="Altas" @click="$emit('menu', 4)" />
                    <div></div>
                </div>
                <div class="grid place-content-center">
                    <ButtonDashCI value="Bajas" @click="$emit('menu', 5)" />
                    <div></div>
                </div>
                <div class="grid place-content-center">
                    <ButtonDashCI value="Compras" @click="$emit('menu', 6)" />
                    <div></div>
                </div>
            </div>
            <div class="grid grid-cols-3">
                <div class="grid place-content-center">
                    <ButtonDashCI value="Maniobras" @click="$emit('menu', 7)" />
                    <div></div>
                </div>
            </div>
        </div>
    </CardCi>
</template>

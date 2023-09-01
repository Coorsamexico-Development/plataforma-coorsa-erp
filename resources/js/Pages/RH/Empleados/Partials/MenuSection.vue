<script setup>
import ButtonSeccion from "@/Components/ButtonSeccion.vue";

const emit = defineEmits(["changeMenu"]);
const props = defineProps({
    menuSeleted: {
        type: Number,
    },
});
</script>
<template>
    <div class="flex justify-center">
        <div class="grid grid-cols-4 gap-4 mt-2">
            <ButtonSeccion
                @click="emit('changeMenu', 1)"
                :active="props.menuSeleted === 1"
                :disabled="!$page.props.can['empleados.personales']"
            >
                <div
                    class="grid grid-rows-2 gap-1"
                    style="justify-items: center"
                >
                    <img :src="'/assets/img/datos_personales.png'" />
                    <p>Datos Personales</p>
                </div>
            </ButtonSeccion>
            <ButtonSeccion
                :disabled="!$page.props.can['empleados.direccion']"
                @click="emit('changeMenu', 2)"
                :active="props.menuSeleted === 2"
            >
                <div class="grid grid-rows-2" style="justify-items: center">
                    <img :src="'/assets/img/direccion.png'" />
                    <p>Dirección del Empleado</p>
                </div>
            </ButtonSeccion>
            <ButtonSeccion
                :disabled="!$page.props.can['empleados.banco']"
                @click="emit('changeMenu', 3)"
                :active="props.menuSeleted === 3"
            >
                <div class="grid grid-rows-2" style="justify-items: center">
                    <img :src="'/assets/img/datos_bancarios.png'" />
                    <p>Datos Bancarios</p>
                </div>
            </ButtonSeccion>
            <ButtonSeccion
                :disabled="!$page.props.can['empleados.laborales']"
                @click="emit('changeMenu', 4)"
                :active="props.menuSeleted === 4"
            >
                <div class="grid grid-rows-2" style="justify-items: center">
                    <img :src="'/assets/img/datos_laborales.png'" />
                    <p>Datos Laborales</p>
                </div>
            </ButtonSeccion>
            <ButtonSeccion
                :disabled="!$page.props.can['empleados.monetarios']"
                @click="emit('changeMenu', 5)"
                :active="props.menuSeleted === 5"
            >
                <div class="grid grid-rows-2" style="justify-items: center">
                    <img :src="'/assets/img/datos_monetarios.png'" />
                    <p>Datos Monetarios</p>
                </div>
            </ButtonSeccion>
            <ButtonSeccion
                :disabled="!$page.props.can['empleados.salud']"
                @click="emit('changeMenu', 6)"
                :active="props.menuSeleted === 6"
            >
                <div class="grid grid-rows-2" style="justify-items: center">
                    <img :src="'/assets/img/datos_salud.png'" />
                    <p>Aspectos Generales De Salud</p>
                </div>
            </ButtonSeccion>
            <ButtonSeccion
                :disabled="!$page.props.can['empleados.expediente']"
                @click="emit('changeMenu', 7)"
                :active="props.menuSeleted === 7"
            >
                <div class="grid grid-rows-2" style="justify-items: center">
                    <img :src="'/assets/img/datos_salud.png'" />
                    <p>Archivos expedientes</p>
                </div>
            </ButtonSeccion>
            <slot />
        </div>
    </div>
</template>

<script setup>
import CardCi from "./Partials/CardCi.vue";
import NextTime from "@/Iconos/NextTime.vue";
import ButtonDashCi from "./Dashboard/Partials/ButtonDashCI.vue";

defineEmits(["menu"]);
</script>
<template>
    <CardCi>
        <div class="grid place-content-center text-[#e8505b]">
            <span class="text-[40px] text-center font-semibold">
                ¡Upss... Lo sentimos!
            </span>
            <span class="text-[30px] text-center font-medium">
                Estamos trabajando en ello para brindarte un mejor servicio.
                Pronto estara disponible
            </span>
            <NextTime class="h-[75vh] drop-shadow-[1px_1px_4px_#b5b5b5]" />
            <ButtonDashCi
                value="Dashboard"
                @click="$emit('menu')"
                class="bg-[#e8505b]"
            />
        </div>
    </CardCi>
</template>

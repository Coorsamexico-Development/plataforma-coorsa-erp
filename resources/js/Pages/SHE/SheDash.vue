<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import GraphBar from "./Partials/GraphBar.vue";
</script>
<template>
    <AppLayout title="SHE">
        <div class="grid grid-cols-2 place-content-center">
            <GraphBar ruta="getTableSitio" />
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Inertia } from '@inertiajs/inertia';
import { reactive, watch } from 'vue';


var props = defineProps(
    {
    }
);


</script>

<template>
    <app-layout title="Dashboard">
        <template #header>
            Nuevo Empleado
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg">
                
                </div>
            </div>
        </div>
    </app-layout>
</template>


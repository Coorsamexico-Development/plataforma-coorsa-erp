<script setup>
import DialogModalPopUp from "@/Components/DialogModalPopUp.vue";
defineProps({
    position: {
        type: Object,
        required: true,
    },
    show: {
        type: Boolean,
        default: false,
    },
    evento: {
        type: Object,
        required: true,
    },
});

defineEmits(["close"]);
</script>
<template>
    <DialogModalPopUp
        :show="show"
        :position="position"
        @close="$emit('close')"
        maxWidth="sm"
    >
        <template #title>
            <span class="text-[18px] font-semibold">{{
                evento.event.startStr
            }}</span>
        </template>
        <template #content>
            <ul v-if="evento.allSegs" class="capitalize">
                <template v-for="(item, index) in evento.allSegs" :key="index">
                    <li>{{ item.event.title }}</li>
                </template>
            </ul>
            <span class="capitalize" v-else>{{ evento.event.title }}</span>
        </template>
    </DialogModalPopUp>
</template>

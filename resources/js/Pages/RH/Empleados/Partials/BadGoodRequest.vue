<script setup>
import DialogModal from "@/Components/DialogModal.vue";

const emit = defineEmits(["close"]);

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    maxWidth: {
        type: String,
        default: "2xl",
    },
    closeable: {
        type: Boolean,
        default: true,
    },
    data: {
        type: Object,
        default: {
            title: "Titulo",
            type: "god",
        },
    },
});

const close = () => {
    emit("close");
};
</script>
<template>
    <DialogModal :show="show" @close="close">
        <template #title>
            <h1
                class="text-xl font-bold"
                :class="data.type == 'bad' ? 'text-red-700 text-[2rem]' : ''"
            >
                {{ data.title }}
            </h1>
        </template>
        <template #content>
            <div v-if="data.type === 'god'" class="grid gap-4">
                <div class="text-[1.25rem] font-medium">
                    <span>
                        Archivos subidos correctamente:
                        {{ data.info.correctFiles }}
                    </span>
                </div>
                <div class="grid gap-2">
                    <span class="text-[1.25rem] font-medium">
                        Archivos no subidos
                    </span>
                    <ul class="px-2 text-[1rem]">
                        <template
                            v-for="incorectFile in data.info.incorrectFiles"
                            :key="incorectFile"
                        >
                            <li>{{ incorectFile }}</li>
                        </template>
                    </ul>
                </div>
            </div>
            <div
                v-else-if="data.type === 'bad'"
                class="font-medium text-red-700 text-[1.5rem]"
            >
                <span>{{ data.info.data.message }}</span>
            </div>
        </template>
    </DialogModal>
</template>

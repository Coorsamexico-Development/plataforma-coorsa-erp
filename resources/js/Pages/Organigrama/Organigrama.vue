<script setup>
import { useForm, usePage } from "@inertiajs/inertia-vue3";
import { ref, computed } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import DiagramaModal from "./Modals/DiagramaModal.vue";
import Dragable from "vuedraggable";
import Elemento from "./Partials/Elemento.vue";

const props = defineProps(["nominas", "SinArea", "rels", "gerencia", "nodes"]);
const page = usePage();
const modal = ref(false);
let nodos = ref();
const form = useForm({
    elemento: "",
    area: "",
});

const area = () => {
    form.transform((data) => ({
        ...data,
    })).post(route("organigrama.area"), {
        onFinish: () => {
            nodos.value = page.props.value.flash.nodes;
            console.log(nodos.value);
            /* modal.value = true; */
        },
        onCancel: () => form.reset(),
    });
};

const close = () => {
    modal.value = false;
};
const open = () => {
    modal.value = true;
};
</script>
<template>
    <AppLayout :nominas="nominas" title="Organigrama">
        <div class="flex overflow-hidden">
            <Dragable
                :list="SinArea"
                item-key="id"
                group="elementos"
                animation="300"
                tag="div"
                class="p-[1vw] w-[25%] grid gap-[2px] justify-center h-[90vh] overflow-auto"
                drag-class="drag"
                ghost-class="ghost"
                @drop="
                    form.area = 1;
                    area();
                "
            >
                <template #header>
                    <h1
                        class="text-[20px] h-fit font-bold text-center py-[10px] uppercase hover:cursor-pointer"
                        @click="open"
                    >
                        Lista Empleados
                    </h1>
                </template>
                <template #item="{ element }">
                    <Elemento
                        :nodo="element"
                        class="odd:bg-white even:bg-[#e5e7eb] h-fit rounded-md"
                        @mousedown="form.elemento = element"
                    />
                </template>
            </Dragable>
            <Dragable
                :list="gerencia"
                item-key="id"
                group="elementos"
                animation="300"
                tag="div"
                class="p-[1vw] w-[25%] grid gap-[2px] justify-center h-[90vh] overflow-auto"
                drag-class="drag"
                ghost-class="ghost"
                @drop="
                    form.area = 2;
                    area();
                "
            >
                <template #header>
                    <button
                        class="text-[20px] h-fit font-bold text-center py-[10px] uppercase hover:cursor-pointer"
                        @click="
                            nodos = gerencia;
                            open();
                        "
                        :disabled="form.progress"
                    >
                        Gerencia
                    </button>
                </template>
                <template #item="{ element }">
                    <Elemento
                        :nodo="element"
                        class="odd:bg-white even:bg-[#e5e7eb] h-fit rounded-md"
                        @mousedown="form.elemento = element"
                    />
                </template>
            </Dragable>
        </div>
        <DiagramaModal
            title="Gerencia"
            @close="close"
            :show="modal"
            :nodos="nodos"
            :relaciones="rels"
            :max-width="'full'"
        />
    </AppLayout>
</template>

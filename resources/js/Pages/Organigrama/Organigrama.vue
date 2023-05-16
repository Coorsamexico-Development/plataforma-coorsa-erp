<script setup>
import { useForm, usePage } from "@inertiajs/inertia-vue3";
import { ref, onMounted } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import DiagramaModal from "./Modals/DiagramaModal.vue";
import DiagramaAreas from "./Partials/DiagramaAreas.vue";
import Dragable from "vuedraggable";
import Elemento from "./Partials/Elemento.vue";

const props = defineProps(["nominas", "rels", "nodes", "areas", "areaRel"]);

const modal = ref(false);
let nodos = ref();
let rela = ref();
let arean = ref();

const form = useForm({
    elemento: "",
    area: "",
});
const addAreaF = useForm({
    area: "",
});

const area = () => {
    form.transform((data) => ({
        ...data,
    })).post(route("organigrama.area"), {
        onCancel: () => form.reset(),
    });
};
const addArea = () => {
    addAreaF
        .transform((data) => ({
            ...data,
        }))
        .post(route("area.addArea"), {
            onFinish: () => addAreaF.reset(),
            onCancel: () => addAreaF.reset(),
        });
};

const close = () => {
    modal.value = false;
};
const open = (e) => {
    nodos.value = e.n;
    rela.value = e.r;
    arean.value = props.areas[e.a].nombre;
    modal.value = true;
};
const elemento = (elm) => {
    form.area = elm.element;
    area();
};
</script>
<template>
    <AppLayout :nominas="nominas" title="Organigrama">
        <div class="flex overflow-hidden">
            <Dragable
                :list="nodes[1]"
                item-key="id"
                group="elementos"
                animation="300"
                tag="div"
                class="p-[1vw] w-[25%] grid gap-[2px] justify-center h-[90vh] overflow-auto"
                drag-class="drag"
                ghost-class="ghost"
            >
                <template #header>
                    <h1
                        class="text-[20px] h-fit font-bold text-center py-[10px] uppercase"
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
            <div class="h-[90vh] w-full">
                <div class="flex py-3 px-10">
                    <form class="flex gap-10" @submit.prevent="addArea">
                        <input
                            style="border: 1px black solid"
                            :disabled="disable"
                            class="border-black rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 read-only:bg-gray-300"
                            type="text"
                            required
                            v-model="addAreaF.area"
                            placeholder="Nombre del area"
                        />
                        <input
                            type="submit"
                            value="Agregar area"
                            style="
                                border: 0.2rem solid #ec2944;
                                padding: 0.5em 1em 0.5em 1em;
                            "
                            class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-[#1A1A22] uppercase transition border border-gray-300 rounded-md shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200 active:text-gray-800 active:bg-gray-50 disabled:opacity-25 cursor-pointer"
                        />
                    </form>
                </div>
                <DiagramaAreas
                    :nodos="nodes"
                    :areas="areas"
                    :rels="rels"
                    :areaRel="areaRel"
                    @elemento="elemento"
                    @modal="open"
                />
            </div>
        </div>
        <DiagramaModal
            :title="arean"
            @close="close"
            :show="modal"
            :nodos="nodos"
            :relaciones="rela"
            :max-width="'full'"
        />
    </AppLayout>
</template>

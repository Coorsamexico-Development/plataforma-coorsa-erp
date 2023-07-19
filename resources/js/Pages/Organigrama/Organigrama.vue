<script setup>
import { useForm } from "@inertiajs/inertia-vue3";
import { ref, onBeforeUpdate, watch, computed } from "vue";
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
let sons = ref();
let ar = ref();

const form = useForm({
    elemento: "",
    area: "",
});
const addAreaF = useForm({
    area: "",
});

const search = ref("");

const area = () => {
    form.transform((data) => ({
        ...data,
    })).post(route("organigrama.area"), {
        onSuccess: () => form.reset(),
        onCancel: () => form.reset(),
    });
};
const addArea = () => {
    addAreaF
        .transform((data) => ({
            ...data,
        }))
        .post(route("area.addArea"), {
            onSuccess: () => addAreaF.reset(),
            onFinish: () => {
                addAreaF.reset();
            },
            onCancel: () => addAreaF.reset(),
        });
};

const close = () => {
    modal.value = false;
};
const open = (e) => {
    ar.value = e;
    nodos.value = props.nodes[e.a];
    rela.value = props.rels[e.a];
    arean.value = props.areas[e.a - 2];
    modal.value = true;
    let son = [];

    if (props.areaRel) {
        props.areaRel.forEach((element) => {
            if (element.nodoA === props.areas[e.a - 2].nombre) {
                son.push({
                    nodoA: element.nodoA,
                    nodoB: element.nodoB,
                    idA: element.idA,
                    idB: element.idB,
                    padre: element.padre,
                    hijo: element.hijo,
                    ph: element.ph,
                });
            }
        });
    }
    sons.value = son;
};
const elemento = (elm) => {
    form.area = elm.element;
    area();
};
/* onBeforeUpdate(() => {
    rela.value = props.rels[ar.value.a];
}); */

let nodes0 = computed(() => {
    if (search.value === "") return (nodes0.value = props.nodes[0]);
    return props.nodes[0].filter((area) => {
        if (area.Puesto.toLowerCase().includes(search.value.toLowerCase())) {
            return area;
        }
    });
});
</script>
<template>
    <AppLayout :nominas="nominas" title="Organigrama">
        <div class="flex overflow-hidden">
            <Dragable
                v-if="$page.props.can['organigrama.edit']"
                :list="nodes0"
                item-key="id"
                group="elementos"
                animation="300"
                tag="div"
                class="p-[1vw] w-[25%] flex flex-col gap-[2px] h-[90vh] overflow-auto"
                drag-class="drag"
                ghost-class="ghost"
            >
                <template #header>
                    <h1
                        class="text-[20px] h-fit font-bold text-center py-[10px] uppercase"
                    >
                        Lista de Puestos
                    </h1>

                    <input
                        type="search"
                        v-model="search"
                        placeholder="Buscar puesto"
                        aria-label="Search"
                        class="rounded-2xl text-[12px] px-2 py-1 focus:border-transparent uppercase border"
                    />
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
                <div class="flex px-10 py-3">
                    <form
                        class="flex gap-10"
                        @submit.prevent="addArea"
                        v-if="$page.props.can['organigrama.edit']"
                    >
                        <input
                            style="border: 1px black solid"
                            :disabled="disable"
                            class="uppercase border-black rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 read-only:bg-gray-300"
                            type="text"
                            required
                            v-model="addAreaF.area"
                            placeholder="Nombre del area"
                            @keyup.enter="addArea"
                        />
                        <input
                            :disabled="addAreaF.area === ''"
                            type="submit"
                            value="Agregar area"
                            class="inline-flex bg-[#0097f2] hover:bg-[#308EC7] hover:scale-105 items-center px-4 py-2 text-xs font-semibold tracking-widest text-white uppercase transition-all duration-200 rounded-md shadow-sm disabled:opacity-50 cursor-pointer disabled:hover:cursor-default disabled:bg-[#88CEF9] disabled:hover:scale-100"
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
            @modal="open"
            :show="modal"
            :nodos="nodos"
            :relaciones="rela"
            :sons="sons"
            :max-width="'full'"
        />
    </AppLayout>
</template>

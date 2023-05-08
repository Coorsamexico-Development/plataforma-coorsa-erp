<script setup>
import { computed, reactive, watch, ref } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Inertia } from "@inertiajs/inertia";
import { pickBy } from "lodash";
import TableEmpleados from "./Partials/TableEmpleados.vue";
import InputSearch from "@/Components/InputSearch.vue";
import Pagination from "@/Components/Pagination.vue";
import ButtonAdd from "../../../Components/ButtonAdd.vue";
import ButtonInfo from "@/Components/Buttoninfo.vue";
import { Link } from "@inertiajs/inertia-vue3";
import NominaModal from "./Partials/NominaModal.vue";

var props = defineProps({
    empleados: Object,
    activo: String,
    filters: Object,
    nominas: Object,
});

const params = reactive({
    search: props.filters.search,
});
const nominaModal = ref(false);

watch(params, () => {
    const clearParams = pickBy({ ...params });
    Inertia.visit(route("empleado.indexmanual", { activo: props.activo }), {
        data: clearParams,
        replace: true,
        preserveScroll: true,
        preserveState: true,
    });
});

const descargarReporte = () => {
    Inertia.get(route("export.empleados", { activo: props.activo }), {
        replace: true,
        preserveScroll: true,
        preserveState: true,
    });
};

const permission = computed(() => {
    if (props.activo === "activo") {
        return "user-activos";
    }
    return "user-inactivos";
});
</script>

<template>
    <app-layout title="Dashboard" :nominas="nominas">
        <template #header>
            <h2
                v-if="activo === 'activo'"
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                Empleados activos
            </h2>
            <h2
                v-if="activo === 'inactivo'"
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                Empleados inactivos
            </h2>

            <a :href="route('export.empleados', activo)">
                <ButtonInfo style="float: right; margin-top: -1.5rem">
                    <svg
                        id="Capa_1"
                        data-name="Capa 1"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 15.5 18"
                        style="width: 1rem; fill: white; margin-right: 0.5rem"
                    >
                        <path
                            id="Trazado_65"
                            data-name="Trazado 65"
                            class="cls-1"
                            d="M.58,9.01V4.27c-.04-1.62,1.11-3.02,2.66-3.24,.17-.02,.35-.04,.52-.03h6.27c.89-.02,1.74,.35,2.35,1.01,.4,.42,.8,.83,1.2,1.25,.6,.61,.93,1.45,.91,2.32V13.77c.03,1.64-1.14,3.04-2.71,3.24-.12,.02-.24,.02-.36,.02-2.59,0-5.18,.01-7.77,0-1.48,0-2.75-1.09-3.02-2.59-.04-.22-.06-.45-.06-.67,0-1.59,0-3.17,0-4.76Zm6.95-3.4h3.1c.31,.01,.57-.24,.58-.56,0,0,0-.02,0-.02,0-.33-.24-.6-.56-.61H4.59c-.07,0-.13,0-.2,0-.32,.04-.54,.34-.5,.67,.04,.31,.3,.54,.6,.52,1.01,0,2.03,0,3.04,0h0Zm0,4.01h3.08c.32,.02,.59-.23,.61-.56,0-.04,0-.08,0-.12-.04-.32-.31-.55-.62-.53H4.47c-.32-.01-.59,.25-.6,.58-.01,.33,.24,.61,.56,.62,.02,0,.04,0,.06,0,1.01,0,2.02,0,3.04,0h0Zm-1.53,2.81h-1.47c-.1,0-.19,0-.28,.04-.27,.1-.43,.38-.37,.67,.06,.3,.32,.51,.62,.49h2.98c.32,.03,.6-.21,.63-.55,.03-.33-.21-.63-.53-.66-.04,0-.07,0-.11,0-.49,0-.99,0-1.48,0h0Z"
                        />
                    </svg>
                    REPORTE
                </ButtonInfo>
            </a>
            <div
                v-if="
                    activo === 'activo' &&
                    $page.props.can[permission + '.create']
                "
            >
                <Link :href="route('empleado.create')">
                    <ButtonAdd
                        style="
                            float: right;
                            margin-top: -1.5rem;
                            margin-right: 2rem;
                        "
                    >
                        <span style="font-size: 1.4rem; margin-right: 0.5rem"
                            >+</span
                        >
                        AGREGAR
                    </ButtonAdd>
                </Link>
            </div>
            <InputSearch
                v-model="params.search"
                style="float: right; margin-top: -2rem; margin-right: 12rem"
            >
            </InputSearch>
            <ButtonInfo
                v-if="$page.props.can['recibos-nominas']"
                class="float-right -mt-[1.5rem] mr-[8rem]"
                @click="nominaModal = true"
            >
                Nominas
            </ButtonInfo>
        </template>

        <div class="py-12">
            <div class="mx-auto sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg">
                    <TableEmpleados
                        :activo="activo"
                        :empleados="props.empleados.data"
                        :filters="props.filters"
                        :permission="permission"
                    />
                    <Pagination :pagination="props.empleados.data" />
                </div>
            </div>
        </div>
        <NominaModal :show="nominaModal" @close="nominaModal = false" />
    </app-layout>
</template>

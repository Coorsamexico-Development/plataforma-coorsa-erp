<template>
    <DataTable>
        <template #section-header>
            <div class="w-full px-2 py-2 overflow-hidden bg-white shadow sm:rounded-lg">
                <div class="flex gap-2">
                    <title-component class="">Departamentos</title-component>
                    <div>
                        <InputSearch type="search" v-model="params.search" aria-label="Search" />
                    </div>
                </div>
            </div>
        </template>
        <template #table-header>
            <th scope="col" class="w-1/6 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">
                ACCION
            </th>
            <th scope="col" class="w-3/6 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">
                <span class="inline-flex justify-between w-full px-6 py-3" @click="sort('nombre')">Nombre
                    <svg v-if="
                        params.field === 'nombre' &&
                        params.direction === 'asc'
                    " xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                        <path
                            d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z" />
                    </svg>
                    <svg v-if="
                        params.field === 'nombre' &&
                        params.direction === 'desc'
                    " xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                        <path
                            d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z" />
                    </svg>
                </span>
            </th>
            <th scope="col" class="w-3/6 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">
                <span class="inline-flex justify-between w-full px-6 py-3" @click="sort('descripcion')">Departamentos
                    <template v-if="params.field === 'descripcion'">
                        <svg v-if="
                            params.direction === 'asc'
                        " xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                            <path
                                d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z" />
                        </svg>
                        <svg v-if="
                            params.direction === 'desc'
                        " xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                            <path
                                d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z" />
                        </svg>
                    </template>

                </span>
            </th>
            <th scope="col" class="w-2/6 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">
                #Personal
            </th>
            <th scope="col" class="w-2/6 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">
                <span class="inline-flex justify-between w-full px-6 py-3 cursor-pointer" @click="sort('activo_erp')">ACTIVO
                    <template v-if="params.field === 'activo_erp'">
                        <svg v-if="params.direction === 'asc'" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
                            viewBox="0 0 20 20" fill="currentColor">
                            <path
                                d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z" />
                        </svg>
                        <svg v-else xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path
                                d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z" />
                        </svg>
                    </template>
                </span>
            </th>
        </template>
        <template #table-body>
            <tr class="text-gray-500" v-for="departamento in departamentos.data" :key="departamento.id"
                @click="selectedRow(departamento, $event)">
                <td>
                    <info-button v-if="$page.props.can['departamentos.update']" class="relative w-5 h-5 px-0"
                        @click="$emit('update', departamento)">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 absolute z-10 left-[13%] py-1" fill="none"
                            viewBox="0 0 24 24" stroke="#FFFFFF">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </info-button>
                </td>
                <td class="px-2 whitespace-nowrap">
                    {{ departamento.nombre }}
                </td>
                <td class="px-2 whitespace-nowrap">
                    {{ departamento.descripcion }}
                </td>
                <td>{{ departamento.personal }}</td>
                <td class="px-1 py-2 text-sm text-center text-gray-500 uppercase break-all">
                    <svg v-if="departamento.activo_erp" class="w-6 h-6 m-auto text-green-500"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <svg v-else class="w-6 h-6 m-auto text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </td>
            </tr>
        </template>
    </DataTable>
</template>
<script>
import { Link } from "@inertiajs/inertia-vue3";
import { selectElement, removeSelect } from "../../../../utils/index";
import { pickBy, throttle } from "lodash";
import InfoButton from "@/Components/Buttoninfo.vue";
import DataTable from "@/Components/DataTable.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputSearch from "@/Components/InputSearch.vue";
import TitleComponent from "@/Components/Title.vue";

let elementSelect;

export default {
    emits: ["selected", "update", "create", "deletion"],
    props: {
        filters: Object,
        departamentos: Object,
        puestos: Object,
    },
    components: {
        Link,
        InfoButton,
        PrimaryButton,
        DataTable,
        InputSearch,
        TitleComponent,
    },
    data() {
        return {
            variable: 0,
            params: {
                search: this.filters.search,
                field: this.filters.field,
                direction: this.filters.direction,
            },
        };
    },
    methods: {
        sort(field) {
            this.params.field = field;
            this.params.direction =
                this.params.direction === "asc" ? "desc" : "asc";
        },
        selectedRow(departamento, event) {
            if (elementSelect !== undefined) {
                removeSelect(elementSelect);
            }
            elementSelect = event.target.closest("tr");
            selectElement(elementSelect);
            this.$emit("selected", departamento);
        },
    },
    watch: {
        params: {
            handler: throttle(function () {
                let params = pickBy(this.params);
                this.$inertia.get(this.route("departamentos.index"), params, {
                    replace: true,
                    preserveState: true,
                    only: ["departamentos"],
                });
            }, 150),
            deep: true,
        },
    },
};
</script>

<template>
    <app-layout title="Dashboard" :nominas="nominas">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Lista de Departamentos
            </h2>
        </template>
        <div class="py-6">
            <div class="mx-auto sm:px-3 lg:px-8">
                <div class="grid grid-cols-5 overflow-hidden">
                    <div class="col-span-3">
                        <TableDepartamentos
                            :departamentos="departamentos"
                            :filters="filters"
                            @selected="selectRow($event)"
                            @update="showModalDepartamento($event)"
                        >
                        </TableDepartamentos>
                        <div>
                            <pagination :pagination="departamentos" />
                        </div>
                    </div>
                    <TablePuestos
                        class="col-span-2"
                        :departamento="selectDepartamento"
                    />
                </div>
            </div>
        </div>
        <departamento-modal
            :show="showModalDep"
            :typeForm="typeForm"
            :ubicaciones="ubicaciones"
            :clientes="clientes"
            :departamento="departamento"
            @close="closeModalDepartamento"
        />
    </app-layout>
</template>

<script>
import { Link } from "@inertiajs/inertia-vue3";
import { defineComponent } from "vue";
import { pickBy, throttle } from "lodash";
import AppLayout from "@/Layouts/AppLayout.vue";
import TableDepartamentos from "./Partials/TableDepartamentos.vue";
import TablePuestos from "./Partials/TablePuestos.vue";
import CardVue from "@/Components/Card.vue";
import Pagination from "@/Components/Pagination.vue";
import DepartamentoModal from "./Partials/DepartamentoModal.vue";

export default defineComponent({
    props: {
        departamentos: {
            type: Object,
            required: true,
        },
        filters: {
            type: Object,
        },
        ubicaciones: {
            type: Array,
            required: true,
        },
        clientes: {
            type: Array,
            required: true,
        },
        nominas: Object,
    },
    components: {
        AppLayout,
        TableDepartamentos,
        TablePuestos,
        Link,
        CardVue,
        Pagination,
        DepartamentoModal,
    },
    data() {
        return {
            showModalDep: false,
            typeForm: "create",
            selectDepartamento: {
                id: -1,
                nombre: "Sin departamento",
                activo: true,
            },
            departamento: {
                id: -1,
                nombre: "",
                ubicacione_id: null,
                cliente_id: "",
                activo: true,
            },
            confirmingDepDeletion: false,
        };
    },
    methods: {
        closeModalDepartamento() {
            this.showModalDep = false;
            this.typeForm = "create";
        },
        showModalDepartamento(departamento) {
            if (!!departamento) {
                this.typeForm = "update";
                this.departamento = departamento;
            } else {
                this.typeForm = "create";
                this.departamento = {
                    id: -1,
                    nombre: "",
                    ubicacione_id: null,
                    cliente_id: "",
                    activo: true,
                };
            }
            this.showModalDep = true;
        },
        closeModaldelete() {
            this.confirmingDepDeletion = false;
        },
        confirmDepDeletion(index) {
            this.selectDepartamento = pickBy(this.departamentos.data[index]);
            this.confirmingDepDeletion = true;
        },
        deleteDep() {
            const indexDep = this.departamentos.data.findIndex(
                (departamento) => {
                    return this.selectDepartamento.id == departamento.id;
                }
            );
            this.$inertia.delete(
                this.route("departamento.destroy", this.selectDepartamento.id),
                {
                    preserveScroll: true,
                    preserveState: true,
                    onSuccess: () => {
                        this.closeModaldelete();
                        if (indexDep < this.departamentos.total) {
                            this.selectDepartamento = pickBy(
                                this.departamentos.data[indexDep]
                            );
                        } else {
                            this.selectDepartamento.id = -1;
                        }
                    },
                }
            );
        },
        selectRow(selectRow) {
            this.selectDepartamento = selectRow;
        },
        /* showUpdatelDepartamento(departamento){
            if(typeof  departamento != undefined ||  departamento !=''){
               this.typeForm = 'update';
               this.selectDepartamento= {'nombre': '', 'activo': true};
            }else{
                this.typeForm = 'create';
                this.selectDepartamento = departamento
            }
           this.showModalDep = true;
        }, */
    },
});
</script>

<template>
    <app-layout title="Dashboard">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Lista de Departamentos
            </h2>
        </template>
        <div class="py-6">
            <div class="mx-auto sm:px-3 lg:px-8 ">
                <div class="grid grid-cols-5 overflow-hidden">
                    <div class="col-span-3">
                        <TableDepartamentos :departamentos="departamentos" :filters="filters"
                            @selected="selectRow($event)">
                        </TableDepartamentos>
                        <div>
                            <pagination :pagination="departamentos" />
                        </div>
                    </div>
                    <TablePuestos class="col-span-2" :departamento="selectDepartamento" />
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3';
import { defineComponent } from 'vue'
import { pickBy, throttle } from "lodash";
import AppLayout from '@/Layouts/AppLayout.vue'
import TableDepartamentos from './Partials/TableDepartamentos.vue'
import TablePuestos from './Partials/TablePuestos.vue'
import CardVue from '../../../Components/Card.vue';
import Pagination from '@/Components/Pagination.vue';


export default defineComponent({
    props: {
        departamentos: {
            type: Object,
            required: true
        },
        filters: {
            type: Object
        }
    },
    components: {
        AppLayout,
        TableDepartamentos,
        TablePuestos,
        Link,
        CardVue,
        Pagination,
    },
    data() {
        return {
            showModalDep: false,
            typeForm: 'create',
            selectDepartamento: { 'id': -1, 'nombre': 'Sin departamento', 'activo': true },
            departamento: { 'id': -1, 'nombre': '', 'activo': true },
            confirmingDepDeletion: false,
        }
    },
    methods: {
        closeModalDepartamento() {
            this.showModalDep = false;
            this.typeForm = 'create';
        },
        showModalDepartamento(index) {
            if (index >= 0) {
                this.typeForm = 'update';
                this.departamento = pickBy(this.departamentos.data[index]);
            } else {
                this.typeForm = 'create';
                this.departamento = { 'id': -1, 'nombre': '', 'activo': true };
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
            const indexDep = this.departamentos.data.findIndex((departamento) => {
                return this.selectDepartamento.id == departamento.id
            })
            this.$inertia.delete(this.route('departamento.destroy',
                this.selectDepartamento.id), {
                preserveScroll: true,
                preserveState: true,
                onSuccess: () => {
                    this.closeModaldelete();
                    if (indexDep < this.departamentos.total) {
                        this.selectDepartamento = pickBy(this.departamentos.data[indexDep]);
                    } else {
                        this.selectDepartamento.id = -1;
                    }
                }
            })
        },
        selectRow(selectRow) {
            this.selectDepartamento = selectRow;
        }
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

    }
})
</script>

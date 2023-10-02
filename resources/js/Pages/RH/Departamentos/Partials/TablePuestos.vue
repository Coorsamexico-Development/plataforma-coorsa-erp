<template>
    <div class="select-none">
        <data-table>
            <template #section-header>
                <div
                    class="flex items-center w-full gap-2 px-2 py-2 overflow-hidden bg-white shadow sm:rounded-lg"
                >
                    <Titlecomponent class="w-1/3">
                        Puestos <br />
                        <p class="text-sm">({{ departamento.nombre }})</p>
                    </Titlecomponent>
                    <InputSearch
                        type="search"
                        v-model="filters.search"
                        aria-label="Search"
                        class="block w-40 max-w-xs border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    />
                    <primary-button
                        v-if="$page.props.can['puestos.create']"
                        @click="showModalPuesto"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="w-4 h-4"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                clip-rule="evenodd"
                            />
                        </svg>
                        Nuevo
                    </primary-button>
                </div>
            </template>
            <template #table-header>
                <th
                    scope="col"
                    class="w-1/6 text-xs font-semibold tracking-wider text-gray-500 uppercase"
                >
                    <span
                        class="inline-flex justify-between w-full px-6 py-3"
                        @click="sort('')"
                    >
                        Acciones
                        <svg
                            v-if="filters.field === '' && filters.ceco != ''"
                            xmlns="http://www.w3.org/2000/svg"
                            class="w-4 h-4"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                        >
                            <path
                                d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"
                            />
                        </svg>
                    </span>
                </th>

                <th
                    scope="col"
                    class="w-4/6 text-xs font-semibold tracking-wider text-gray-500 uppercase"
                >
                    <span
                        class="inline-flex justify-between w-full px-6 py-3"
                        @click="sort('name')"
                        >Nombre
                        <svg
                            v-if="
                                filters.field === 'name' &&
                                filters.direction === 'asc'
                            "
                            xmlns="http://www.w3.org/2000/svg"
                            class="w-4 h-4"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                        >
                            <path
                                d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"
                            />
                        </svg>
                        <svg
                            v-if="
                                filters.field === 'name' &&
                                filters.direction === 'desc'
                            "
                            xmlns="http://www.w3.org/2000/svg"
                            class="w-4 h-4"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                        >
                            <path
                                d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"
                            />
                        </svg>
                    </span>
                </th>

                <th
                    scope="col"
                    class="w-1/6 text-xs font-semibold tracking-wider text-gray-500 uppercase"
                    v-if="departamento.id != -1"
                >
                    Plantilla Autorizada
                </th>

                <th
                    scope="col"
                    class="w-1/6 text-xs font-semibold tracking-wider text-gray-500 uppercase"
                >
                    <span
                        class="inline-flex justify-between w-full px-6 py-3"
                        @click="sort('activo')"
                        >Activo
                        <svg
                            v-if="
                                filters.field === 'activo' &&
                                filters.direction === 'asc'
                            "
                            xmlns="http://www.w3.org/2000/svg"
                            class="w-4 h-4"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                        >
                            <path
                                d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"
                            />
                        </svg>
                        <svg
                            v-if="
                                filters.field === 'activo' &&
                                filters.direction === 'desc'
                            "
                            xmlns="http://www.w3.org/2000/svg"
                            class="w-4 h-4"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                        >
                            <path
                                d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"
                            />
                        </svg>
                    </span>
                </th>
            </template>
            <template #table-body>
                <tr
                    v-for="(puesto, index) in puestos.data"
                    :key="index"
                    class="select-none"
                >
                    <td class="flex gap-1 px-2 py-2 text-sm">
                        <input
                            v-if="$page.props.can['departamentos.update']"
                            type="checkbox"
                            :id="'puesto-' + puesto.id"
                            name="checke-puesto"
                            class="text-indigo-600 border-gray-300 rounded shadow-sm disabled:bg-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            @change.self="
                                setPuestoToDepartamento(
                                    puesto.id,
                                    $event.target.checked
                                )
                            "
                            v-bind:value="puesto.id"
                            v-model="puestosDepartamento"
                            :disabled="disableCheck"
                        />
                        <info-button
                            v-if="$page.props.can['puestos.update']"
                            class="relative w-5 h-5 px-0"
                            @click="editPuesto(puesto)"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="w-6 h-6 absolute z-10 left-[13%] py-1"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                                />
                            </svg>
                        </info-button>
                        <danger-button
                            v-if="$page.props.can['puestos.delete']"
                            class="relative w-5 h-5 px-0"
                            @click="setDeletion(puesto)"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="absolute z-10 w-6 h-6 py-1"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                />
                            </svg>
                        </danger-button>
                        <info-button
                            class="relative w-5 h-5 px-0"
                            v-if="
                                puestosDepartamento.includes(puesto.id) &&
                                $page.props.can['puestos.empleados']
                            "
                            @click="modalEmp(puesto)"
                        >
                            <Eye
                                class="absolute z-10 w-6 h-6 py-1 text-white left-1"
                            />
                        </info-button>
                    </td>
                    <td
                        class="px-1 py-2 text-sm text-left text-gray-500 uppercase break-all"
                    >
                        {{ puesto.name }}
                    </td>

                    <td
                        class="flex justify-center px-1 py-2 text-sm text-center text-gray-500 uppercase break-all"
                        v-if="departamento.id != -1"
                    >
                        <div v-if="puestosDepartamento.includes(puesto.id)">
                            <span>{{ plantAct[puesto.id] }} / </span
                            ><input
                                type="text"
                                class="p-0 text-sm border-0 w-[25px] focus:ring-0 ml-[4px]"
                                onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                                v-model="plantAuth[puesto.id]"
                                @change="
                                    updatePlantilla($event.target.value, puesto)
                                "
                            />
                        </div>
                    </td>
                    <td
                        class="px-1 py-2 text-sm text-center text-gray-500 uppercase break-all"
                    >
                        <svg
                            v-if="puesto.activo"
                            class="w-6 h-6 m-auto text-green-500"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>
                        <svg
                            v-else
                            class="w-6 h-6 m-auto text-red-500"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>
                    </td>
                </tr>
            </template>
        </data-table>
        <div>
            <pagination-axios :pagination="puestos" @loadPage="loadPage" />
        </div>
        <puesto-modal
            :show="showingPuesto"
            :puesto="puestoSelect"
            :typeForm="typeForm"
            @close="closeModalPuesto"
            @savedPuesto="searchPuestos"
        />
        <jet-dialog-modal
            :show="showingConfirmDeletion"
            @close="closeModalDelete"
        >
            <template #title> Eliminar Puesto </template>

            <template #content>
                Estas seguro que deseas eliminar el puesto?, Una vez que se
                elimine, este ya no estará disponible.
            </template>

            <template #footer>
                <jet-secondary-button @click="closeModalDelete">
                    Cancelar
                </jet-secondary-button>
                &nbsp;
                <danger-button @click="deletePuesto">Eliminar </danger-button>
            </template>
        </jet-dialog-modal>
        <ErrorPuesto
            :show="errorPuesto"
            @close="errorPuesto = false"
            :empleado="empleado"
            :puesto="puesto"
            :dpto="departamento"
        />
        <ErrorOrganigrama
            :show="errorOrg"
            @close="errorOrg = false"
            :area="area"
        />
        <ModalEmpPues
            :puesto="puesto"
            :dpto="departamento"
            :show="empPues"
            @close="empPues = false"
        />
    </div>
</template>
<script>
import { ref } from "vue";
import { Link, useForm } from "@inertiajs/inertia-vue3";
import { pickBy, throttle } from "lodash";
import InfoButton from "@/Components/Buttoninfo.vue";
import DataTable from "@/Components/DataTable.vue";
import SwitchButton from "@/Components/SwitchButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import DangerButton from "@/Components/DangerButton.vue";
import JetSecondaryButton from "@/Components/SecondaryButton.vue";
import JetDialogModal from "@/Components/DialogModal.vue";
import PuestoModal from "../Partials/PuestoModal.vue";
import swal from "sweetalert";
import InputSearch from "@/Components/InputSearch.vue";
import Titlecomponent from "@/Components/Title.vue";
import PaginationAxios from "@/Components/PaginationAxios.vue";
import axios from "axios";
import ErrorPuesto from "../Modal/ErrorPuesto.vue";
import ErrorOrganigrama from "../Modal/ErrorOrganigrama.vue";
import Eye from "@/Iconos/Eye.vue";
import ModalEmpPues from "../Modal/ModalEmp-Pues.vue";

export default {
    props: {
        departamento: {
            type: Object,
            required: true,
        },
    },
    components: {
        Link,
        InfoButton,
        PrimaryButton,
        DataTable,
        PuestoModal,
        DangerButton,
        JetSecondaryButton,
        JetDialogModal,
        SwitchButton,
        InputSearch,
        Titlecomponent,
        PaginationAxios,
        ErrorPuesto,
        Eye,
        ModalEmpPues,
        ErrorOrganigrama,
    },
    setup() {
        const errorPuesto = ref(false);
        const errorOrg = ref(false);
        const empleado = ref();
        const puesto = ref();
        const empPues = ref(false);
        const plantAuth = ref([]);
        const plantAct = ref([]);
        const plantAuthF = useForm({
            puesto: "",
            departamento: "",
            plantilla: 0,
        });
        const area = ref("");

        return {
            errorPuesto,
            empleado,
            puesto,
            empPues,
            plantAuth,
            plantAuthF,
            plantAct,
            errorOrg,
            area,
        };
    },
    data() {
        return {
            filters: {
                search: null,
                field: null,
                direction: null,
                ceco: "",
            },
            puestos: {
                data: [],
            },
            puestoSelect: {
                name: "",
                active: false,
            },
            puestosDepartamento: [],
            typeForm: "create",
            disableCheck: true,
            showingPuesto: false,
            showingConfirmDeletion: false,
        };
    },
    mounted() {
        this.getPuestos();
    },
    methods: {
        async setPuestoToDepartamento(puestoId, checked) {
            await axios
                .put(
                    route("departamento.puestos.update", this.departamento.id),
                    {
                        puesto_id: puestoId,
                        areas_id: 1,
                        checked,
                    }
                )
                .then(({ data }) => {
                    console.log(data);
                    if (data != "ok") {
                        this.errorOrg = true;
                        this.area = data;
                    }
                    this.getPuestosDepartemento();
                })
                .catch((error) => {
                    console.log(error.response);
                    if (error.response) {
                        let messageError = "";
                        const messageServer = error.response.data.message;
                        if (error.response.status != 500) {
                            messageError = messageServer;
                        } else {
                            messageError = "Internal Server Error";
                        }
                        swal(
                            "Error SET Puesto TO Departamento",
                            messageError,
                            "error"
                        );
                    }
                });
        },
        async getPuestosDepartemento() {
            await axios
                .get(route("departamento.puestos.index", this.departamento.id))
                .then(({ data }) => {
                    this.puestosDepartamento = data.dptoPues;
                    this.plantAuth = data.plantilla;
                    this.plantAct = data.empleados;
                })
                .catch((error) => {
                    console.log(error.response);
                    if (error.response) {
                        let messageError = "";
                        const messageServer = error.response.data.message;
                        if (error.response.status != 500) {
                            messageError = messageServer;
                        } else {
                            messageError = "Internal Server Error";
                        }
                        swal("Error GET Puestos", messageError, "error");
                    }
                });
        },
        async getPuestos() {
            await axios
                .get(this.route("puestos.index"), {
                    params: pickBy(this.filters),
                })
                .then((response) => {
                    this.puestos = response.data.puestos;
                    this.filters = response.data.filters;
                })
                .catch((error) => {
                    if (error.response) {
                        let messageError = "";
                        const messageServer = error.response.data.message;
                        if (error.response.status != 500) {
                            messageError = messageServer;
                        } else {
                            messageError = "Internal Server Error";
                        }
                        swal("Error GET Puestos", messageError, "error");
                    }
                });
        },
        async searchPuestos() {
            await axios
                .get(this.route("puestos.index"), {
                    params: pickBy(this.filters),
                })
                .then((response) => {
                    this.puestos = response.data.puestos;
                })
                .catch((error) => {
                    if (error.response) {
                        let messageError = "";
                        const messageServer = error.response.data.message;
                        if (error.response.status != 500) {
                            messageError = messageServer;
                        } else {
                            messageError = "Internal Server Error";
                        }
                        swal("Error search Puesto", messageError, "error");
                    }
                });
        },
        async loadPage(page) {
            await axios
                .get(this.route("puestos.index"), {
                    params: {
                        search: this.filters.search,
                        field: this.filters.field,
                        direction: this.filters.direction,
                        page: page,
                    },
                })
                .then((response) => {
                    this.puestos = response.data.puestos;
                })
                .catch((error) => {
                    if (error.response) {
                        let messageError = "";
                        const messageServer = error.response.data.message;
                        if (error.response.status != 500) {
                            messageError = messageServer;
                        } else {
                            messageError = "Internal Server Error";
                        }
                        swal("Error PAGINATE PUESTO", messageError, "error");
                    }
                });
        },
        sort(field) {
            this.filters.field = field;
            if (field !== "") {
                this.filters.ceco = "";
                this.filters.direction =
                    this.filters.direction === "asc" ? "desc" : "asc";
            } else if (this.filters.ceco === "") {
                this.filters.direction = "";
                this.filters.ceco = this.departamento;
            } else this.filters.ceco = "";
        },
        showModalPuesto() {
            this.typeForm = "create";
            this.showingPuesto = true;
        },
        editPuesto(puesto) {
            this.typeForm = "update";
            this.puestoSelect = puesto;
            this.showingPuesto = true;
        },
        closeModalPuesto() {
            this.showingPuesto = false;
        },
        setDeletion(puesto) {
            this.puestoSelect = pickBy(puesto);
            this.showingConfirmDeletion = true;
        },
        closeModalDelete() {
            this.showingConfirmDeletion = false;
        },
        deletePuesto() {
            axios
                .delete(this.route("puestos.destroy", this.puestoSelect.id))
                .then((response) => {
                    if (response.data.empleado != "succes") {
                        this.empleado = response.data.empleado;
                        this.puesto = this.puestoSelect;
                        this.errorPuesto = true;
                        this.closeModalDelete();
                    } else {
                        this.searchPuestos();
                        this.closeModalDelete();
                    }
                });
        },
        modalEmp(puestoId) {
            this.empPues = true;
            this.puesto = puestoId;
        },
        updatePlantilla(e, puesto) {
            this.plantAuthF.puesto = puesto.id;
            this.plantAuthF.plantilla = e;
            this.plantAuthF.departamento = this.departamento.id;
            axios
                .post(route("emp.puesto.plantilla"), this.plantAuthF, {})
                .then(() => this.getPuestosDepartemento())
                .catch((e) => console.log(e.response));
        },
    },
    watch: {
        departamento: {
            handler: throttle(function () {
                if (this.departamento.id !== -1) {
                    this.disableCheck = false;
                    this.getPuestosDepartemento();
                } else {
                    this.disabledCheck = true;
                    this.puestosDepartamento = [];
                }
            }, 150),
            deep: true,
        },
        filters: {
            handler: throttle(function () {
                this.searchPuestos();
            }, 200),
            deep: true,
        },
    },
};
</script>

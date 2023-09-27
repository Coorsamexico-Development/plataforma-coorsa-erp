<script setup>
import Modal from "@/Components/Modal.vue";
import PaginationAxios from "@/Components/PaginationAxios.vue";
import axios from "axios";
import { watchEffect, ref } from "vue";
import Eye from "@/Iconos/Eye.vue";
import { Inertia } from "@inertiajs/inertia";

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
    puesto: {},
    dpto: {},
});
const emit = defineEmits(["close"]);

const close = () => {
    emit("close");
};

const empleados = ref({ data: [] });
const search = ref("");

async function loadPage(page) {
    await axios
        .get(route("dpto.puesto.emp", [props.dpto.id, props.puesto.id]), {
            params: {
                page: page,
            },
        })
        .then(({ data }) => {
            empleados.value = data;
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
}

watchEffect(async () => {
    if (props.show) {
        try {
            await axios
                .get(route("dpto.puesto.emp", [props.dpto.id, props.puesto.id]))
                .then(({ data }) => {
                    empleados.value = data;
                })
                .catch((e) => {
                    console.log(e.response);
                });
        } catch (e) {
            console.log(e);
        }
    }
});

function buscar() {
    if (props.show) {
        axios
            .get(
                route("dpto.puesto.search", [
                    props.dpto.id,
                    props.puesto.id,
                    search.value,
                ])
            )
            .then(({ data }) => {
                empleados.value = data;
            })
            .catch((e) => {
                loadPage();
            });
    }
}
</script>
<template>
    <Modal
        :show="show"
        :max-width="'3xl'"
        :closeable="closeable"
        @close="close"
    >
        <div class="px-6 py-4 bg-[#F5F5F5]">
            <div class="text-[25px] text-[#505967]">
                <h2 class="text-[20px] font-semibold">
                    {{ dpto.nombre }} {{ dpto.descripcion }}
                </h2>
                <h2 class="font-bold">{{ puesto.name }}</h2>
            </div>

            <div class="mt-4">
                <div>
                    <input
                        type="search"
                        placeholder="Buscador"
                        class="text-[14px] h-fit px-2 py-1 rounded-2xl w-full border-0 focus:border-0 focus:ring-0"
                        v-model="search"
                        @input="buscar()"
                    />
                </div>

                <table
                    class="w-full mt-2 border-separate table-auto border-spacing-1"
                >
                    <tr class="text-left text-[#374151]">
                        <th
                            class="px-2 rounded-2xl text-[17px] text-center w-3/12"
                        >
                            No. Empleado
                        </th>
                        <th class="px-2 rounded-2xl text-[17px] w-8/12">
                            Nombre
                        </th>
                        <th class="px-2 rounded-2xl text-[17px] w-1/12">
                            Expediente
                        </th>
                    </tr>
                    <tr
                        v-for="(emp, index) in empleados.data"
                        :key="index"
                        class="text-[#404957]"
                    >
                        <td
                            class="px-3 py-[2px] rounded-2xl text-center bg-white"
                        >
                            {{ emp.numero_empleado }}
                        </td>
                        <td
                            class="px-3 py-[2px] rounded-2xl normal-case bg-white"
                        >
                            {{ emp.fullname }}
                        </td>
                        <td
                            class="px-3 py-[2px] rounded-2xl normal-case grid place-content-center"
                        >
                            <a
                                class="grid px-2 text-white bg-blue-500 hover:cursor-pointer hover:scale-110 rounded-2xl place-content-center w-fit"
                                :href="route('empleado.edit', emp.id)"
                            >
                                <Eye
                                    class="w-[22px] h-[22px] transition-all duration-200"
                                />
                            </a>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="mt-1">
                <PaginationAxios :pagination="empleados" @loadPage="loadPage" />
            </div>
        </div>
    </Modal>
</template>

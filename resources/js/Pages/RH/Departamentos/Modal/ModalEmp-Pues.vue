<script setup>
import Modal from "@/Components/Modal.vue";
import PaginationAxios from "@/Components/PaginationAxios.vue";
import axios from "axios";
import { watchEffect, ref } from "vue";

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
</script>
<template>
    <Modal
        :show="show"
        :max-width="maxWidth"
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

            <table
                class="w-full mt-4 border-separate table-auto border-spacing-1"
                v-if="empleados !== ''"
            >
                <tr class="text-left text-[#374151]">
                    <th class="px-2 rounded-2xl text-[17px] text-center">
                        No. Empleado
                    </th>
                    <th class="px-2 rounded-2xl text-[17px]">Nombre</th>
                </tr>
                <tr
                    v-for="(emp, index) in empleados.data"
                    :key="index"
                    class="text-[#404957]"
                >
                    <td class="px-3 py-[2px] rounded-2xl text-center bg-white">
                        {{ emp.numero_empleado }}
                    </td>
                    <td class="px-3 py-[2px] rounded-2xl normal-case bg-white">
                        {{ emp.fullname }}
                    </td>
                </tr>
            </table>

            <div class="mt-1">
                <PaginationAxios :pagination="empleados" @loadPage="loadPage" />
            </div>
        </div>
    </Modal>
</template>

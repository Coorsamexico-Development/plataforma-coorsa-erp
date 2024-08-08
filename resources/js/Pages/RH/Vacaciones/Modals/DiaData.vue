<script setup>
import DialogModalPopUp from "@/Components/DialogModalPopUp.vue";
import moment from "moment";
import { watchEffect } from "vue";
const props = defineProps({
    position: {
        type: Object,
        required: true,
    },
    show: {
        type: Boolean,
        default: false,
    },
    evento: {
        type: Object,
        required: true,
    },
});

defineEmits(["close"]);

watchEffect(() =>
    props.show ? console.log(props.evento.event.extendedProps) : null
);
</script>
<template>
    <DialogModalPopUp
        :show="show"
        :position="position"
        @close="$emit('close')"
        maxWidth="4xl"
    >
        <template #title>
            <span class="text-[18px] font-semibold">{{
                evento.event.startStr
            }}</span>
        </template>
        <template #content>
            <table
                class="capitalize border-separate table-auto border-spacing-3"
            >
                <tr>
                    <th></th>
                    <th>Nombre</th>
                    <th>Ceco</th>
                    <th>Puesto</th>
                    <th>Tipo</th>
                    <th>Edad</th>
                </tr>
                <template v-if="evento.allSegs">
                    <tr v-for="(item, index) in evento.allSegs" :key="index">
                        <td>
                            <div
                                class="h-[3rem] w-[3rem] bg-red-50 overflow-hidden rounded-full"
                            >
                                <img
                                    :src="
                                        item.event.extendedProps.foto ??
                                        item.event.extendedProps.profile
                                    "
                                    alt=""
                                />
                            </div>
                        </td>
                        <td>{{ item.event.extendedProps.name }}</td>
                        <td>{{ item.event.extendedProps.ceco }}</td>
                        <td>{{ item.event.extendedProps.puesto }}</td>
                        <td>
                            {{
                                item.event.extendedProps.tipe == 1
                                    ? "Cumleaños"
                                    : "Vacaciones"
                            }}
                        </td>
                        <td>
                            {{
                                moment().subtract(
                                    moment(item.event.extendedProps.fechaNac),
                                    "y"
                                )
                            }}
                        </td>
                    </tr>
                </template>
                <tr v-else>
                    <td>
                        <div
                            class="h-[3rem] w-[3rem] bg-red-50 overflow-hidden rounded-full"
                        >
                            <img
                                :src="
                                    evento.event.extendedProps.foto ??
                                    evento.event.extendedProps.profile
                                "
                                alt=""
                            />
                        </div>
                    </td>
                    <td>{{ evento.event.extendedProps.name }}</td>
                    <td>{{ evento.event.extendedProps.ceco }}</td>
                    <td>{{ evento.event.extendedProps.puesto }}</td>
                    <td>
                        {{
                            evento.event.extendedProps.tipe == 1
                                ? "Cumleaños"
                                : "Vacaciones"
                        }}
                    </td>
                    <td>
                        {{
                            moment()
                                .subtract(
                                    moment(
                                        evento.event.extendedProps.fechaNac
                                    ).format("YYYY"),
                                    "y"
                                )
                                .format("YY")
                        }}
                    </td>
                </tr>
            </table>
        </template>
    </DialogModalPopUp>
</template>

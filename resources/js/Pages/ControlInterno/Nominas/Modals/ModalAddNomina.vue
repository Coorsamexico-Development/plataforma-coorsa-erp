<script setup>
import DialogModalPopUp from "@/Components/DialogModalPopUp.vue";
import TextInputWhitLabel from "@/Components/TextInputWhitLabel.vue";
import DatePicker from "@/Components/DatePicker.vue";
import { reactive } from "vue";
import axios from "axios";
import { useForm } from "@inertiajs/inertia-vue3";

const emit = defineEmits(["close"]);
defineProps({
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
    position: {
        type: Object,
        required: true,
    },
});
const close = () => {
    emit("close");
    dataValues.reset();
};

const dataValues = useForm({
    fecha: null,
    nombre: {
        id: 7,
        porcentaje: null,
        riesgo: null,
    },
    nss: {
        id: 8,
        porcentaje: null,
        riesgo: null,
    },
    rfc: {
        id: 9,
        porcentaje: null,
        riesgo: null,
    },
    ingreso: {
        id: 10,
        porcentaje: null,
        riesgo: null,
    },
    puesto: {
        id: 11,
        porcentaje: null,
        riesgo: null,
    },
    infonavit: {
        id: 12,
        porcentaje: null,
        riesgo: null,
    },
    fonacot: {
        id: 13,
        porcentaje: null,
        riesgo: null,
    },
    bancos: {
        id: 14,
        porcentaje: null,
        riesgo: null,
    },
});

function subirSua() {
    axios
        .post(
            route("addNomina", { ...dataValues }),
            { ...dataValues },
            {
                onUploadProgress: () => {
                    dataValues.processing = true;
                },
            }
        )
        .then(({ data }) => close())
        .catch((err) => console.log(err.response ?? err))
        .finally(() => {
            dataValues.processing = false;
        });
}
</script>
<template>
    <DialogModalPopUp
        :show="show"
        :maxWidth="maxWidth"
        @close="close"
        :position="position"
    >
        <template #title>
            <span>Añadir un nuevo registro</span>
        </template>
        <template #content>
            <div class="grid gap-2">
                <div class="flex items-center justify-between">
                    <DatePicker
                        id="fecha"
                        label="Selccione un mes"
                        :dates="dataValues.fecha"
                        @selectDate="(e) => (dataValues.fecha = e)"
                    />
                </div>
                <div class="flex items-center justify-between gap-2">
                    <div class="grid font-medium">
                        <span class="text-[18px]">Nombre</span>
                        <div class="flex items-center justify-between gap-2">
                            <TextInputWhitLabel
                                label="Rango Porcentual"
                                id="pago"
                                onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                                :value="dataValues.pago"
                                @input="
                                    (e) => (dataValues.nombre.porcentaje = e)
                                "
                            />
                            <TextInputWhitLabel
                                label="Riesgo Inherente"
                                id="pagar"
                                onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                                :value="dataValues.pagar"
                                @input="(e) => (dataValues.nombre.riesgo = e)"
                            />
                        </div>
                    </div>
                    <div class="grid font-medium">
                        <span class="text-[18px]">NSS</span>
                        <div class="flex items-center justify-between gap-2">
                            <TextInputWhitLabel
                                label="Rango Porcentual"
                                id="pago"
                                onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                                :value="dataValues.pago"
                                @input="(e) => (dataValues.nss.porcentaje = e)"
                            />
                            <TextInputWhitLabel
                                label="Riesgo Inherente"
                                id="pagar"
                                onkeypress="if (event.keyCode <div 45 || event.keyCode > 57) event.returnValue = false;"
                                :value="dataValues.pagar"
                                @input="(e) => (dataValues.nss.riesgo = e)"
                            />
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-between gap-2">
                    <div class="grid font-medium">
                        <span class="text-[18px]">RFC</span>
                        <div class="flex items-center justify-between gap-2">
                            <TextInputWhitLabel
                                label="Rango Porcentual"
                                id="pago"
                                onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                                :value="dataValues.pago"
                                @input="(e) => (dataValues.rfc.porcentaje = e)"
                            />
                            <TextInputWhitLabel
                                label="Riesgo Inherente"
                                id="pagar"
                                onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                                :value="dataValues.pagar"
                                @input="(e) => (dataValues.rfc.riesgo = e)"
                            />
                        </div>
                    </div>
                    <div class="grid font-medium">
                        <span class="text-[18px]">Fecha de Ingreso</span>
                        <div class="flex items-center justify-between gap-2">
                            <TextInputWhitLabel
                                label="Rango Porcentual"
                                id="pago"
                                onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                                :value="dataValues.pago"
                                @input="
                                    (e) => (dataValues.ingreso.porcentaje = e)
                                "
                            />
                            <TextInputWhitLabel
                                label="Riesgo Inherente"
                                id="pagar"
                                onkeypress="if (event.keyCode <div 45 || event.keyCode > 57) event.returnValue = false;"
                                :value="dataValues.pagar"
                                @input="(e) => (dataValues.ingreso.riesgo = e)"
                            />
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-between gap-2">
                    <div class="grid font-medium">
                        <span class="text-[18px]">Puesto</span>
                        <div class="flex items-center justify-between gap-2">
                            <TextInputWhitLabel
                                label="Rango Porcentual"
                                id="pago"
                                onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                                :value="dataValues.pago"
                                @input="
                                    (e) => (dataValues.puesto.porcentaje = e)
                                "
                            />
                            <TextInputWhitLabel
                                label="Riesgo Inherente"
                                id="pagar"
                                onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                                :value="dataValues.pagar"
                                @input="(e) => (dataValues.puesto.riesgo = e)"
                            />
                        </div>
                    </div>
                    <div class="grid font-medium">
                        <span class="text-[18px]">Infonavit</span>
                        <div class="flex items-center justify-between gap-2">
                            <TextInputWhitLabel
                                label="Rango Porcentual"
                                id="pago"
                                onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                                :value="dataValues.pago"
                                @input="
                                    (e) => (dataValues.infonavit.porcentaje = e)
                                "
                            />
                            <TextInputWhitLabel
                                label="Riesgo Inherente"
                                id="pagar"
                                onkeypress="if (event.keyCode <div 45 || event.keyCode > 57) event.returnValue = false;"
                                :value="dataValues.pagar"
                                @input="
                                    (e) => (dataValues.infonavit.riesgo = e)
                                "
                            />
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between gap-2">
                    <div class="grid font-medium">
                        <span class="text-[18px]">Fonacot</span>
                        <div class="flex items-center justify-between gap-2">
                            <TextInputWhitLabel
                                label="Rango Porcentual"
                                id="pago"
                                onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                                :value="dataValues.pago"
                                @input="
                                    (e) => (dataValues.fonacot.porcentaje = e)
                                "
                            />
                            <TextInputWhitLabel
                                label="Riesgo Inherente"
                                id="pagar"
                                onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                                :value="dataValues.pagar"
                                @input="(e) => (dataValues.fonacot.riesgo = e)"
                            />
                        </div>
                    </div>
                    <div class="grid font-medium">
                        <span class="text-[18px]">Bancos</span>
                        <div class="flex items-center justify-between gap-2">
                            <TextInputWhitLabel
                                label="Rango Porcentual"
                                id="pago"
                                onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                                :value="dataValues.pago"
                                @input="
                                    (e) => (dataValues.bancos.porcentaje = e)
                                "
                            />
                            <TextInputWhitLabel
                                label="Riesgo Inherente"
                                id="pagar"
                                onkeypress="if (event.keyCode <div 45 || event.keyCode > 57) event.returnValue = false;"
                                :value="dataValues.pagar"
                                @input="(e) => (dataValues.bancos.riesgo = e)"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </template>
        <template #footer>
            <button
                class="px-4 py-1 transition-all duration-200 rounded-xl active:scale-90 bg-[#1D96F1] text-white border-none hover:cursor-pointer disabled:bg-[#f2f2f2]"
                @click="subirSua"
                :disabled="dataValues.processing"
            >
                Cargar
            </button>
        </template>
    </DialogModalPopUp>
</template>

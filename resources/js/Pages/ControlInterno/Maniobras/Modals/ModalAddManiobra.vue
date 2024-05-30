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
    formReq: {
        id: 43,
        porcentaje: null,
        riesgo: null,
    },
    VoBo: {
        id: 44,
        porcentaje: null,
        riesgo: null,
    },
    cuenta: {
        id: 45,
        porcentaje: null,
        riesgo: null,
    },
    ventas: {
        id: 46,
        porcentaje: null,
        riesgo: null,
    },
    pago: {
        id: 47,
        porcentaje: null,
        riesgo: null,
    },
});

function subirSua() {
    axios
        .post(
            route("addManiobra", { ...dataValues }),
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
                        <span class="text-[18px]">
                            Formato de requisicion
                        </span>
                        <div class="flex items-center justify-between gap-2">
                            <TextInputWhitLabel
                                label="Rango Porcentual"
                                id="pago"
                                onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                                :value="dataValues.pago"
                                @input="
                                    (e) => (dataValues.formReq.porcentaje = e)
                                "
                            />
                            <TextInputWhitLabel
                                label="Riesgo Inherente"
                                id="pagar"
                                onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                                :value="dataValues.pagar"
                                @input="(e) => (dataValues.formReq.riesgo = e)"
                            />
                        </div>
                    </div>
                    <div class="grid font-medium">
                        <span class="text-[18px]">
                            Correo Vo. Bo. del cliente
                        </span>
                        <div class="flex items-center justify-between gap-2">
                            <TextInputWhitLabel
                                label="Rango Porcentual"
                                id="pago"
                                onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                                :value="dataValues.pago"
                                @input="(e) => (dataValues.VoBo.porcentaje = e)"
                            />
                            <TextInputWhitLabel
                                label="Riesgo Inherente"
                                id="pagar"
                                onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                                :value="dataValues.pagar"
                                @input="(e) => (dataValues.VoBo.riesgo = e)"
                            />
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-between gap-2">
                    <div class="grid font-medium">
                        <span class="text-[18px]"> Estado de cuenta </span>
                        <div class="flex items-center justify-between gap-2">
                            <TextInputWhitLabel
                                label="Rango Porcentual"
                                id="pago"
                                onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                                :value="dataValues.pago"
                                @input="
                                    (e) => (dataValues.cuenta.porcentaje = e)
                                "
                            />
                            <TextInputWhitLabel
                                label="Riesgo Inherente"
                                id="pagar"
                                onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                                :value="dataValues.pagar"
                                @input="(e) => (dataValues.cuenta.riesgo = e)"
                            />
                        </div>
                    </div>
                    <div class="grid font-medium">
                        <span class="text-[18px]"> Cuadro Ventas </span>
                        <div class="flex items-center justify-between gap-2">
                            <TextInputWhitLabel
                                label="Rango Porcentual"
                                id="pago"
                                onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                                :value="dataValues.pago"
                                @input="
                                    (e) => (dataValues.ventas.porcentaje = e)
                                "
                            />
                            <TextInputWhitLabel
                                label="Riesgo Inherente"
                                id="pagar"
                                onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                                :value="dataValues.pagar"
                                @input="(e) => (dataValues.ventas.riesgo = e)"
                            />
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-between gap-2">
                    <div class="grid font-medium">
                        <span class="text-[18px]"> Comprobante de pago </span>
                        <div class="flex items-center justify-between gap-2">
                            <TextInputWhitLabel
                                label="Rango Porcentual"
                                id="pago"
                                onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                                :value="dataValues.pago"
                                @input="(e) => (dataValues.pago.porcentaje = e)"
                            />
                            <TextInputWhitLabel
                                label="Riesgo Inherente"
                                id="pagar"
                                onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                                :value="dataValues.pagar"
                                @input="(e) => (dataValues.pago.riesgo = e)"
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

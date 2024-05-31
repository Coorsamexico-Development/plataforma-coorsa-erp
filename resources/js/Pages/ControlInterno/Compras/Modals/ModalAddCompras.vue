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
    reqComp: {
        id: 36,
        porcentaje: null,
        riesgo: null,
    },
    timeResp: {
        id: 37,
        porcentaje: null,
        riesgo: null,
    },
    cotizacion: {
        id: 38,
        porcentaje: null,
        riesgo: null,
    },
    authCompra: {
        id: 39,
        porcentaje: null,
        riesgo: null,
    },
    contrarecibo: {
        id: 40,
        porcentaje: null,
        riesgo: null,
    },
    gastDiv: {
        id: 41,
        porcentaje: null,
        riesgo: null,
    },
    cumplimiento: {
        id: 42,
        porcentaje: null,
        riesgo: null,
    },
});

function subirSua() {
    axios
        .post(
            route("addCompras", { ...dataValues }),
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
                            Formato de Req. de compras
                        </span>
                        <div class="flex items-center justify-between gap-2">
                            <TextInputWhitLabel
                                label="Rango Porcentual"
                                id="pago"
                                onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                                :value="dataValues.pago"
                                @input="
                                    (e) => (dataValues.reqComp.porcentaje = e)
                                "
                            />
                            <TextInputWhitLabel
                                label="Riesgo Inherente"
                                id="pagar"
                                onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                                :value="dataValues.pagar"
                                @input="(e) => (dataValues.reqComp.riesgo = e)"
                            />
                        </div>
                    </div>
                    <div class="grid font-medium">
                        <span class="text-[18px]"> Tiempo de Respuesta </span>
                        <div class="flex items-center justify-between gap-2">
                            <TextInputWhitLabel
                                label="Rango Porcentual"
                                id="pago"
                                onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                                :value="dataValues.pago"
                                @input="
                                    (e) => (dataValues.timeResp.porcentaje = e)
                                "
                            />
                            <TextInputWhitLabel
                                label="Riesgo Inherente"
                                id="pagar"
                                onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                                :value="dataValues.pagar"
                                @input="(e) => (dataValues.timeResp.riesgo = e)"
                            />
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-between gap-2">
                    <div class="grid font-medium">
                        <span class="text-[18px]">
                            Cuadro comparativo / cotizacion
                        </span>
                        <div class="flex items-center justify-between gap-2">
                            <TextInputWhitLabel
                                label="Rango Porcentual"
                                id="pago"
                                onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                                :value="dataValues.pago"
                                @input="
                                    (e) =>
                                        (dataValues.cotizacion.porcentaje = e)
                                "
                            />
                            <TextInputWhitLabel
                                label="Riesgo Inherente"
                                id="pagar"
                                onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                                :value="dataValues.pagar"
                                @input="
                                    (e) => (dataValues.cotizacion.riesgo = e)
                                "
                            />
                        </div>
                    </div>
                    <div class="grid font-medium">
                        <span class="text-[18px]">
                            Formato de autorizacion de compra
                        </span>
                        <div class="flex items-center justify-between gap-2">
                            <TextInputWhitLabel
                                label="Rango Porcentual"
                                id="pago"
                                onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                                :value="dataValues.pago"
                                @input="
                                    (e) =>
                                        (dataValues.authCompra.porcentaje = e)
                                "
                            />
                            <TextInputWhitLabel
                                label="Riesgo Inherente"
                                id="pagar"
                                onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                                :value="dataValues.pagar"
                                @input="
                                    (e) => (dataValues.authCompra.riesgo = e)
                                "
                            />
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-between gap-2">
                    <div class="grid font-medium">
                        <span class="text-[18px]"> Contrarecibo </span>
                        <div class="flex items-center justify-between gap-2">
                            <TextInputWhitLabel
                                label="Rango Porcentual"
                                id="pago"
                                onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                                :value="dataValues.pago"
                                @input="
                                    (e) =>
                                        (dataValues.contrarecibo.porcentaje = e)
                                "
                            />
                            <TextInputWhitLabel
                                label="Riesgo Inherente"
                                id="pagar"
                                onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                                :value="dataValues.pagar"
                                @input="
                                    (e) => (dataValues.contrarecibo.riesgo = e)
                                "
                            />
                        </div>
                    </div>
                    <div class="grid font-medium">
                        <span class="text-[18px]">
                            Formato unico de gastos diversos / factura
                        </span>
                        <div class="flex items-center justify-between gap-2">
                            <TextInputWhitLabel
                                label="Rango Porcentual"
                                id="pago"
                                onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                                :value="dataValues.pago"
                                @input="
                                    (e) => (dataValues.gastDiv.porcentaje = e)
                                "
                            />
                            <TextInputWhitLabel
                                label="Riesgo Inherente"
                                id="pagar"
                                onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                                :value="dataValues.pagar"
                                @input="(e) => (dataValues.gastDiv.riesgo = e)"
                            />
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-between gap-2">
                    <div class="grid font-medium">
                        <span class="text-[18px]">
                            Cumplimiento del proceso de inicio a fin
                        </span>
                        <div class="flex items-center justify-between gap-2">
                            <TextInputWhitLabel
                                label="Rango Porcentual"
                                id="pago"
                                onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                                :value="dataValues.pago"
                                @input="
                                    (e) =>
                                        (dataValues.cumplimiento.porcentaje = e)
                                "
                            />
                            <TextInputWhitLabel
                                label="Riesgo Inherente"
                                id="pagar"
                                onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                                :value="dataValues.pagar"
                                @input="
                                    (e) => (dataValues.cumplimiento.riesgo = e)
                                "
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

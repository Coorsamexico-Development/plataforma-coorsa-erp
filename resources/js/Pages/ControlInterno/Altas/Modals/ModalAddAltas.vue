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
        calificacion: null,
    },
    rfc: {
        id: 9,
        porcentaje: null,
        riesgo: null,
        calificacion: null,
    },
    curp: {
        id: 19,
        porcentaje: null,
        riesgo: null,
        calificacion: null,
    },
    alta: {
        id: 20,
        porcentaje: null,
        riesgo: null,
        calificacion: null,
    },
    envTyF: {
        id: 21,
        porcentaje: null,
        riesgo: null,
        calificacion: null,
    },
    RnPRH: {
        id: 22,
        porcentaje: null,
        riesgo: null,
        calificacion: null,
    },
    DocCorr: {
        id: 23,
        porcentaje: null,
        riesgo: null,
        calificacion: null,
    },
    ClasRies: {
        id: 24,
        porcentaje: null,
        riesgo: null,
        calificacion: null,
    },
    FormAuth: {
        id: 25,
        porcentaje: null,
        riesgo: null,
        calificacion: null,
    },
    CFDI: {
        id: 26,
        porcentaje: null,
        riesgo: null,
        calificacion: null,
    },
});

function subirSua() {
    axios
        .post(
            route("addAltas", { ...dataValues }),
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
                            <TextInputWhitLabel
                                label="Calificacion"
                                id="pagar"
                                onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                                :value="dataValues.pagar"
                                @input="
                                    (e) => (dataValues.nombre.calificacion = e)
                                "
                            />
                        </div>
                    </div>
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
                            <TextInputWhitLabel
                                label="Calificacion"
                                id="pagar"
                                onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                                :value="dataValues.pagar"
                                @input="
                                    (e) => (dataValues.rfc.calificacion = e)
                                "
                            />
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-between gap-2">
                    <div class="grid font-medium">
                        <span class="text-[18px]">CURP</span>
                        <div class="flex items-center justify-between gap-2">
                            <TextInputWhitLabel
                                label="Rango Porcentual"
                                id="pago"
                                onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                                :value="dataValues.pago"
                                @input="(e) => (dataValues.curp.porcentaje = e)"
                            />
                            <TextInputWhitLabel
                                label="Riesgo Inherente"
                                id="pagar"
                                onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                                :value="dataValues.pagar"
                                @input="(e) => (dataValues.curp.riesgo = e)"
                            />
                            <TextInputWhitLabel
                                label="Calificacion"
                                id="pagar"
                                onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                                :value="dataValues.pagar"
                                @input="
                                    (e) => (dataValues.curp.calificacion = e)
                                "
                            />
                        </div>
                    </div>
                    <div class="grid font-medium">
                        <span class="text-[18px]">Formato de Alta</span>
                        <div class="flex items-center justify-between gap-2">
                            <TextInputWhitLabel
                                label="Rango Porcentual"
                                id="pago"
                                onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                                :value="dataValues.pago"
                                @input="(e) => (dataValues.alta.porcentaje = e)"
                            />
                            <TextInputWhitLabel
                                label="Riesgo Inherente"
                                id="pagar"
                                onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                                :value="dataValues.pagar"
                                @input="(e) => (dataValues.alta.riesgo = e)"
                            />
                            <TextInputWhitLabel
                                label="Calificacion"
                                id="pagar"
                                onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                                :value="dataValues.pagar"
                                @input="
                                    (e) => (dataValues.alta.calificacion = e)
                                "
                            />
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-between gap-2">
                    <div class="grid font-medium">
                        <span class="text-[18px]">
                            Enviado en tiempo y forma
                        </span>
                        <div class="flex items-center justify-between gap-2">
                            <TextInputWhitLabel
                                label="Rango Porcentual"
                                id="pago"
                                onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                                :value="dataValues.pago"
                                @input="
                                    (e) => (dataValues.envTyF.porcentaje = e)
                                "
                            />
                            <TextInputWhitLabel
                                label="Riesgo Inherente"
                                id="pagar"
                                onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                                :value="dataValues.pagar"
                                @input="(e) => (dataValues.envTyF.riesgo = e)"
                            />
                            <TextInputWhitLabel
                                label="Calificacion"
                                id="pagar"
                                onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                                :value="dataValues.pagar"
                                @input="
                                    (e) => (dataValues.envTyF.calificacion = e)
                                "
                            />
                        </div>
                    </div>
                    <div class="grid font-medium">
                        <span class="text-[18px]">
                            Registro en plataforma RH
                        </span>
                        <div class="flex items-center justify-between gap-2">
                            <TextInputWhitLabel
                                label="Rango Porcentual"
                                id="pago"
                                onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                                :value="dataValues.pago"
                                @input="
                                    (e) => (dataValues.RnPRH.porcentaje = e)
                                "
                            />
                            <TextInputWhitLabel
                                label="Riesgo Inherente"
                                id="pagar"
                                onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                                :value="dataValues.pagar"
                                @input="(e) => (dataValues.RnPRH.riesgo = e)"
                            />
                            <TextInputWhitLabel
                                label="Calificacion"
                                id="pagar"
                                onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                                :value="dataValues.pagar"
                                @input="
                                    (e) => (dataValues.RnPRH.calificacion = e)
                                "
                            />
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-between gap-2">
                    <div class="grid font-medium">
                        <span class="text-[18px]">
                            Documentacion correspondiente
                        </span>
                        <div class="flex items-center justify-between gap-2">
                            <TextInputWhitLabel
                                label="Rango Porcentual"
                                id="pago"
                                onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                                :value="dataValues.pago"
                                @input="
                                    (e) => (dataValues.DocCorr.porcentaje = e)
                                "
                            />
                            <TextInputWhitLabel
                                label="Riesgo Inherente"
                                id="pagar"
                                onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                                :value="dataValues.pagar"
                                @input="(e) => (dataValues.DocCorr.riesgo = e)"
                            />
                            <TextInputWhitLabel
                                label="Calificacion"
                                id="pagar"
                                onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                                :value="dataValues.pagar"
                                @input="
                                    (e) => (dataValues.DocCorr.calificacion = e)
                                "
                            />
                        </div>
                    </div>
                    <div class="grid font-medium">
                        <span class="text-[18px]">
                            Clasificacion de riesgo
                        </span>
                        <div class="flex items-center justify-between gap-2">
                            <TextInputWhitLabel
                                label="Rango Porcentual"
                                id="pago"
                                onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                                :value="dataValues.pago"
                                @input="
                                    (e) => (dataValues.ClasRies.porcentaje = e)
                                "
                            />
                            <TextInputWhitLabel
                                label="Riesgo Inherente"
                                id="pagar"
                                onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                                :value="dataValues.pagar"
                                @input="(e) => (dataValues.ClasRies.riesgo = e)"
                            />
                            <TextInputWhitLabel
                                label="Calificacion"
                                id="pagar"
                                onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                                :value="dataValues.pagar"
                                @input="
                                    (e) =>
                                        (dataValues.ClasRies.calificacion = e)
                                "
                            />
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-between gap-2">
                    <div class="grid font-medium">
                        <span class="text-[18px]"> Formato Autorizado </span>
                        <div class="flex items-center justify-between gap-2">
                            <TextInputWhitLabel
                                label="Rango Porcentual"
                                id="pago"
                                onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                                :value="dataValues.pago"
                                @input="
                                    (e) => (dataValues.FormAuth.porcentaje = e)
                                "
                            />
                            <TextInputWhitLabel
                                label="Riesgo Inherente"
                                id="pagar"
                                onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                                :value="dataValues.pagar"
                                @input="(e) => (dataValues.FormAuth.riesgo = e)"
                            />
                            <TextInputWhitLabel
                                label="Calificacion"
                                id="pagar"
                                onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                                :value="dataValues.pagar"
                                @input="
                                    (e) =>
                                        (dataValues.FormAuth.calificacion = e)
                                "
                            />
                        </div>
                    </div>
                    <div class="grid font-medium">
                        <span class="text-[18px]">CFDI</span>
                        <div class="flex items-center justify-between gap-2">
                            <TextInputWhitLabel
                                label="Rango Porcentual"
                                id="pago"
                                onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                                :value="dataValues.pago"
                                @input="(e) => (dataValues.CFDI.porcentaje = e)"
                            />
                            <TextInputWhitLabel
                                label="Riesgo Inherente"
                                id="pagar"
                                onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                                :value="dataValues.pagar"
                                @input="(e) => (dataValues.CFDI.riesgo = e)"
                            />
                            <TextInputWhitLabel
                                label="Calificacion"
                                id="pagar"
                                onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                                :value="dataValues.pagar"
                                @input="
                                    (e) => (dataValues.CFDI.calificacion = e)
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

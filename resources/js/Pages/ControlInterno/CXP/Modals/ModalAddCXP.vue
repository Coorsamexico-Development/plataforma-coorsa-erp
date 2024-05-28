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
    SAPP: {
        id: 15,
        porcentaje: null,
        riesgo: null,
        calificacion: 0,
    },
    FSVFP: {
        id: 16,
        porcentaje: null,
        riesgo: null,
        calificacion: 0,
    },
    FPVFP: {
        id: 17,
        porcentaje: null,
        riesgo: null,
        calificacion: 0,
    },
    GAP: {
        id: 18,
        porcentaje: null,
        riesgo: null,
        calificacion: 0,
    },
});

function subirSua() {
    axios
        .post(
            route("addCXP", { ...dataValues }),
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
                <div class="grid font-medium">
                    <span class="text-[18px]">
                        Solicitud autorizada con fecha de programacion de pago
                    </span>
                    <div class="flex items-center justify-between gap-2">
                        <TextInputWhitLabel
                            label="Rango Porcentual"
                            id="pago"
                            onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                            :value="dataValues.SAPP"
                            @input="(e) => (dataValues.SAPP.porcentaje = e)"
                        />
                        <TextInputWhitLabel
                            label="Riesgo Inherente"
                            id="pagar"
                            onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                            :value="dataValues.pagar"
                            @input="(e) => (dataValues.SAPP.riesgo = e)"
                        />
                        <TextInputWhitLabel
                            label="Calificacion"
                            id="pagar"
                            onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                            :value="dataValues.pagar"
                            @input="(e) => (dataValues.SAPP.calificacion = e)"
                        />
                    </div>
                </div>
                <div class="grid font-medium">
                    <span class="text-[18px]">
                        Fecha de solicitud vs Fecha programada no mayor a 3 dias
                    </span>
                    <div class="flex items-center justify-between gap-2">
                        <TextInputWhitLabel
                            label="Rango Porcentual"
                            id="pago"
                            onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                            :value="dataValues.pago"
                            @input="(e) => (dataValues.FSVFP.porcentaje = e)"
                        />
                        <TextInputWhitLabel
                            label="Riesgo Inherente"
                            id="pagar"
                            onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                            :value="dataValues.pagar"
                            @input="(e) => (dataValues.FSVFP.riesgo = e)"
                        />
                        <TextInputWhitLabel
                            label="Calificacion"
                            id="pagar"
                            onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                            :value="dataValues.pagar"
                            @input="(e) => (dataValues.FSVFP.calificacion = e)"
                        />
                    </div>
                </div>
                <div class="grid font-medium">
                    <span class="text-[18px]">
                        Fecha programada vs Fecha de pago coincidan
                    </span>
                    <div class="flex items-center justify-between gap-2">
                        <TextInputWhitLabel
                            label="Rango Porcentual"
                            id="pago"
                            onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                            :value="dataValues.pago"
                            @input="(e) => (dataValues.FPVFP.porcentaje = e)"
                        />
                        <TextInputWhitLabel
                            label="Riesgo Inherente"
                            id="pagar"
                            onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                            :value="dataValues.pagar"
                            @input="(e) => (dataValues.FPVFP.riesgo = e)"
                        />
                        <TextInputWhitLabel
                            label="Calificacion"
                            id="pagar"
                            onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                            :value="dataValues.pagar"
                            @input="(e) => (dataValues.FPVFP.calificacion = e)"
                        />
                    </div>
                </div>
                <div class="grid font-medium">
                    <span class="text-[18px]">
                        Solo gastos autorizados sean pagados
                    </span>
                    <div class="flex items-center justify-between gap-2">
                        <TextInputWhitLabel
                            label="Rango Porcentual"
                            id="pago"
                            onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                            :value="dataValues.pago"
                            @input="(e) => (dataValues.GAP.porcentaje = e)"
                        />
                        <TextInputWhitLabel
                            label="Riesgo Inherente"
                            id="pagar"
                            onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                            :value="dataValues.pagar"
                            @input="(e) => (dataValues.GAP.riesgo = e)"
                        />
                        <TextInputWhitLabel
                            label="Riesgo Inherente"
                            id="pagar"
                            onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                            :value="dataValues.pagar"
                            @input="(e) => (dataValues.GAP.calificacion = e)"
                        />
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

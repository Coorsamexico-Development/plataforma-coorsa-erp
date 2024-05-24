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
};

const dataValues = useForm({
    fecha: null,
    pago: 0,
    pagar: 0,
    diff: 0,
    incre: 0,
});

const dataValuesConsult = reactive({
    fecha: null,
    pago: 0,
    pagar: 0,
    diff: 0,
    incre: 0,
});

function subirSua() {
    axios
        .post(
            route("dataEvolucionImss", { ...dataValues }),
            { ...dataValues },
            {
                onUploadProgress: () => {
                    dataValues.processing = true;
                },
            }
        )
        .then(({ data }) => console.log(data))
        .catch((err) => console.log(err.response ?? err))
        .finally(() => {
            dataValues.processing = false;
            close();
            dataValues.reset();
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
                        :dates="dataValuesConsult.fecha"
                        @selectDate="(e) => (dataValues.fecha = e)"
                    />
                </div>
                <div class="flex items-center justify-between gap-2">
                    <TextInputWhitLabel
                        label="Se pago"
                        id="pago"
                        onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                        :value="dataValuesConsult.pago"
                        @input="(e) => (dataValues.pago = e)"
                    />
                    <TextInputWhitLabel
                        label="Se debio pagar"
                        id="pagar"
                        onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                        :value="dataValuesConsult.pagar"
                        @input="(e) => (dataValues.pagar = e)"
                    />
                </div>
                <div class="flex items-center justify-between gap-2">
                    <TextInputWhitLabel
                        label="Diferencia"
                        id="diff"
                        onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                        :value="dataValuesConsult.diff"
                        @input="(e) => (dataValues.diff = e)"
                    />
                    <TextInputWhitLabel
                        label="Incremento %"
                        id="incr"
                        onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                        :value="dataValuesConsult.incre"
                        @input="(e) => (dataValues.incre = e)"
                        maxlength="3"
                    />
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

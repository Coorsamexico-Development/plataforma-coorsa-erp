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
    colaboradores: 0,
    cotizado: 0,
});

const dataValuesConsult = reactive({
    fecha: null,
    colaboradores: 0,
    cotizado: 0,
});

function subirSua() {
    axios
        .post(
            route("dataEvolucionColab", { ...dataValues }),
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
                        label="Colaboradores"
                        id="pago"
                        onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                        :value="dataValuesConsult.colaboradores"
                        @input="(e) => (dataValues.colaboradores = e)"
                    />
                    <TextInputWhitLabel
                        label="Cotizado"
                        id="pagar"
                        onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                        :value="dataValuesConsult.cotizado"
                        @input="(e) => (dataValues.cotizado = e)"
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

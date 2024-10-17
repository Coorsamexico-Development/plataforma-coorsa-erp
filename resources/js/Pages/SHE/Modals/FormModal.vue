<script setup>
import { useForm } from "@inertiajs/inertia-vue3";
import { onMounted, ref, watch } from "vue";
import DialogModalPopUp from "@/Components/DialogModalPopUp.vue";
import TextInputShes from "../Partials/TextInputShes.vue";
import DatePicker from "@/Components/DatePicker.vue";
import moment from "moment";

const emit = defineEmits(["close"]);

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
    position: {
        type: Object,
        required: true,
    },
    rutaSeries: String,
    rutaAdd: String,
});

const close = () => {
    emit("close");
};

const sitios = ref([]);

const form = useForm({
    date: null,
});

async function getSitios() {
    const { data } = await axios.get(route(props.rutaSeries));
    data.forEach((sitio) => {
        form[sitio.name] = {
            porcentaje: null,
        };
    });
    sitios.value = data;
}

onMounted(() => getSitios());

function sendData() {
    axios
        .post(
            route(props.rutaAdd, { ...form }),
            { ...form },
            {
                onUploadProgress: () => (form.processing = true),
            }
        )
        .then(({ data }) => emit("close"))
        .catch((err) => console.log(err.response ?? err))
        .finally(() => {
            form.processing = false;
            form.reset();
        });
}
</script>
<template>
    <DialogModalPopUp
        :show="show"
        :max-width="maxWidth"
        :closeable="closeable"
        @close="close"
        :position="position"
    >
        <template #title>
            <span>Nueva Evaluacion de sitios</span>
        </template>
        <template #content>
            <div class="grid gap-2">
                <div class="flex items-center justify-end">
                    <DatePicker
                        class="w-fit"
                        label="Seleccione una fecha a cargar"
                        v-model="form.date"
                        @selectDate="(e) => (form.date = e)"
                        modelType="yyyy-MM"
                        :maxDate="null"
                    />
                </div>
                <div
                    class="grid grid-cols-2 gap-2 font-semibold text-[1.3rem] text-center"
                >
                    <div
                        class="flex items-center justify-center col-start-2 px-2 py-1 bg-[#F4F5F9] rounded-full"
                    >
                        Porcentaje de cumplimiento
                    </div>
                </div>
                <template v-for="(sitio, index) in sitios" :key="index">
                    <div
                        class="grid grid-cols-2 gap-2 font-semibold text-[1.2rem] text-center"
                    >
                        <div
                            class="flex items-center justify-center px-2 py-1 bg-[#F4F5F9] rounded-full"
                        >
                            {{ sitio.name }}
                        </div>
                        <TextInputShes v-model="form[sitio.name].porcentaje" />
                    </div>
                </template>
            </div>
        </template>
        <template #footer>
            <div class="flex justify-end w-full">
                <button
                    class="text-[1.3rem] px-12 py-1 bg-[#1d96f1] text-white rounded-2xl hover:bg-[#72bdf6] transition-all duration-200 enabled:active:scale-95 disabled:bg-[#d7d8db]"
                    @click="sendData()"
                    :disabled="form.processing"
                >
                    Subir
                </button>
            </div>
        </template>
    </DialogModalPopUp>
</template>

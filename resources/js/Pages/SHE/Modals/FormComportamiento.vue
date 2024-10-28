<script setup>
import { useForm } from "@inertiajs/inertia-vue3";
import { onMounted, reactive, ref, watch } from "vue";
import DialogModalPopUp from "@/Components/DialogModalPopUp.vue";
import TextInputShes from "../Partials/TextInputShes.vue";
import DatePicker from "@/Components/DatePicker.vue";
import moment from "moment";
import { pickBy, throttle } from "lodash";
import Add from "@/Iconos/Add.vue";

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
    date: {
        type: Object,
        required: true,
    },
    table: String,
    rutaAdd: String,
    channel: String,
});

const filters = pickBy({
    ...props.date,
});

const close = () => {
    emit("close");
    form.reset();
    cont.value = 0;
};

const sitios = ref([]);

const form = useForm({
    date: props.date.full,
    parametros: [],
    values: [],
});

const cont = ref(0);

async function getSitios() {
    const { data } = await axios
        .post(route("getSeries", { ...filters }))
        .catch((err) => console.log(err.response ?? err));

    console.log(data);
    data.forEach((sitio) => {
        form[sitio.name] = {
            ecologia: null,
            salud: null,
            seguridad: null,
            documental: null,
        };
    });
    sitios.value = data;
}

onMounted(() => getSitios());

function sendData() {
    axios
        .post(
            route(props.rutaAdd, { ...form, ...props, ...filters }),
            { ...form },
            {
                onUploadProgress: () => (form.processing = true),
            }
        )
        .then(({ data }) => {
            console.log(data);
            emit("close");
        })
        .catch((err) => console.log(err.response ?? err))
        .finally(() => {
            form.processing = false;
            form.reset();
        });
}

watch(
    filters,
    throttle(() => {
        getSitios();
    }),
    100
);

function selectDate(evt) {
    filters.full = evt;
    filters.month = evt.split("-")[0];
    filters.year = evt.split("-")[1];
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
            <span>Comportamiento</span>
        </template>
        <template #content>
            <div class="grid gap-3">
                <div class="flex items-center justify-end">
                    <DatePicker
                        class="w-fit"
                        label="Seleccione una fecha a cargar"
                        v-model="form.date"
                        @selectDate="(e) => selectDate(e)"
                        modelType="MM-yyyy"
                        :maxDate="null"
                    />
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <template v-for="i in cont" :key="i">
                        <TextInputShes
                            placeholder="Parametro"
                            v-model="form.parametros[i]"
                            keyPress="null"
                        />
                        <TextInputShes
                            placeholder="Valor"
                            v-model="form.values[i]"
                        />
                    </template>
                    <div class="col-span-2 place-self-center">
                        <Add
                            class="w-[2dvw] active:scale-95 hover:cursor-pointer transition-all duration-200"
                            @click="cont += 1"
                        />
                    </div>
                </div>
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

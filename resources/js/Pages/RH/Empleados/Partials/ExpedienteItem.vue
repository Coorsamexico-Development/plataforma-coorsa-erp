<script setup>
import { reactive, watch } from 'vue';
import InputLabel from '@/Components/InputLabel.vue';
import DropZone from '@/Components/DropZone.vue';
import InputError from '@/Components/InputError.vue';
import axios from 'axios';


const props = defineProps({
    expediente: {
        type: Object,
        required: true,
    },
    empleadoId: {
        type: Number | null,
        default: null,
    }
})

const form = reactive({
    file: [],
    successful: false,
    processing: false,
    progress: {
        percent: 0
    },
    errors: {},
    error: '',
    hasErrors: false
});


const loadFile = async (empleadoId = 0) => {
    if (form.file.length === 0) {
        return true;
    }
    form.processing = true;
    axios.post(route('empleado.expediente', empleadoId), {
        file: form.file,
        cat_tipo_documento_id: props.expediente.cat_tipo_documento_id
    }, {
        onUploadProgress: function (progressEvent) {

            const { loaded, total } = progressEvent;
            let percent = Math.floor((loaded * 100) / total);
            if (percent < 100) {
                console.log(`${loaded} bytes of ${total} bytes. ${percent}%`);
            }
            form.progress = { percent, loaded, total };
        },
        headers: {
            'Content-Type': 'multipart/form-data'
        }
    }).then(() => {
        form.file = [];
        form.error = ''
        form.hasErrors = false;
    }).catch((error) => {
        console.log(error);
        form.hasErrors = true;
        let message = 'Error to load File';
        if (error.hasOwnProperty('response') && error.response.hasOwnProperty('data')) {
            message = error.response.data.message
        }
        form.error = message
        return false
    }).then(() => {
        form.processing = false;
    });
    return true;
}

watch(form, () => {
    if (props.empleadoId !== null) {
        loadFile(props.empleadoId);
    };
}, {
    deep: true
});

defineExpose({
    loadFile
});


</script>
<template>
    <div class="w-64">

        <InputLabel :value="expediente.tipo_documento" />

        <DropZone v-model="form.file" accept="image/x-png,image/jpeg,application/pdf" />
        <div v-if="form.processing" max="100">
            <div class="flex items-center justify-center h-2 bg-blue-500 rounded-md"
                :style="{ width: form.progress.percent + '%' }">
                <span class=" text-white text-[6px]">
                    {{ form.progress.percent }}%
                </span>
            </div>
        </div>
        <InputError :message="form.error" class="mt-2" />
    </div>
</template>
<style lang="scss" scoped></style>
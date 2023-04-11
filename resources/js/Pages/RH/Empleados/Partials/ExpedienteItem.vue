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


const loadFile = async (empId = 0) => {
    if (form.file.length === 0) {
        return true;
    }
    form.processing = true;
    axios.post(route('empleado.expediente', empId), {
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
    }).then((resp) => {
        form.file = [];
        form.error = ''
        form.hasErrors = false;
        if (props.empleadoId !== null) {
            props.expediente.ruta = resp.data.ruta
        }
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
        <InputLabel :value="expediente.tipo_documento" class="h-10" />
        <DropZone v-model="form.file" accept="image/x-png,image/jpeg,application/pdf" :disabled="expediente.active === 0" />
        <div v-if="form.processing" max="100">
            <div class="flex items-center justify-center h-2 bg-blue-500 rounded-md"
                :style="{ width: form.progress.percent + '%' }">
                <span class=" text-white text-[6px]">
                    {{ form.progress.percent }}%
                </span>
            </div>
        </div>
        <div v-if="expediente.ruta !== null" class="text-green-500 cursor-pointer hover:opacity-80">
            <a data-fancybox="expedientes" :data-src="expediente.ruta">
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="inline w-5 h-5" viewBox="0 0 16 16">
                    <path
                        d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z" />
                    <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z" />
                </svg>
                Ver Archivo
            </a>
        </div>
        <InputError :message="form.error" class="mt-2" />
    </div>
</template>
<style lang="scss" scoped></style>
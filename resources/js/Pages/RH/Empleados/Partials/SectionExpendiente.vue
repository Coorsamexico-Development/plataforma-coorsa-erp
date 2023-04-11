<script setup>
import { ref } from 'vue'
import ExpedienteItem from './ExpedienteItem.vue'
import InputLabel from '@/Components/InputLabel.vue'
import DropZone from '@/Components/DropZone.vue'
import InputError from '@/Components/InputError.vue'

const props = defineProps({

    empleadoId: {
        default: null
    },
    expedientes: {
        type: Object,
        required: true,
    },
    typeForm: {
        type: String,
        default: 'create'
    },
    editEmpleadoDisable: {
        default: true
    },
    form: {
        type: Object,
        required: true,
    }
})

const itemRefs = ref([])

const uploadExpedientes = async (emId) => {

    try {
        const expedientes = []
        itemRefs.value.forEach(expedienteItemment => {
            expedientes.push(expedienteItemment.loadFile(emId))
        });
        const resps = await Promise.all(expedientes);


    } catch (error) {
        console.log(error)
    }

}
defineExpose({
    uploadExpedientes
})


</script>
<template>
    <div class="border-b tab">
        <div class="relative border-l-2 border-transparent">
            <input checked class="absolute z-10 w-full h-10 opacity-0 cursor-pointer top-6" type="checkbox"
                id="datosPersonales">
            <header class="flex items-center justify-between p-5 pl-8 pr-8 cursor-pointer select-none tab-label"
                for="datosPersonales">
                <span class="text-xl font-thin text-grey-darkest">
                    Archivos Expedientes
                </span>
                <div class="flex items-center justify-center border rounded-full border-grey w-7 h-7 test">
                    <svg aria-hidden="true" class="" data-reactid="266" fill="none" height="24" stroke="#606F7B"
                        stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewbox="0 0 24 24" width="24"
                        xmlns="http://www.w3.org/2000/svg">
                        <polyline points="6 9 12 15 18 9">
                        </polyline>
                    </svg>
                </div>
            </header>
            <div class="tab-content">
                <div class="pb-5 pl-8 pr-8">
                    <div class="grid grid-cols-3 gap-4">
                        <div class="mt-4">
                            <InputLabel for="fotografia" value="Foto:" />
                            <DropZone id="fotografia" v-model="form.fotografia" ref="fotografia"
                                accept="image/x-png,image/jpeg" />
                            <div v-if="form.fotografia_url" class="text-green-500 cursor-pointer hover:opacity-80">
                                <a data-fancybox="fotografia" :data-src="form.fotografia_url">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="inline w-5 h-5"
                                        viewBox="0 0 16 16">
                                        <path
                                            d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z" />
                                        <path
                                            d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z" />
                                    </svg>
                                    Ver Archivo
                                </a>
                            </div>
                            <InputError :message="form.errors.fotografia" class="mt-2" />
                        </div>
                        <div class="mt-4">
                            <InputLabel for="foto_empresarial" value="Foto Empresarial:" />
                            <DropZone id="foto_empresarial" v-model="form.foto_empresarial" ref="fotografia_empresarial"
                                accept="image/x-png,image/jpeg" />
                        </div>
                        <ExpedienteItem v-for="expediente in expedientes" :key="expediente.id" ref="itemRefs"
                            :expediente="expediente" :empleado-id="props.empleadoId" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
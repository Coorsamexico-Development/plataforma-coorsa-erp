<script setup>
import { ref, onMounted } from 'vue'
import ExpedienteItem from './ExpedienteItem.vue'

const props = defineProps({
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
    }
})

const itemRefs = ref([])

const uploadExpedientes = async (empleadoId) => {

    try {
        const expedientes = []
        itemRefs.value.forEach(expedienteItemment => {
            expedientes.push(expedienteItemment.loadFile(empleadoId))
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
                        <ExpedienteItem v-for="expediente in expedientes" :key="expediente.id" ref="itemRefs"
                            :expediente="expediente" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
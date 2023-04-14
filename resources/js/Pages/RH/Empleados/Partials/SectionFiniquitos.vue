
<script setup>
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import Select from '@/Components/Select.vue';


const props = defineProps({
    form: {
        type: Object,
        required: true
    },
    motivosBajas: {
        type: Object,
        required: true
    },
});


</script>
<template>
    <div class="border-b tab">
        <div class="relative border-l-2 border-transparent">
            <input checked class="absolute z-10 w-full h-10 opacity-0 cursor-pointer top-6" type="checkbox"
                id="datosPersonales">
            <header class="flex items-center justify-between p-5 pl-8 pr-8 cursor-pointer select-none tab-label"
                for="datosPersonales">
                <span class="text-xl font-thin text-grey-darkest">
                    Finiquitos
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
                            <InputLabel for="cat_bajas_empleado_id" value="Motivo de Baja:*" />
                            <Select v-model="props.form.cat_bajas_empleado_id" class="w-full">
                                <option v-for="motivo in props.motivosBajas" :key="motivo.id" :value="motivo.id">
                                    {{ motivo.nombre }}
                                </option>
                            </Select>
                            <InputError :message="props.form.errors.cat_bajas_empleado_id" class="mt-2" />
                        </div>
                        <div class="mt-4">
                            <InputLabel for="fecha_baja" value="Fecha de Baja:" />
                            <TextInput id="fecha_baja" type="date" max="2030-12-31" v-model="props.form.fecha_baja"
                                class="block w-full mt-1" />
                            <InputError :message="props.form.errors.fecha_baja" class="mt-2" />
                        </div>
                        <div class="mt-4">
                            <InputLabel for="fecha_finiquito" value="Fecha de Finiquito:*" />
                            <TextInput id="fecha_finiquito" type="date" max="2030-12-31"
                                v-model="props.form.fecha_finiquito" class="block w-full mt-1" />
                            <InputError :message="props.form.errors.fecha_finiquito" class="mt-2" />
                        </div>
                        <div class="mt-4">
                            <InputLabel for="monto_finiquito" value="Monto de Finiquito:*" />
                            <TextInput id="monto_finiquito" type="text" placeholder="$0.0"
                                v-model="props.form.monto_finiquito" class="block w-full mt-1" max="99999" />
                            <InputError :message="props.form.errors.monto_finiquito" class="mt-2" />
                        </div>
                        <div class="mt-4">
                            <InputLabel for="finiquito_pagado" value="Pagado:*" />
                            <Select v-model="props.form.finiquito_pagado" class="w-full">
                                <option value="0">NO</option>
                                <option value="1">SI</option>
                            </Select>
                            <InputError :message="props.form.errors.finiquito_pagado" class="mt-2" />
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>
</template>

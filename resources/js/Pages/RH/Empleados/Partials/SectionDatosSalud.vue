<script setup>
import InputLabel from '@/Components/InputLabel.vue';
import Select from '@/Components/Select.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';

const props = defineProps({
    form: {
        type: Object,
        required: true
    },
    tiposSangres: {
        type: Array,
        required: true
    },
});

</script>
<template>
    <div class="border-b tab">
        <div class="relative border-l-2 border-transparent">
            <input checked class="absolute z-10 w-full h-10 opacity-0 cursor-pointer top-6" type="checkbox"
                id="datosGenerales">
            <header class="flex items-center justify-between p-5 pl-8 pr-8 cursor-pointer select-none tab-label"
                for="datosGenerales">
                <span class="text-xl font-thin text-grey-darkest">
                    Aspectos Generales de Salud
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
                            <InputLabel for="cat_tipos_sangre_id" value="Tipo de Sangre:*" />
                            <Select v-model="props.form.cat_tipos_sangre_id" class="w-full">
                                <option v-for="sangre in props.tiposSangres" :key="sangre.id" :value="sangre.id">
                                    {{ sangre.nombre }}
                                </option>
                            </Select>
                            <InputError :message="props.form.errors.cat_tipos_sangre_id" class="mt-2" />
                        </div>
                        <div class="mt-4">
                            <InputLabel for="alergias" value="Alergias:" />
                            <TextInput id="alergias" type="text" v-model="props.form.alergias" class="block w-full mt-1"
                                placeholder="alergia 1, alergia 2" />
                            <InputError :message="props.form.errors.alergias" class="mt-2" />
                        </div>
                        <div class="mt-4">
                            <InputLabel for="enfermedades_cronicas" value="Enfermedades Crónicas:" />
                            <TextInput id="enfermedades_cronicas" type="text" v-model="props.form.enfermedades_cronicas"
                                class="block w-full mt-1" placeholder="enfermedad 1, enfermedad 2" />
                            <InputError :message="props.form.errors.enfermedades_cronicas" class="mt-2" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
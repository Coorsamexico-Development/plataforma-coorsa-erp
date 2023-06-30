
<script setup>
import { ref, watchEffect, computed } from 'vue';
import InputLabel from '@/Components/InputLabel.vue';
import Select from '@/Components/Select.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import ListInputVue from '@/Components/ListInput.vue';
import { useForm, usePage } from '@inertiajs/inertia-vue3';

const props = defineProps({
    form: {
        type: Object,
        required: true
    },
    departamentos: {
        type: Object,
        required: true
    },
    tiposContratos: {
        type: Object,
        required: true
    },
});

const puestos = ref([])

/*OBTENCION DE PUESTOS*/
const getPuestos = () => {
    axios.get(route('departamento.puestos.list', props.form.departamento_id))
        .then((response) => {
            puestos.value = response.data
        }).catch(error => {
            if (error.response) {
                let messageError = '';
                const messageServer = error.response.data.message
                if (error.response.status != 500) {
                    messageError = messageServer;
                }
                else {
                    messageError = 'Internal Server Error';
                }
                swal('Error get Puesto Departamento', messageError, 'error')
            }
        });
}

const fecha_termino = computed(() => {
    let date;
    if (props.form.fecha_ingreso_real) {
        date = new Date(props.form.fecha_ingreso_real);
    } else {
        date = new Date();
    }

    date.setDate(date.getDate() + 30);
    let month = date.getMonth() + 1;
    if (month < 10) {
        month = '0' + month;
    }
    let day = date.getDate();
    if (day < 10) {
        day = '0' + day;
    }
    return date.getFullYear() + "-" + month + "-" + day;
});

watchEffect(() => {
    if (props.form.departamento_id !== -1 && props.form.departamento_id !== '') {
        getPuestos();
    }
});

const canEdit = computed(() => {
    return usePage().props.value.can['edit-users'];
})

</script>
<template>
    <div class="border-b tab">
        <div class="relative border-l-2 border-transparent">
            <input class="absolute z-10 w-full h-10 opacity-0 cursor-pointer top-6" type="checkbox" id="datosLaborales"
                checked>
            <header class="flex items-center justify-between p-5 pl-8 pr-8 cursor-pointer select-none tab-label"
                for="datosLaborales">
                <span class="text-xl font-thin text-grey-darkest">
                    Datos Laborales
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
                            <InputLabel for="departamento_id" value="Departamento:*" />
                            <ListInputVue  v-if="canEdit" v-model="props.form.departamento_id" list="departamentos" class="w-full"
                                :options="props.departamentos" />
                            <ListInputVue  v-else :disable="true" v-model="props.form.departamento_id" list="departamentos" class="w-full"
                            :options="props.departamentos" />

                            <InputError :message="props.form.errors.departamento_id" class="mt-2" />
                        </div>
                        <div class="mt-4">
                            <InputLabel for="puesto_id" value="Puesto:*" />
                            <Select v-if="canEdit" v-model="props.form.puesto_id" class="w-full">
                                <option v-for="puesto in puestos" :key="puesto.id" :value="puesto.id">
                                    {{ puesto.name }}
                                </option>
                            </Select>
                            <Select v-else :disabled="true" v-model="props.form.puesto_id" class="w-full">
                                <option v-for="puesto in puestos" :key="puesto.id" :value="puesto.id">
                                    {{ puesto.name }}
                                </option>
                            </Select>
                            <InputError :message="props.form.errors.puesto_id" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <InputLabel for="horario" value="Horario Laboral:*" />
                            <Select v-if="canEdit" v-model="props.form.horario" class="w-full">
                                <option disabled selected>Elige un Horario</option>
                                <option value="oficina">Oficina</option>
                                <option value="turnos">Rolar Turnos</option>
                            </Select>
                            <Select v-else :disabled="true" v-model="props.form.horario" class="w-full">
                                <option disabled selected>Elige un Horario</option>
                                <option value="oficina">Oficina</option>
                                <option value="turnos">Rolar Turnos</option>
                            </Select>
                            
                            <InputError :message="props.form.errors.horario" class="mt-2" />
                        </div>
                        <div class="mt-4">
                            <InputLabel for="nss" value="Número de Seguridad Social:*" />
                            <TextInput v-if="canEdit" id="nss" type="text" v-model="props.form.nss" class="block w-full mt-1"
                                placeholder="Número de Seguridad Social" />
                            <p v-else class="block w-full mt-1" >
                               {{ props.form.nss }}
                            </p>
                            <InputError :message="props.form.errors.nss" class="mt-2" />
                        </div>
                        <div class="mt-4">
                            <InputLabel for="fecha_ingreso" value="Fecha de Ingreso:*" />
                            <TextInput v-if="canEdit" id="fecha_ingreso" type="date" v-model="props.form.fecha_ingreso"
                                class="block w-full mt-1" placeholder="Fecha de Ingreso" max="2030-12-12" />
                            <p v-else class="block w-full mt-1" >
                               {{ props.form.fecha_ingreso }}
                            </p>
                            <InputError :message="props.form.errors.fecha_ingreso" class="mt-2" />
                        </div>
                        <div class="mt-4">
                            <InputLabel for="fecha_ingreso_real" value="Fecha Real:*" />
                            <TextInput v-if="canEdit" id="fecha_ingreso_real" type="date" v-model="props.form.fecha_ingreso_real"
                                class="block w-full mt-1" placeholder="Fecha Real" max="2030-12-12" />
                            <p v-else class="block w-full mt-1" >
                               {{ props.form.fecha_ingreso_real }}
                            </p>
                            <InputError :message="props.form.errors.fecha_ingreso_real" class="mt-2" />
                        </div>
                        <div class="mt-4">
                            <InputLabel for="cat_tipos_nomina_id" value="Tipo de Nomina:*" />
                            <Select  v-if="canEdit"  v-model="props.form.cat_tipos_nomina_id" class="w-full">
                                <option disabled selected>Elige un tipo de nomina</option>
                                <option value="1">Semanal</option>
                                <option value="2">Quincenal</option>
                            </Select>
                            <Select  v-else :disabled="true"  v-model="props.form.cat_tipos_nomina_id" class="w-full">
                                <option disabled selected>Elige un tipo de nomina</option>
                                <option value="1">Semanal</option>
                                <option value="2">Quincenal</option>
                            </Select>
                            <InputError :message="props.form.errors.cat_tipos_nomina_id" class="mt-2" />
                        </div>
                        <div class="mt-4">
                            <InputLabel for="tipos_contrato_id" value="Tipo de Contrato:*" />
                            <Select v-if="canEdit" v-model="props.form.tipos_contrato_id" class="w-full">
                                <option v-for="tipo_contrato in props.tiposContratos" :key="tipo_contrato.id"
                                    :value="tipo_contrato.id">
                                    {{ tipo_contrato.nombre }}
                                </option>
                            </Select>
                            <Select v-else :disable="true" v-model="props.form.tipos_contrato_id" class="w-full">
                                <option v-for="tipo_contrato in props.tiposContratos" :key="tipo_contrato.id"
                                    :value="tipo_contrato.id">
                                    {{ tipo_contrato.nombre }}
                                </option>
                            </Select>
                            
                            <InputError :message="props.form.errors.tipos_contrato_id" class="mt-2" />
                        </div>
                        <div v-if="props.form.tipos_contrato_id == 1 && canEdit" class="mt-4">
                            <InputLabel for="fecha_termino" value="Fecha de Termino:" />
                            <TextInput v-if="canEdit" id="fecha_termino" type="date" v-model="fecha_termino" class="w-full " disabled />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

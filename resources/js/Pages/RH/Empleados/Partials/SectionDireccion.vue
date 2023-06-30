<script setup>
import { ref, watchEffect } from 'vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import ListInputVue from '../../../../Components/ListInput.vue';
import swal from 'sweetalert';
import { useForm, usePage } from '@inertiajs/inertia-vue3';
import { computed } from 'vue';


const props = defineProps({
    form: {
        type: Object,
        required: true
    },
    estados: {
        type: Object,
        required: true
    },
});


const municipiosDireccion = ref([]);
const localidadesDireccion = ref([]);

const getMunicipios = () => {
    if (props.form.direccion_estado_id !== '' && props.form.direccion_estado_id > 0) {
        axios.get(route('municipos.estado', props.form.direccion_estado_id)).
            then((response) => {
                municipiosDireccion.value = response.data;
            }).catch(error => {
                if (error.response) {
                    let messageError = '';
                    const messageServer = error.response.data.message
                    if (error.response.status != 500) {
                        messageError = messageServer;
                    } else {
                        messageError = 'Internal Server Error';
                    }
                    console.log(error.response);
                    swal("Error get Municipios", messageError, "error");
                }
            });
    }
}

const getLocalidades = () => {
    if (props.form.direccion_municipio_id !== '' && props.form.direccion_municipio_id > 0) {
        axios.get(route('localidades.municipio', props.form.direccion_municipio_id)).
            then((response) => {
                localidadesDireccion.value = response.data;
            }).catch(error => {
                if (error.response) {
                    let messageError = '';
                    const messageServer = error.response.data.message
                    if (error.response.status != 500) {
                        messageError = messageServer;
                    } else {
                        messageError = 'Internal Server Error';
                    }
                    console.log(error.response);
                    swal("Error get Localidades", messageError, "error");
                }
            });
    }
}

watchEffect(() => {
    if (props.form.direccion_estado_id !== -1 && props.form.direccion_estado_id !== '') {
        getMunicipios();
    }
    if (props.form.direccion_municipio_id !== -1 && props.form.direccion_municipio_id !== '') {
        getLocalidades();
    }
});


const canEdit = computed(() => {
    return usePage().props.value.can['edit-users'];
})


</script>
<template>
    <div class="border-b tab">
        <div class="relative border-l-2 border-transparent">
            <input class="absolute z-10 w-full h-10 opacity-0 cursor-pointer top-6" type="checkbox" id="datosDirection"
                checked>
            <header class="flex items-center justify-between p-5 pl-8 pr-8 cursor-pointer select-none tab-label"
                for="datosDirection">
                <span class="text-xl font-thin text-grey-darkest">
                    Dirección del Empleado
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
                            <InputLabel for="calle" value="Calle:*" />
                            <TextInput v-if="canEdit" id="calle" type="text" v-model="props.form.calle" class="block w-full mt-1"
                                placeholder="Calle" required />
                            <p v-else class="block w-full mt-1" >
                               {{ props.form.calle }}
                            </p>
                            <InputError :message="props.form.errors.calle" class="mt-2" />
                        </div>
                        <div class="mt-4">
                            <InputLabel for="numero" value="Colonia:*" />
                            <TextInput v-if="canEdit" id="numero" type="text" v-model="props.form.numero" class="block w-full mt-1"
                                placeholder="Nombre" required />
                            <p v-else class="block w-full mt-1" >
                               {{ props.form.numero }}
                            </p>
                            <InputError :message="props.form.errors.numero" class="mt-2" />
                        </div>
                        <div class="mt-4">
                            <InputLabel for="colonia" value="Número:*" />
                            <TextInput v-if="canEdit" id="colonia" type="text" v-model="props.form.colonia" class="block w-full mt-1"
                                placeholder="Número" required />
                            <p v-else class="block w-full mt-1" >
                               {{ props.form.colonia }}
                            </p>
                            <InputError :message="props.form.errors.colonia" class="mt-2" />
                        </div>
                        <div class="mt-4">
                            <InputLabel for="codigo_postal" value="CP:*" />
                            <TextInput v-if="canEdit" id="codigo_postal" type="number" maxlength="5" v-model="props.form.codigo_postal"
                                class="block w-full mt-1" placeholder="Codigo Postal" required />
                            <p v-else class="block w-full mt-1" >
                               {{ props.form.codigo_postal }}
                            </p>
                            <InputError :message="props.form.errors.codigo_postal" class="mt-2" />
                        </div>
                        <div class="mt-4">
                            <InputLabel for="lote" value="Lote:*" />
                            <TextInput v-if="canEdit" id="lote" type="text" v-model="props.form.lote" class="block w-full mt-1"
                                placeholder="Lote" required />
                            <p v-else class="block w-full mt-1" >
                               {{ props.form.lote }}
                            </p>
                            <InputError :message="props.form.errors.lote" class="mt-2" />
                        </div>
                        <div class="mt-4">
                            <InputLabel for="manzana" value="Manzana:*" />
                            <TextInput v-if="canEdit" id="manzana" type="text" v-model="props.form.manzana" class="block w-full mt-1"
                                placeholder="Manzana" required />
                            <p v-else class="block w-full mt-1" >
                               {{ props.form.manzana }}
                            </p>
                            <InputError :message="props.form.errors.manzana" class="mt-2" />
                        </div>

                        <!-- ----------------------------------------------------------------- -->
                        <div class="mt-4">
                            <InputLabel for="direccion_estado_id" value="Estado:*" />
                            <ListInputVue  v-if="canEdit"  list="listaEstados" v-model="props.form.direccion_estado_id"
                                :options="props.estados" class="block w-full mt-1" />
                            <ListInputVue  v-else :disable="true"  list="listaEstados" v-model="props.form.direccion_estado_id"
                                :options="props.estados" class="block w-full mt-1" />
                            <InputError :message="props.form.errors.direccion_estado_id" class="mt-2" />
                        </div>
                        <div class="mt-4">
                            <InputLabel for="direccion_municipio_id" value="Municipio:*" />
                            <ListInputVue  v-if="props.form.direccion_estado_id != 0 && canEdit" list="listaMunicipios"
                                :disabled="false" v-model="props.form.direccion_municipio_id" :options="municipiosDireccion"
                                class="block w-full mt-1" />
                            <ListInputVue v-if="props.form.direccion_estado_id == 0" list="listaMunicipios"
                                :disabled="true" />
                            <InputError :message="props.form.errors.direccion_municipio_id" class="mt-2" />
                        </div>
                        <div class="mt-4">
                            <InputLabel for="direccion_localidade_id" value="Localidad:*" />
                            <ListInputVue list="listaLocalidades" v-if="props.form.direccion_municipio_id != 0 && canEdit"
                                :disabled="false" v-model="props.form.direccion_localidade_id"
                                :options="localidadesDireccion" class="block w-full mt-1" />
                            <ListInputVue v-if="props.form.direccion_municipio_id == 0" :disabled="true" />
                            <InputError :message="props.form.errors.direccion_localidade_id" class="mt-2" />
                        </div>
                        <!-- ----------------------------------------------------------------- -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

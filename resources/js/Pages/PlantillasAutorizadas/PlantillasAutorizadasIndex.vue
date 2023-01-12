<script setup>
import { reactive, watch, computed } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { useForm } from "@inertiajs/inertia-vue3";
import { pickBy, throttle } from 'lodash';
import AppLayout from '@/Layouts/AppLayout.vue';
import DataTable from '../../Components/DataTable.vue';
import Title from '@/Components/Title.vue';
import InputSearch from '@/Components/InputSearch.vue';
import PlantillaAutorizadaRow from './Partials/PlantillaAutorizadaRow.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SpinProgress from '@/Components/SpinProgress.vue';
import PlantillaHeader from './Partials/PlantillaHeader.vue';
import PlantillaHeaderEdit from './Partials/PlantillaHeaderEdit.vue';
import PlantillaAutorizadaRowEdit from './Partials/PlantillaAutorizadaRowEdit.vue';

var props = defineProps({
    puestos: {
        type: Object,
        required: true,
    },
    ubicaciones: {
        type: Object,
        required: true,
    },
    filters: {
        type: Object,
        required: true
    },
});

const params = reactive({
    search: props.filters.search,
    field: props.filters.field,
    direction: props.filters.direction,
});

const form = useForm({
    'name': "",
});

const createUbicacion = async () => {
    if (document.getElementById("formUbicacion").reportValidity()) {
        form.post(route('ubicaciones.store'), {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                form.reset()
            }
        });

    }
}

const updateUbicacion = async (ubicacion) => {
    Inertia.put(route('ubicaciones.update', ubicacion.id), ubicacion, {
        preserveScroll: true,
        preserveState: true,
        only: ['ubicaciones', 'errors'],
    });
}

const sort = (field) => {
    params.field = field;
    params.direction = params.direction === "asc" ? "desc" : "asc";
};


const storePlantilla = (data) => {
    Inertia.post(route('rh.plantillas-autorizadas.store'), data, {
        preserveScroll: true,
        preserveState: true,
        only: ['ubicaciones', 'errors'],
        onError: (error) => {
            console.log(error)
            alert("ERROR TO CREATE PLANTILLA")
        }
    });
}

const updatePlantilla = (data) => {
    Inertia.put(route('rh.plantillas-autorizadas.update', data.id), data, {
        preserveScroll: true,
        preserveState: true,
        only: ['ubicaciones', 'errors'],
        onError: (error) => {
            console.log(error)
            alert("ERROR TO UPDATE PLANTILLA")
        }
    });
}


const savePlantillaAutorizada = (data) => {
    if (data.id === -1) {
        storePlantilla(data)
    } else {
        updatePlantilla(data)
    }
}





watch(params, throttle(function () {
    let paramsClear = pickBy(params);

    Inertia.get(route("rh.plantillas-autorizadas.index"), paramsClear, {
        replace: true,
        only: ['puestos'],
        preserveScroll: true,
        preserveState: true,
    });

}, 150));

const totalGlobal = computed(() => {
    let totalActivos = 0
    let totalRequeridos = 0
    props.ubicaciones.forEach((ubicacion) => {
        ubicacion.plantillas_autorizadas.forEach(plantilla => {
            totalActivos += plantilla.cantidad_activa;
            totalRequeridos += plantilla.cantidad;
        });
    });

    return totalActivos + '/' + totalRequeridos
})



</script>
<template>
    <AppLayout title="Plantillas">
        <template #header>
            <Title> Plantillas Autorizadas</Title>
        </template>
        <div class="py-6">
            <div class="mx-auto sm:px-3 lg:px-8 ">
                <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg">
                    <DataTable>
                        <template #section-header>
                            <div class="w-full px-2 py-2 overflow-hidden bg-white shadow sm:rounded-lg">
                                <div class="flex justify-between gap-2">
                                    <div>
                                        <InputSearch type="search" v-model="params.search" aria-label="Search" />
                                    </div>
                                    <div>
                                        Total Global:<span class="px-1 text-white bg-blue-600 rounded-md">{{
                                            totalGlobal
                                        }}
                                        </span>
                                    </div>
                                    <div v-if="$page.props.can['ubicaciones.create']">
                                        <form id="formUbicacion" class="flex font-normal"
                                            @submit.prevent="createUbicacion()">
                                            <TextInput id="name-ubicacion" type="text" v-model="form.name"
                                                class="block w-40 p-0" placeholder=" Nueva Ubicacion" required
                                                maxlength="60" />
                                            <primary-button :disabled="form.processing">
                                                <SpinProgress v-if="form.processing" :inprogress="form.processing" />
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                                                    <path
                                                        d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
                                                </svg>
                                            </primary-button>
                                        </form>
                                        <InputError :message="form.errors.name" class="mt-2" />
                                    </div>
                                </div>
                            </div>
                        </template>
                        <template #table-header>
                            <tr class="text-center">
                                <th scope="col"
                                    class="px-2 py-1 text-xs font-semibold tracking-wider uppercase cursor-pointer "
                                    @click="sort('puestos.name')">
                                    PUESTOS
                                    <template v-if="params.field === 'puestos.name'">
                                        <svg v-if="params.direction === 'asc'" xmlns="http://www.w3.org/2000/svg"
                                            class="inline w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                                            <path
                                                d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z" />
                                        </svg>
                                        <svg v-if="params.direction === 'desc'" xmlns="http://www.w3.org/2000/svg"
                                            class="inline w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                                            <path
                                                d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z" />
                                        </svg>
                                    </template>
                                </th>
                                <template v-if="$page.props.can['ubicaciones.update']">
                                    <PlantillaHeaderEdit v-for="ubicacion in ubicaciones" :key="ubicacion.id"
                                        :ubicacion="ubicacion" @update-ubicacion="updateUbicacion($event)" />
                                </template>
                                <template v-else>
                                    <PlantillaHeader v-for="ubicacion in ubicaciones" :key="ubicacion.id"
                                        :ubicacion="ubicacion" />
                                </template>


                            </tr>
                        </template>
                        <template #table-body>
                            <template v-if="$page.props.can['plantilla-autorizada.update']">
                                <PlantillaAutorizadaRowEdit v-for="puesto in puestos" :key="'p' + puesto.id"
                                    :ubicaciones="ubicaciones" :puesto="puesto"
                                    @save="savePlantillaAutorizada($event)" />
                            </template>
                            <template v-else>
                                <PlantillaAutorizadaRow v-for="puesto in puestos" :key="'p' + puesto.id"
                                    :ubicaciones="ubicaciones" :puesto="puesto" />
                            </template>

                        </template>
                    </DataTable>

                </div>
            </div>
        </div>
    </AppLayout>
</template>

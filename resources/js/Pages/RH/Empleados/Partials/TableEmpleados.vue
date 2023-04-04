<script setup>
import { computed, reactive, watch } from 'vue';
import DataTable from '@/Components/DataTable.vue';
import ShortInput from '@/Components/ShortInput.vue';
import ButtonInfo from '@/Components/Buttoninfo.vue';
import ButtonPhoto from '@/Components/ButtonPhoto.vue';
import ButtonPDF from '@/Components/ButtonPDF.vue';
import ButtonContrato from '@/Components/ButtonContrato.vue';
import { Link, usePage } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';
import { throttle, pickBy } from 'lodash'

import { Fancybox } from "@fancyapps/ui";
import "@fancyapps/ui/dist/fancybox.css";

var props = defineProps(
    {
        empleados: Object,
        permission: String,
        activo: String,
        filters: {
            type: Object,
            required: true,
        }
    }
);

const params = reactive({
    searchs: {
        numero_empleado: '',
        'cecos.nombre': '',
        'puestos.name': '',
        'users.name': '',
        apellido_paterno: '',
        apellido_materno: '',
        ...props.filters.searchs
    },
    fields: props.filters.fields
})



const canEdit = computed(() => {
    return usePage().props.value.can[props.permission + '.update'];
})


const sort = (field) => {
    if (params.fields === null) {
        params.fields = {};// para que no falle hasOwnProperty
    }
    if (params.fields.hasOwnProperty(field)) {
        params.fields[field] = params.fields[field] === 'asc' ? 'desc' : 'asc';
    } else {
        params.fields[field] = 'asc';
    }

}

watch(params, throttle((newParams) => {

    const searchs = pickBy(newParams.searchs)
    const clearParams = pickBy({ fields: newParams.fields, searchs: Object.keys(searchs).length === 0 ? null : searchs });
    Inertia.visit(route("empleado.indexmanual", { activo: props.activo }), {
        data: clearParams,
        replace: true,
        only: ['empleados', 'errors'],
        preserveScroll: true,
        preserveState: true,
    });

}, 150), {
    deep: true
})


</script>


<template>
    <DataTable>
        <template #table-header>
            <tr class="text-center">
                <th scope="col" class="w-1/12 px-6 py-3 text-xs font-semibold tracking-wider uppercase cursor-pointer ">
                    <span class="">
                        EXPEDIENTE
                    </span>
                </th>
                <th scope="col" class="w-1/12 px-6 py-3 text-xs font-semibold tracking-wider uppercase cursor-pointer ">
                    <span class="block my-1" @click="sort('numero_empleado')">
                        NO. EMPLEADO
                        <template v-if="params.fields && params.fields['numero_empleado']">
                            <svg v-if="params.fields['numero_empleado'] === 'asc'" xmlns="http://www.w3.org/2000/svg"
                                class="inline w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                                <path
                                    d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z" />
                            </svg>
                            <svg v-else xmlns="http://www.w3.org/2000/svg" class="inline w-4 h-4" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path
                                    d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z" />
                            </svg>
                        </template>
                    </span>
                    <ShortInput type="search" v-model="params.searchs.numero_empleado" />
                </th>
                <th scope="col" class="w-1/12 px-6 py-3 text-xs font-semibold tracking-wider uppercase cursor-pointer ">
                    <span class="block my-1" @click="sort('cecos.nombre')">
                        DEPARTAMENTO
                        <template v-if="params.fields && params.fields['cecos.nombre']">
                            <svg v-if="params.fields['cecos.nombre'] === 'asc'" xmlns="http://www.w3.org/2000/svg"
                                class="inline w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                                <path
                                    d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z" />
                            </svg>
                            <svg v-else xmlns="http://www.w3.org/2000/svg" class="inline w-4 h-4" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path
                                    d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z" />
                            </svg>
                        </template>
                    </span>
                    <ShortInput type="search" v-model="params.searchs['cecos.nombre']" />
                </th>
                <th scope="col" class="w-1/12 px-6 py-3 text-xs font-semibold tracking-wider uppercase cursor-pointer ">
                    <span class="block my-1" @click="sort('puestos.name')">
                        PUESTO
                        <template v-if="params.fields && params.fields['puestos.name']">
                            <svg v-if="params.fields['puestos.name'] === 'asc'" xmlns="http://www.w3.org/2000/svg"
                                class="inline w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                                <path
                                    d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z" />
                            </svg>
                            <svg v-else xmlns="http://www.w3.org/2000/svg" class="inline w-4 h-4" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path
                                    d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z" />
                            </svg>
                        </template>
                    </span>
                    <ShortInput type="search" v-model="params.searchs['puestos.name']" />
                </th>
                <th scope="col" class="w-1/12 px-6 py-3 text-xs font-semibold tracking-wider uppercase cursor-pointer ">
                    <span class="block my-1" @click="sort('users.name')">
                        NOMBRE
                        <template v-if="params.fields && params.fields['users.name']">
                            <svg v-if="params.fields['users.name'] === 'asc'" xmlns="http://www.w3.org/2000/svg"
                                class="inline w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                                <path
                                    d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z" />
                            </svg>
                            <svg v-else xmlns="http://www.w3.org/2000/svg" class="inline w-4 h-4" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path
                                    d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z" />
                            </svg>
                        </template>
                    </span>
                    <ShortInput type="search" v-model="params.searchs['users.name']" />
                </th>
                <th scope="col" class="w-1/12 px-6 py-3 text-xs font-semibold tracking-wider uppercase cursor-pointer ">
                    <span class="block my-1" @click="sort('apellido_paterno')">
                        APELLIDO PATERNO
                        <template v-if="params.fields && params.fields['apellido_paterno']">
                            <svg v-if="params.fields['apellido_paterno'] === 'asc'" xmlns="http://www.w3.org/2000/svg"
                                class="inline w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                                <path
                                    d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z" />
                            </svg>
                            <svg v-else xmlns="http://www.w3.org/2000/svg" class="inline w-4 h-4" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path
                                    d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z" />
                            </svg>
                        </template>
                    </span>
                    <ShortInput type="search" v-model="params.searchs.apellido_paterno" />
                </th>
                <th scope="col" class="w-1/12 px-6 py-3 text-xs font-semibold tracking-wider uppercase cursor-pointer ">
                    <span class="block my-1" @click="sort('apellido_materno')">
                        APELLIDO MATERNO
                        <template v-if="params.fields && params.fields['apellido_materno']">
                            <svg v-if="params.fields['apellido_materno'] === 'asc'" xmlns="http://www.w3.org/2000/svg"
                                class="inline w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                                <path
                                    d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z" />
                            </svg>
                            <svg v-else xmlns="http://www.w3.org/2000/svg" class="inline w-4 h-4" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path
                                    d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z" />
                            </svg>
                        </template>
                    </span>

                    <ShortInput type="search" v-model="params.searchs.apellido_materno" />
                </th>
                <th scope="col" class="w-1/12 px-6 py-3 text-xs font-semibold tracking-wider uppercase cursor-pointer ">
                    <span class="">
                        TELÉFONO
                    </span>
                </th>
                <th scope="col" class="w-1/12 px-6 py-3 text-xs font-semibold tracking-wider uppercase cursor-pointer ">
                    <span class="">
                        ACCIONES
                    </span>
                </th>
            </tr>
        </template>
        <template #table-body>
            <tr v-for="empleado in empleados" :key="empleado.id">
                <td class="px-2 whitespace-nowrap">
                    <div class="buttons_table">
                        <ButtonPhoto style="width:3rem; justify-content: center; margin:0.5rem" v-if="empleado.fotografia"
                            :data-fancybox="'single' + empleado.id" :data-src="empleado.fotografia">
                            <svg id="Capa_1" data-name="Capa 1" xmlns="http://www.w3.org/2000/svg"
                                style="color:white; fill:white;" viewBox="0 0 15.5 15">
                                <g id="Grupo_48" data-name="Grupo 48">
                                    <path id="Trazado_55" data-name="Trazado 55" class="cls-1"
                                        d="M4.37,8.26c1.88,1.76,4.81,1.75,6.69,0,.17,.1,.34,.2,.51,.31,1.97,1.28,3.17,3.46,3.19,5.81,.04,.3-.16,.58-.46,.62-.05,0-.1,0-.15,0H1.27c-.3,.04-.57-.18-.61-.47,0-.04,0-.09,0-.13,0-2.51,1.37-4.83,3.56-6.06,.03-.02,.07-.04,.1-.06,.01,0,.03-.01,.04-.01Z" />
                                    <path id="Trazado_56" data-name="Trazado 56" class="cls-1"
                                        d="M7.7,.9c2.09,0,3.79,1.69,3.8,3.78-.04,2.08-1.72,3.77-3.8,3.81-2.09,0-3.78-1.7-3.79-3.79,0-2.09,1.7-3.79,3.79-3.8Z" />
                                </g>
                            </svg>
                        </ButtonPhoto>
                        <ButtonPDF v-if="empleado.expedienteGeneral"
                            style="width:3rem; justify-content: center; margin:0.5rem;"
                            :data-fancybox="'expediente' + empleado.id" :data-src="empleado.expedienteGeneral.ruta"
                            data-type="pdf" data-caption="Expediente">
                            <svg id="Capa_1" data-name="Capa 1" xmlns="http://www.w3.org/2000/svg"
                                style="color:white; fill:white;" viewBox="0 0 15.5 18">
                                <g id="Grupo_33" data-name="Grupo 33">
                                    <path id="Trazado_50" data-name="Trazado 50" class="cls-1"
                                        d="M1.55,9.27V2.92c-.04-.8,.51-1.51,1.3-1.66,.14-.02,.27-.03,.41-.03h6.14c.12,0,.24,.05,.33,.14,1.52,1.52,3.04,3.04,4.56,4.56,.09,.08,.14,.19,.13,.31,0,3.12,0,6.24,0,9.37,.05,.81-.51,1.52-1.3,1.68-.14,.02-.27,.03-.41,.03H3.27c-.89,.06-1.65-.61-1.72-1.49,0-.08,0-.16,0-.23,0-2.11,0-4.22,0-6.32Zm11.15-2.42l-3.89-3.89v3.89h3.89Zm-5.96,7.28c.42,0,.83,.03,1.23,0,.82,.04,1.53-.59,1.59-1.42,.2-.76-.26-1.54-1.02-1.74-.09-.02-.18-.04-.27-.04-.46-.02-.91-.02-1.37,0-.13,0-.15,.06-.15,.17v3.03Zm-2.05-1.11c.31-.03,.61-.09,.91-.16,.46-.14,.74-.6,.66-1.08-.02-.45-.37-.81-.82-.85-.43-.05-.87,0-1.31-.01-.15,0-.17,.06-.17,.2,0,.93,0,1.87,0,2.81,0,.06,0,.11,0,.17h.71v-1.08Zm5.38-2.12v3.2h.72v-1.29h1.14v-.59h-1.13v-.74h1.21v-.58c-.06,0-.11-.01-.16-.01h-1.78Z" />
                                    <path id="Trazado_51" data-name="Trazado 51" class="cls-1"
                                        d="M7.48,13.55v-2.05c.39-.13,.83-.03,1.12,.27,.26,.41,.28,.94,.05,1.37-.26,.37-.73,.54-1.17,.41Z" />
                                    <path id="Trazado_52" data-name="Trazado 52" class="cls-1"
                                        d="M4.7,11.48c.26-.11,.56-.06,.76,.14,.11,.17,.12,.39,.01,.57-.19,.22-.5,.3-.78,.2v-.91Z" />
                                </g>
                            </svg>
                        </ButtonPDF>
                        <ButtonContrato v-if="empleado.contrato"
                            style="width:3rem; justify-content: center; fill:white; margin:0.5rem"
                            :data-fancybox="'contrato' + empleado.id" :data-src="empleado.contrato.ruta" data-type="pdf"
                            data-caption="Contrato">
                            <svg id="Capa_1" data-name="Capa 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 15.5 18">
                                <path id="Trazado_65" data-name="Trazado 65" class="cls-1"
                                    d="M.58,9.01V4.27c-.04-1.62,1.11-3.02,2.66-3.24,.17-.02,.35-.04,.52-.03h6.27c.89-.02,1.74,.35,2.35,1.01,.4,.42,.8,.83,1.2,1.25,.6,.61,.93,1.45,.91,2.32V13.77c.03,1.64-1.14,3.04-2.71,3.24-.12,.02-.24,.02-.36,.02-2.59,0-5.18,.01-7.77,0-1.48,0-2.75-1.09-3.02-2.59-.04-.22-.06-.45-.06-.67,0-1.59,0-3.17,0-4.76Zm6.95-3.4h3.1c.31,.01,.57-.24,.58-.56,0,0,0-.02,0-.02,0-.33-.24-.6-.56-.61H4.59c-.07,0-.13,0-.2,0-.32,.04-.54,.34-.5,.67,.04,.31,.3,.54,.6,.52,1.01,0,2.03,0,3.04,0h0Zm0,4.01h3.08c.32,.02,.59-.23,.61-.56,0-.04,0-.08,0-.12-.04-.32-.31-.55-.62-.53H4.47c-.32-.01-.59,.25-.6,.58-.01,.33,.24,.61,.56,.62,.02,0,.04,0,.06,0,1.01,0,2.02,0,3.04,0h0Zm-1.53,2.81h-1.47c-.1,0-.19,0-.28,.04-.27,.1-.43,.38-.37,.67,.06,.3,.32,.51,.62,.49h2.98c.32,.03,.6-.21,.63-.55,.03-.33-.21-.63-.53-.66-.04,0-.07,0-.11,0-.49,0-.99,0-1.48,0h0Z" />
                            </svg>
                        </ButtonContrato>
                    </div>

                </td>

                <td class="px-2 whitespace-nowrap">{{ empleado.numero_empleado }}</td>
                <td class="px-2 whitespace-nowrap">{{ empleado.departamento }}</td>
                <td class="px-2 whitespace-nowrap">{{ empleado.puesto }}</td>
                <td class="px-2 whitespace-nowrap">{{ empleado.name }}</td>
                <td class="px-2 whitespace-nowrap">{{ empleado.apellido_paterno }}</td>
                <td class="px-2 whitespace-nowrap">{{ empleado.apellido_materno }}</td>
                <td class="px-2 whitespace-nowrap">{{ empleado.telefono }}</td>
                <td class="px-2 whitespace-nowrap">
                    <Link v-if="canEdit" :href="route('empleado.edit', empleado.id)">
                    <ButtonInfo class="w-6 h-6 px-1" style="width:3rem; align-items: center; justify-content: center;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="white" class="w-4 h-4" viewBox="0 0 16 16">
                            <path
                                d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                            <path fill-rule="evenodd"
                                d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z" />
                        </svg>
                    </ButtonInfo>
                    </Link>
                </td>
            </tr>
        </template>
    </DataTable>
</template>

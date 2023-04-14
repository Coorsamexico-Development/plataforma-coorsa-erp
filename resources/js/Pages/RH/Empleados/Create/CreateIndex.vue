<script setup>
import { ref, onBeforeMount } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm } from '@inertiajs/inertia-vue3';
import ButtonInfo from '@/Components/Buttoninfo.vue';
import ButtonAdd from '@/Components/ButtonAdd.vue';
import swal from 'sweetalert';
import SectionExpendiente from '../Partials/SectionExpendiente.vue';
import SectionDatosPersonales from '../Partials/SectionDatosPersonales.vue';
import SectionDireccion from '../Partials/SectionDireccion.vue';
import SectionDatosBancarios from '../Partials/SectionDatosBancarios.vue';
import SectionDatosLaborales from '../Partials/SectionDatosLaborales.vue';
import SectionDatosMonetarios from '../Partials/SectionDatosMonetarios.vue';
import SectionDatosSalud from '../Partials/SectionDatosSalud.vue';
import MenuSection from '../Partials/MenuSection.vue';

var props = defineProps(
    {
        escolaridades: Object,
        estados_civiles: Object,
        cat_tipo_sangre: Object,
        bancos: Object,
        departamentos: Object,
        tipos_contrato: Object,
        roles: Object,
        expedientes: Object,
        empleado_id: Number | null,
    }
);


let sectionExpediente = ref(null);
const estadosDireccion = ref([]);

onBeforeMount(async () => {
    try {
        const requestCatalogos = []
        requestCatalogos.push(axios.get(route('catalogos.estados')));
        const respCatalogos = await Promise.all(requestCatalogos);
        estadosDireccion.value = respCatalogos[0].data.estadosDireccion;
    } catch (error) {
        console.log(error)
        let messageError = 'Error get catalogos';
        if (error.response) {

            const messageServer = error.response.data.message
            if (error.response.status != 500) {
                messageError = messageServer;
            }
            else {
                messageError = 'Internal Server Error';
            }

        }
        swal('Error Catalogos', messageError, 'error')
    }
});



const form = useForm
    ({
        'numero_empleado': '',
        'banco_id': '',
        'escolaridade_id': '',
        'nombre': '',
        'apellido_paterno': '',
        'apellido_materno': '',
        'fecha_nacimiento': '',
        'fecha_ingreso': '',
        'fecha_ingreso_real': '',
        'fecha_baja': '',
        'departamento_id': '',
        'puesto_id': '',
        'nss': '0',
        'curp': '',
        'rfc': '',
        'contacto_emergencia': '',
        'telefono': '',
        'correo_electronico': '',
        'hijos': '0',
        'clave_bancaria': '',
        'numero_cuenta_bancaria': '',
        'direccion_estado_id': '',
        'direccion_municipio_id': '',
        'direccion_localidade_id': '',
        'calle': '',
        'numero': '',
        'colonia': '',
        'codigo_postal': '',
        'lote': '',
        'manzana': '',
        'cat_tipos_nomina_id': '',
        'tipos_contrato_id': '',
        'salario_bruto': 0.0,
        'salario_diario': 0.0,
        'salario_imss': 0.0,
        'bono_puntualidad': 0.0,
        'bono_asistencia': 0.0,
        'despensa': 1057.16,
        'fondo_ahorro': 0.0,
        'horario': '',
        'cat_estados_civile_id': '',
        'fotografia': null,
        'expediente': null,
        'contrato': null,
        'cat_tipos_sangre_id': '',
        'alergias': '',
        'enfermedades_cronicas': '',
        'cat_genero_id': '',
        'cat_bajas_empleado_id': '',
        'fecha_finiquito': '',
        'monto_finiquito': 0,
        'finiquito_pagado': false,
        'password': "12345678",
        'rol_id': '',
        /*Datos coorporativos*/
        'correo_empresarial': null,
        'foto_empresarial': null,
        'telefono_empresarial': null
    });

const createEmpleado = () => {
    form.post(route('empleado.store'),
        {
            preserveScroll: true,
            preserveState: true,
            onSuccess: async () => {
                form.reset();
                //los prop deberian cambiar
                await sectionExpediente.value.uploadExpedientes(props.empleado_id);
                props.empleado_id = null;
                swal("Create", "Exitosamente", "success");
            },
            onError: (error) => {
                console.log(error.response);
                swal("Error al crear", "Favor de validar los datos", "error");
            }
        });

}
/*CAMBIAR ELEMENTO*/

const menuSeleted = ref(1);

const cambiar = (id) => {
    menuSeleted.value = id;
}

</script>

<template>
    <app-layout title="Dashboard">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Nuevo Empleado
            </h2>
        </template>
        <form id="formCreateEmpleado" class="mx-4 md:mx-8">
            <!-- Section controller -->
            <MenuSection @changeMenu="cambiar($event)" :menu-seleted="menuSeleted" />
            <!-- Sections froms -->
            <div class="py-6">
                <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div class="px-4 py-2 bg-white shadow-xl sm:rounded-lg">
                        <!--  Datos Personales -->
                        <SectionDatosPersonales v-if="menuSeleted == 1" :form="form" :escolaridades="props.escolaridades"
                            :estados-civiles="props.estados_civiles" :roles="props.roles" />
                        <!-- Fin Datos Personales -->
                        <!-- Dirección del Empleado -->
                        <SectionDireccion v-if="menuSeleted == 2" :form="form" :estados="estadosDireccion" />
                        <!--Fin Dirección del Empleado -->
                        <!-- Datos Bancarios -->
                        <SectionDatosBancarios v-if="menuSeleted == 3" :form="form" :bancos="props.bancos" />
                        <!-- Fin  Datos Bancarios -->
                        <!-- Datos Laborales -->
                        <SectionDatosLaborales v-if="menuSeleted == 4" :form="form" :departamentos="props.departamentos"
                            :tipos-contratos="props.tipos_contrato" />
                        <!-- Fin Datos Laborales -->
                        <!-- Datos Monetarios -->
                        <SectionDatosMonetarios v-if="menuSeleted == 5" :form="form" />
                        <!-- Fin Datos Monetarios -->
                        <!-- Aspectos Generales de Salud -->
                        <SectionDatosSalud v-if="menuSeleted == 6" :form="form" :tiposSangres="props.cat_tipo_sangre" />
                        <!--Fin Aspectos Generales de Salud -->
                        <!-- Expediente no perder el ref -->
                        <div v-show="menuSeleted == 7">
                            <SectionExpendiente ref="sectionExpediente" :expedientes="props.expedientes" :form="form" />
                        </div>
                        <!--Fin Expediente -->
                        <!-- Error forms -->
                        <div class="flex justify-end gap-2">
                            <a :href="route('empleado.indexmanual', { activo: 'activo' })">
                                <ButtonInfo>
                                    Regresar
                                </ButtonInfo>
                            </a>
                            <ButtonAdd @click="createEmpleado">
                                Guardar
                            </ButtonAdd>
                        </div>
                    </div>
                </div>
            </div>

        </form>
    </app-layout>
</template>


<script setup>
import { onMounted, onBeforeMount, ref, computed } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { useForm, Link, usePage } from "@inertiajs/inertia-vue3";

import ButtonSeccion from "@/Components/ButtonSeccion.vue";
import ButtonInfo from "@/Components/Buttoninfo.vue";
import ButtonAdd from "@/Components/ButtonAdd.vue";
import swal from "sweetalert";
import SectionExpendiente from "../Partials/SectionExpendiente.vue";

import MenuSection from "../Partials/MenuSection.vue";
import SectionDatosPersonales from "../Partials/SectionDatosPersonales.vue";
import SectionDireccion from "../Partials/SectionDireccion.vue";
import SectionDatosBancarios from "../Partials/SectionDatosBancarios.vue";
import SectionDatosLaborales from "../Partials/SectionDatosLaborales.vue";
import SectionDatosMonetarios from "../Partials/SectionDatosMonetarios.vue";
import SectionDatosSalud from "../Partials/SectionDatosSalud.vue";
import SectionFiniquitos from "../Partials/SectionFiniquitos.vue";
import { Fancybox } from "@fancyapps/ui";
import "@fancyapps/ui/dist/fancybox.css";

var props = defineProps({
    escolaridades: Object,
    estados_civiles: Object,
    cat_tipo_sangre: Object,
    bancos: Object,
    departamentos: Object,
    tipos_contrato: Object,
    empleado: Object,
    direccion: Object,
    roles: Object,
    cat_bajas: Object,
    empleado_baja: Object,
    finiquito: Object,
    departamento_puesto: Object,
    expedientes: Object,
    nominas: Object,
});
let editEmpleadoDisable = ref(false);
const estadosDireccion = ref([]);

onBeforeMount(async () => {
    try {
        const requestCatalogos = [];
        requestCatalogos.push(axios.get(route("catalogos.estados")));
        const respCatalogos = await Promise.all(requestCatalogos);
        estadosDireccion.value = respCatalogos[0].data.estadosDireccion;
    } catch (error) {
        console.log(error);
        let messageError = "Error get catalogos";
        if (error.response) {
            const messageServer = error.response.data.message;
            if (error.response.status != 500) {
                messageError = messageServer;
            } else {
                messageError = "Internal Server Error";
            }
        }
        swal("Error Catalogos", messageError, "error");
    }
});

const form = useForm({
    id: props.empleado.id,
    numero_empleado: props.empleado.numero_empleado,
    banco_id: props.empleado.banco_id,
    escolaridade_id: props.empleado.escolaridad_id,
    nombre: props.empleado.name,
    apellido_paterno:
        props.empleado.apellido_paterno === null
            ? ""
            : props.empleado.apellido_paterno,
    apellido_materno:
        props.empleado.apellido_materno === null
            ? ""
            : props.empleado.apellido_materno,
    fecha_nacimiento:
        props.empleado.fecha_nacimiento === null
            ? ""
            : props.empleado.fecha_nacimiento,
    fecha_ingreso: props.empleado.fecha_ingreso,
    fecha_ingreso_real: props.empleado.fecha_ingreso_real,
    fecha_baja: "",
    departamento_id:
        props.departamento_puesto !== null
            ? props.departamento_puesto.departamento_id
            : "",
    puesto_id:
        props.departamento_puesto !== null
            ? props.departamento_puesto.puesto_id
            : "",
    nss: props.empleado.nss,
    curp: props.empleado.curp,
    rfc: props.empleado.rfc,
    contacto_emergencia: props.empleado.contacto_emergencia,
    telefono: props.empleado.telefono,
    correo_electronico: props.empleado.email,
    hijos: props.empleado.hijos,
    clave_bancaria: props.empleado.clave_bancaria,
    numero_cuenta_bancaria: props.empleado.numero_cuenta_bancaria,
    direccion_estado_id:
        props.direccion !== null ? props.direccion.estado_id : "",
    direccion_municipio_id:
        props.direccion !== null ? props.direccion.municipio_id : "",
    direccion_localidade_id:
        props.direccion !== null ? props.direccion.localidad_id : "",
    calle: props.direccion !== null ? props.direccion.calle : "",
    numero: props.direccion !== null ? props.direccion.numero : "",
    colonia: props.direccion !== null ? props.direccion.colonia : "",
    codigo_postal:
        props.direccion !== null ? props.direccion.codigo_postal : "",
    lote: props.direccion !== null ? props.direccion.lote : "",
    manzana: props.direccion !== null ? props.direccion.manzana : "",
    cat_tipos_nomina_id: props.empleado.cat_tipos_nomina_id,
    tipos_contrato_id: props.empleado.tipos_contrato_id,
    salario_bruto: props.empleado.salario_bruto,
    salario_diario: props.empleado.salario_diario,
    salario_imss: props.empleado.salario_imss,
    bono_puntualidad: props.empleado.bono_puntualidad,
    bono_asistencia: props.empleado.bono_asistencia,
    despensa: props.empleado.despensa,
    fondo_ahorro: props.empleado.fondo_ahorro,
    horario: props.empleado.horario,
    cat_estados_civile_id: props.empleado.estado_civil_id,
    fotografia: props.empleado.fotografia,
    cat_tipos_sangre_id: props.empleado.cat_tipo_sangre_id,
    alergias: props.empleado.alergias,
    enfermedades_cronicas: props.empleado.enfermedades_cronicas,
    cat_genero_id: props.empleado.cat_genero_id,
    cat_bajas_empleado_id: "",
    fecha_baja: "",
    fecha_finiquito: "",
    monto_finiquito: 0,
    finiquito_pagado: false,
    password: "",
    rol_id: props.empleado.role_id,
    /*Datos coorporativos*/
    correo_empresarial: props.empleado.correo_empresarial,
    foto_empresarial: props.empleado.foto_empresarial,
    foto_empresarial_url: props.empleado.foto_empresarial,
    telefono_empresarial: props.empleado.telefono_empresarial,
    fotografia_url: props.empleado.fotografia,
    direccion_id: props.empleado.direccion_id,
});

onMounted(() => {
    if (props.empleado_baja !== null) {
        form.cat_bajas_empleado_id = props.empleado_baja.cat_bajas_empleado_id;
        form.fecha_baja = props.empleado_baja.fecha_baja;
    }

    if (props.finiquito !== null) {
        form.fecha_finiquito = props.finiquito.fecha_finiquito;
        form.monto_finiquito = props.finiquito.monto;
        form.finiquito_pagado = props.finiquito.pagado;
    }
});

const updateEmpelado = () => {
    if (form.fotografia == "") {
        form.fotografia = null;
    }
    if (form.foto_empresarial == undefined) {
        form.foto_empresarial = null;
    }

    form.post(route("empleados.update", props.empleado.id), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            form.fotografia_url = props.empleado.fotografia;
            form.foto_empresarial_url = props.empleado.foto_empresarial;
            swal("Actualizado", "Exitosamente", "success");
        },
        onError: (error) => {
            console.log(error.response);
            swal("Error al crear", "Favor de validar los datos", "error");
        },
    });
};

/*CAMBIAR ELEMENTO*/

const menuSeleted = ref(1);

const cambiar = (id) => {
    menuSeleted.value = id;
};

const canEdit = computed(() => {
    return usePage().props.value.can["edit-users"];
});
</script>

<template>
    <app-layout title="Dashboard" :nominas="nominas">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Actualizar Empleado
            </h2>
        </template>
        <div class="space">
            <MenuSection
                @changeMenu="cambiar($event)"
                :menu-seleted="menuSeleted"
            >
                <ButtonSeccion
                    v-if="$page.props.can['users.update-finiquito']"
                    @click="cambiar(8)"
                    :active="menuSeleted === 8"
                    :disabled="!$page.props.can['empleados.finiquito']"
                >
                    <div
                        class="grid grid-rows-2"
                        style="margin-left: 1.5rem; justify-content: center"
                    >
                        <img
                            style="margin-left: 0.8rem"
                            :src="'/assets/img/finiquitos.png'"
                        />
                        <p>Finiquitos</p>
                    </div>
                </ButtonSeccion>
            </MenuSection>
        </div>

        <div class="px-24 py-3">
            <div class="shadow-xl sm:rounded-lg">
                <div class="pl-8 pr-8">
                    <!-- Datos Personales -->
                    <SectionDatosPersonales
                        v-if="menuSeleted == 1"
                        :form="form"
                        :escolaridades="props.escolaridades"
                        :estados-civiles="props.estados_civiles"
                        :roles="props.roles"
                        :editEmpleadoDisable="editEmpleadoDisable"
                    />
                    <!-- Fin Datos Personales -->
                    <!-- Dirección del Empleado -->
                    <SectionDireccion
                        v-if="menuSeleted == 2"
                        :form="form"
                        :estados="estadosDireccion"
                    />
                    <!--Fin Dirección del Empleado -->
                    <!-- Datos Bancarios -->
                    <SectionDatosBancarios
                        v-if="menuSeleted == 3"
                        :form="form"
                        :bancos="props.bancos"
                    />
                    <!-- Fin  Datos Bancarios -->
                    <!-- Datos Laborales -->
                    <SectionDatosLaborales
                        v-if="menuSeleted == 4"
                        :form="form"
                        :departamentos="props.departamentos"
                        :tipos-contratos="props.tipos_contrato"
                    />
                    <!-- Fin Datos Laborales -->
                    <!-- Datos Monetarios -->
                    <SectionDatosMonetarios
                        v-if="menuSeleted == 5"
                        :form="form"
                    />
                    <!-- Fin Datos Monetarios -->
                    <!-- Aspectos Generales de Salud -->
                    <SectionDatosSalud
                        v-if="menuSeleted == 6"
                        :form="form"
                        :tiposSangres="props.cat_tipo_sangre"
                    />
                    <!--Fin Aspectos Generales de Salud -->
                    <!-- Expediente -->
                    <div v-show="menuSeleted == 7">
                        <SectionExpendiente
                            ref="sectionExpediente"
                            :empleado-id="empleado.id"
                            :expedientes="props.expedientes"
                            :form="form"
                        />
                    </div>
                    <!--Fin Expediente -->
                    <!-- Finiquitos -->
                    <SectionFiniquitos
                        v-if="menuSeleted == 8 && canEdit"
                        :form="form"
                        :motivos-bajas="props.cat_bajas"
                    />
                    <!-- Fin Finiquitos -->
                    <div class="flex justify-end gap-2">
                        <Link
                            :href="
                                route('empleado.indexmanual', {
                                    activo:
                                        props.empleado.activo == 1
                                            ? 'activo'
                                            : 'inactivo',
                                })
                            "
                            replace
                        >
                            <ButtonInfo> Regresar </ButtonInfo>
                        </Link>
                        <ButtonAdd @click="updateEmpelado()" v-if="canEdit">
                            Guardar
                        </ButtonAdd>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

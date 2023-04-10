<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm } from '@inertiajs/inertia-vue3';
import { ref, computed } from 'vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import InputSuccess from '@/Components/InputSuccess.vue';
import Select from '@/Components/Select.vue';
import TextInput from '@/Components/TextInput.vue';
import ButtonSeccion from '@/Components/ButtonSeccion.vue';
import ButtonInfo from '@/Components/Buttoninfo.vue';
import ButtonAdd from '@/Components/ButtonAdd.vue';
import swal from 'sweetalert';
import ListInputVue from '../../../../Components/ListInput.vue';
import { ObtenerCurp } from '../../../../utils/index.js';
import SectionExpendiente from '../Partials/SectionExpendiente.vue';

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

let typeForm = ref('create');
let sectionExpediente = ref(null);




let catalogos = ref({
    departamentos: props.departamentos,
    escolaridades: props.escolaridades,
    bancos: props.bancos,
    tiposContratos: props.tipos_contrato,
    tiposSangres: props.cat_tipo_sangre,
    motivosBajas: []
})

let puestos = ref([]);


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


const createOrUpdate = () => {
    if (typeForm.value == 'create') {
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

}

/*Obtener datos direcciones*/
const getEstados = () => {
    axios.get(route('catalogos.formularioEmpleado')).then((response) => {
        catalogos.value.estadosDireccion = response.data.estadosDireccion;
        //console.log(response.data);
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
            swal("Error get Catalogos", messageError, "error");
        }
    });
}


const getMunicipios = () => {
    if (form.direccion_estado_id !== '' && form.direccion_estado_id > 0) {
        axios.get(route('municipos.estado', form.direccion_estado_id)).
            then((response) => {
                catalogos.value.municipiosDireccion = response.data;
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
    if (form.direccion_municipio_id !== '' && form.direccion_municipio_id > 0) {
        axios.get(route('localidades.municipio', form.direccion_municipio_id)).
            then((response) => {
                catalogos.value.localidadesDireccion = response.data;
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

/*OBTENCION DE PUESTOS*/
const getPuestos = () => {
    axios.get(route('departamento.puestos.list', form.departamento_id))
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


/*CAMBIAR ELEMENTO*/

let buttonSelected = ref(1);

const cambiar = (id) => {
    buttonSelected.value = id;
}

/*Calcular salario*/
const calculoSalario = () => {
    const salarioBruto = form.salario_bruto;
    const despensa = form.despensa;
    const salarioImss = (salarioBruto - despensa) / 1.22;
    const salarioDiarioImss = salarioImss / 30.42;
    const salarioDiario = salarioDiarioImss / 1.0452;
    const bonoAsistencia = salarioImss * 0.1;
    const bonoPuntualidad = salarioImss * 0.1;

    form.salario_diario = salarioDiario.toFixed(2);
    form.salario_imss = salarioImss.toFixed(2);
    form.bono_puntualidad = bonoPuntualidad.toFixed(2);
    form.bono_asistencia = bonoAsistencia.toFixed(2);
    form.fondo_ahorro = (salarioImss * 0.02).toFixed(2);
}

/*Funciones calculadas*/
const edad = computed(() => {
    let date;
    if (form.fecha_nacimiento) {
        date = new Date(form.fecha_nacimiento);
        let dateNow = new Date();
        let auxEdad = dateNow.getFullYear() - date.getFullYear();
        if (dateNow.getMonth() < date.getMonth()) auxEdad--;
        return auxEdad;
    }
    else {
        return ""
    }
});

const generateCurp = computed(() => {
    return ObtenerCurp(form.nombre, form.apellido_paterno, form.apellido_materno, form.fecha_nacimiento);
});

const messageCurp = computed(() => {
    if (form.curp != '') {
        if (form.curp.length === 18 && form.curp.startsWith(generateCurp)) {
            form.errors.curp = '';
            return 'CURP VALIDA';
        } else {
            form.errors.curp = 'CURP INVALIDA.';
            return '';
        }
    }
    return '';
});
const messageRFC = computed(() => {
    if (form.rfc != '') {
        if (form.rfc.length >= 12 && form.rfc.startsWith(generateCurp)) {
            form.errors.rfc = '';
            return 'RCF VALIDO';
        } else {
            form.errors.rfc = 'RFC INVALIDO.';
            return '';
        }
    }
    return '';
});

const fecha_termino = computed(() => {
    let date;
    if (form.fecha_ingreso_real) {
        date = new Date(form.fecha_ingreso_real);
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
            <div class="flex justify-center">
                <div class="grid grid-cols-4 gap-1">
                    <div class="divseccion" style="margin-right:0rem">
                        <ButtonSeccion style="width:10rem" @click="cambiar(1)" href="1">
                            <div class="grid grid-rows-2 gap-1" style="justify-items:center;">
                                <img :src="'/assets/img/datos_personales.png'" />
                                <p>Datos Personales</p>
                            </div>
                        </ButtonSeccion>
                    </div>
                    <div class="divseccion">
                        <ButtonSeccion style="width:10rem" @click="cambiar(2)">
                            <div class="grid grid-rows-2 " style="justify-items:center;">
                                <img :src="'/assets/img/direccion.png'" />
                                <p>Dirección del Empleado</p>
                            </div>
                        </ButtonSeccion>
                    </div>
                    <div class="divseccion">
                        <ButtonSeccion style="width:10rem" @click="cambiar(3)">
                            <div class="grid grid-rows-2 " style="justify-items:center;">
                                <img :src="'/assets/img/datos_bancarios.png'" />
                                <p>Datos Bancarios</p>
                            </div>
                        </ButtonSeccion>
                    </div>
                    <div class="divseccion">
                        <ButtonSeccion style="width:10rem" @click="cambiar(4)">
                            <div class="grid grid-rows-2 " style="justify-items:center;">
                                <img :src="'/assets/img/datos_laborales.png'" />
                                <p>Datos Laborales</p>
                            </div>
                        </ButtonSeccion>
                    </div>
                    <div class="divseccion">
                        <ButtonSeccion style="width:10rem" @click="cambiar(5)">
                            <div class="grid grid-rows-2 " style="justify-items:center;">
                                <img :src="'/assets/img/datos_monetarios.png'" />
                                <p>Datos Monetarios</p>
                            </div>
                        </ButtonSeccion>
                    </div>
                    <div class="divseccion">
                        <ButtonSeccion style="width:10rem" @click="cambiar(6)">
                            <div class="grid grid-rows-2 " style="justify-items:center;">
                                <img :src="'/assets/img/datos_salud.png'" />
                                <p>Aspectos Generales De Salud</p>
                            </div>
                        </ButtonSeccion>
                    </div>
                    <div class="divseccion">
                        <ButtonSeccion style="width:10rem" @click="cambiar(7)">
                            <div class="grid grid-rows-2 " style="justify-items:center;">
                                <img :src="'/assets/img/datos_salud.png'" />
                                <p>Archivos expedientes</p>
                            </div>
                        </ButtonSeccion>
                    </div>
                    <div class="divseccion" v-if="typeForm == 'update' && $page.props.can['users.update-finiquito']">
                        <ButtonSeccion style="width:10rem" @click="cambiar(8)">
                            <div class="grid grid-rows-2 " style="margin-left: 1.5rem;justify-content: center;">
                                <img style="margin-left:0.8rem" :src="'/assets/img/finiquitos.png'" />
                                <p>Finiquitos</p>
                            </div>
                        </ButtonSeccion>
                    </div>
                </div>
            </div>
            <!-- Sections froms -->
            <div class="py-6">
                <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div class="px-4 py-2 bg-white shadow-xl sm:rounded-lg">
                        <!-- Datos Personales -->
                        <div v-if="buttonSelected == 1" class="border-b tab">
                            <div class="relative border-l-2 border-transparent">
                                <input class="absolute z-10 w-full h-10 opacity-0 cursor-pointer top-6" type="checkbox"
                                    id="datosPersonales" checked>
                                <header
                                    class="flex items-center justify-between p-5 pl-8 pr-8 cursor-pointer select-none tab-label"
                                    for="datosPersonales">
                                    <span class="text-xl font-thin text-grey-darkest">
                                        Datos Personales
                                    </span>
                                </header>
                                <div class="tab-content">
                                    <div class="pb-8 pl-8 pr-8">
                                        <div class="grid grid-cols-3 gap-4">
                                            <div class="mt-4">
                                                <InputLabel for="numero_empleado" value="ID de Empleado:*" />
                                                <TextInput id="numero_empleado" type="text" v-model="form.numero_empleado"
                                                    class="block w-full mt-1" placeholder="No. Empleado" />
                                                <InputError :message="form.errors.numero_empleado" class="mt-2" />
                                            </div>
                                            <div class="mt-4">
                                                <InputLabel for="nombre" value="Nombre:*" />
                                                <TextInput id="nombre" type="text" v-model="form.nombre"
                                                    class="block w-full mt-1" placeholder="Nombre" />
                                                <InputError :message="form.errors.nombre" class="mt-2" />
                                            </div>
                                            <div class="mt-4">
                                                <InputLabel for="apellido_paterno" value="Apellido Paterno:*" />
                                                <TextInput id="apellido_paterno" type="text" v-model="form.apellido_paterno"
                                                    class="block w-full mt-1" placeholder="Apellido Paterno" />
                                                <InputError :message="form.errors.apellido_paterno" class="mt-2" />
                                            </div>
                                            <div class="mt-4">
                                                <InputLabel for="apellido_materno" value="Apellido Materno:*" />
                                                <TextInput id="apellido_materno" type="text" v-model="form.apellido_materno"
                                                    class="block w-full mt-1" placeholder="Apellido Materno" />
                                                <InputError :message="form.errors.apellido_materno" class="mt-2" />
                                            </div>
                                            <div class="mt-4">
                                                <InputLabel for="fecha_nacimiento" value="Fecha de Nacimiento:*" />
                                                <TextInput id="fecha_nacimiento" type="date" v-model="form.fecha_nacimiento"
                                                    class="block w-full mt-1" placeholder="No. Empleado" />
                                                <InputError :message="form.errors.fecha_nacimiento" class="mt-2" />
                                            </div>
                                            <div class="mt-4">
                                                <InputLabel for="edad" value="Edad:*" />
                                                <TextInput id="edad" v-model="edad" type="text" disabled
                                                    class="block w-full mt-1" placeholder="Edad" />
                                            </div>
                                            <div class="mt-4">
                                                <InputLabel for="telefono" value="Telefono:*" />
                                                <TextInput id="telefono" type="text" v-model="form.telefono"
                                                    class="block w-full mt-1" placeholder="+52 722 000 0000" />
                                                <InputError :message="form.errors.telefono" class="mt-2" />
                                            </div>
                                            <div class="mt-4">
                                                <InputLabel for="curp" value="CURP:*" />
                                                <TextInput id="curp" type="text" v-model="form.curp"
                                                    class="block w-full mt-1" :placeholder="generateCurp" maxlength="18" />
                                                <InputError :message="form.errors.curp" class="mt-2" />
                                                <InputSuccess :message="messageCurp" class="mt-2" />
                                            </div>
                                            <div class="mt-4">
                                                <InputLabel for="rfc" value="RFC:*" />
                                                <TextInput id="rfc" type="text" v-model="form.rfc" class="block w-full mt-1"
                                                    placeholder="RFC" maxlength="13" />
                                                <InputError :message="form.errors.rfc" class="mt-2" />
                                                <InputSuccess :message="messageRFC" class="mt-2" />
                                            </div>
                                            <div class="mt-4">
                                                <InputLabel for="correo_electronico" value="Correo:*" />
                                                <TextInput id="correo_electronico" type="email"
                                                    v-model="form.correo_electronico" class="block w-full mt-1"
                                                    placeholder="correo@ejemplo.com" />
                                                <InputError :message="form.errors.correo_electronico" class="mt-2" />
                                            </div>
                                            <div class="mt-4">
                                                <InputLabel for="cat_genero_id" value="Genero:*" />
                                                <Select v-model="form.cat_genero_id" class="w-full"
                                                    style="margin-top:0.2rem">
                                                    <option disabled selected>Elige un Genero</option>
                                                    <option value="1">Masculino</option>
                                                    <option value="2">Femenino</option>
                                                </Select>
                                                <InputError :message="form.errors.cat_genero_id" class="mt-2" />

                                            </div>
                                            <div class="mt-4">
                                                <InputLabel for="escolaridade_id" value="Escolaridad:*" />
                                                <Select v-model="form.escolaridade_id" class="w-full">
                                                    <option v-for="escolaridad in catalogos.escolaridades"
                                                        :key="escolaridad.id" :value="escolaridad.id">
                                                        {{ escolaridad.nombre }}
                                                    </option>
                                                </Select>
                                                <InputError :message="form.errors.escolaridade_id" class="mt-2" />
                                            </div>
                                            <div class="mt-4">
                                                <InputLabel for="cat_estados_civile_id" value="Estado Civil:*" />
                                                <Select v-model="form.cat_estados_civile_id" class="w-full">
                                                    <option v-for="estados_civil in estados_civiles" :key="estados_civil.id"
                                                        :value="estados_civil.id">
                                                        {{ estados_civil.nombre }}
                                                    </option>
                                                </Select>
                                                <InputError :message="form.errors.cat_estados_civile_id" class="mt-2" />
                                            </div>
                                            <div class="mt-4">
                                                <InputLabel for="contacto_emergencia" value="Contacto de Emergencia:*" />
                                                <TextInput id="contacto_emergencia" type="text"
                                                    v-model="form.contacto_emergencia" class="block w-full mt-1"
                                                    placeholder="Contacto de emergencia" />
                                                <InputError :message="form.errors.contacto_emergencia" class="mt-2" />
                                            </div>
                                            <div class="mt-4">
                                                <InputLabel for="hijos" value=" Hijos:*" />
                                                <TextInput id="hijos" type="number" v-model="form.hijos"
                                                    class="block w-full mt-1" placeholder="Hijos" />
                                                <InputError :message="form.errors.hijos" class="mt-2" />
                                            </div>

                                            <div class="mt-4">
                                                <InputLabel for="password" value="Password:*" />
                                                <TextInput id="password" type="password" v-model="form.password"
                                                    class="block w-full mt-1" placeholder="Contraseña" />
                                                <InputError :message="form.errors.password" class="mt-2" />
                                            </div>

                                            <div class="mt-4 ">
                                                <InputLabel for="cat_estados_civile_id" value="Rol:*" />
                                                <Select v-model="form.rol_id" class="w-full">
                                                    <option v-for="rol in roles" :key="rol.id" :value="rol.id">
                                                        {{ rol.nombre }}
                                                    </option>
                                                </Select>

                                                <InputError :message="form.errors.rol_id" class="mt-0" />
                                            </div>
                                            <div class="mt-4">
                                                <InputLabel value="Correo Empresarial:*" />
                                                <TextInput class="block w-full mt-1" type="email"
                                                    v-model="form.correo_empresarial" />
                                            </div>
                                            <div class="mt-4">
                                                <InputLabel value="Telefono Empresarial:*" />
                                                <TextInput class="block w-full mt-1" type="tel"
                                                    v-model="form.telefono_empresarial" />
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Fin Datos Personales -->
                        <!-- Dirección del Empleado -->
                        <div v-if="buttonSelected == 2" class="border-b tab">
                            <div class="relative border-l-2 border-transparent">
                                <input class="absolute z-10 w-full h-10 opacity-0 cursor-pointer top-6" type="checkbox"
                                    id="datosPersonales" checked>
                                <header
                                    class="flex items-center justify-between p-5 pl-8 pr-8 cursor-pointer select-none tab-label"
                                    for="datosPersonales">
                                    <span class="text-xl font-thin text-grey-darkest">
                                        Dirección del Empleado
                                    </span>
                                    <div
                                        class="flex items-center justify-center border rounded-full border-grey w-7 h-7 test">
                                        <svg aria-hidden="true" class="" data-reactid="266" fill="none" height="24"
                                            stroke="#606F7B" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            viewbox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
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
                                                <TextInput id="calle" type="text" v-model="form.calle"
                                                    class="block w-full mt-1" placeholder="Calle" required />
                                                <InputError :message="form.errors.calle" class="mt-2" />
                                            </div>
                                            <div class="mt-4">
                                                <InputLabel for="numero" value="Colonia:*" />
                                                <TextInput id="numero" type="text" v-model="form.numero"
                                                    class="block w-full mt-1" placeholder="Nombre" required />
                                                <InputError :message="form.errors.numero" class="mt-2" />
                                            </div>
                                            <div class="mt-4">
                                                <InputLabel for="colonia" value="Número:*" />
                                                <TextInput id="colonia" type="text" v-model="form.colonia"
                                                    class="block w-full mt-1" placeholder="Número" required />
                                                <InputError :message="form.errors.colonia" class="mt-2" />
                                            </div>
                                            <div class="mt-4">
                                                <InputLabel for="codigo_postal" value="CP:*" />
                                                <TextInput id="codigo_postal" type="number" maxlength="5"
                                                    v-model="form.codigo_postal" class="block w-full mt-1"
                                                    placeholder="Codigo Postal" required />
                                                <InputError :message="form.errors.codigo_postal" class="mt-2" />
                                            </div>
                                            <div class="mt-4">
                                                <InputLabel for="lote" value="Lote:*" />
                                                <TextInput id="lote" type="text" v-model="form.lote"
                                                    class="block w-full mt-1" placeholder="Lote" required />
                                                <InputError :message="form.errors.lote" class="mt-2" />
                                            </div>
                                            <div class="mt-4">
                                                <InputLabel for="manzana" value="Manzana:*" />
                                                <TextInput id="manzana" type="text" v-model="form.manzana"
                                                    class="block w-full mt-1" placeholder="Manzana" required />
                                                <InputError :message="form.errors.manzana" class="mt-2" />
                                            </div>

                                            <!-- ----------------------------------------------------------------- -->
                                            <div class="mt-4">
                                                <InputLabel for="direccion_estado_id" value="Estado:*" />
                                                <ListInputVue list="listaEstados" v-model="form.direccion_estado_id"
                                                    :options="catalogos.estadosDireccion" class="block w-full mt-1"
                                                    @click="getEstados()" />
                                                <InputError :message="form.errors.direccion_estado_id" class="mt-2" />
                                            </div>
                                            <div class="mt-4">
                                                <InputLabel for="direccion_municipio_id" value="Municipio:*" />
                                                <ListInputVue v-if="form.direccion_estado_id != 0" list="listaMunicipios"
                                                    :disabled="false" v-model="form.direccion_municipio_id"
                                                    :options="catalogos.municipiosDireccion" class="block w-full mt-1"
                                                    @click="getMunicipios()" />
                                                <ListInputVue v-if="form.direccion_estado_id == 0" list="listaMunicipios"
                                                    :disabled="true" />
                                                <InputError :message="form.errors.direccion_municipio_id" class="mt-2" />
                                            </div>
                                            <div class="mt-4">
                                                <InputLabel for="direccion_localidade_id" value="Localidad:*" />
                                                <ListInputVue list="listaLocalidades"
                                                    v-if="form.direccion_municipio_id != 0" :disabled="false"
                                                    v-model="form.direccion_localidade_id"
                                                    :options="catalogos.localidadesDireccion" class="block w-full mt-1"
                                                    @click="getLocalidades()" />
                                                <ListInputVue v-if="form.direccion_municipio_id == 0" :disabled="true" />
                                                <InputError :message="form.errors.direccion_localidade_id" class="mt-2" />
                                            </div>
                                            <!-- ----------------------------------------------------------------- -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--Fin Dirección del Empleado -->
                        <!-- Datos Bancarios -->
                        <div v-if="buttonSelected == 3" class="border-b tab">
                            <div class="relative border-l-2 border-transparent">
                                <input class="absolute z-10 w-full h-10 opacity-0 cursor-pointer top-6" type="checkbox"
                                    id="datosPersonale" checked>
                                <header
                                    class="flex items-center justify-between p-5 pl-8 pr-8 cursor-pointer select-none tab-label"
                                    for="datosPersonales">
                                    <span class="text-xl font-thin text-grey-darkest">
                                        Datos Bancarios
                                    </span>
                                    <div
                                        class="flex items-center justify-center border rounded-full border-grey w-7 h-7 test">
                                        <svg aria-hidden="true" class="" data-reactid="266" fill="none" height="24"
                                            stroke="#606F7B" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            viewbox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
                                            <polyline points="6 9 12 15 18 9">
                                            </polyline>
                                        </svg>
                                    </div>
                                </header>
                                <div class="tab-content">
                                    <div class="pb-5 pl-8 pr-8">
                                        <div class="grid grid-cols-3 gap-4">
                                            <div class="col-span-3">
                                                <InputLabel for="banco_id" value="Banco:*" />
                                                <Select v-model="form.banco_id" class="w-full">
                                                    <option v-for="banco in catalogos.bancos" :key="banco.id"
                                                        :value="banco.id">
                                                        {{ banco.nombre }}
                                                    </option>
                                                </Select>
                                                <InputError :message="form.errors.banco_id" class="mt-2" />
                                            </div>
                                            <div class="mt-4">
                                                <InputLabel for="clave_bancaria" value="Clave Interbancaria:*" />
                                                <TextInput id="clave_bancaria" type="text" v-model="form.clave_bancaria"
                                                    class="block w-full mt-1" placeholder="Clave Interbancaria" />
                                                <InputError :message="form.errors.clave_bancaria" class="mt-2" />
                                            </div>
                                            <div class="mt-4">
                                                <InputLabel for="numero_cuenta_bancaria" value="No. Cuenta:*" />
                                                <TextInput id="numero_cuenta_bancaria" type="text"
                                                    v-model="form.numero_cuenta_bancaria" class="block w-full mt-1"
                                                    placeholder="No. Cuenta" />
                                                <InputError :message="form.errors.numero_cuenta_bancaria" class="mt-2" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Fin  Datos Bancarios -->
                        <!-- Datos Laborales -->
                        <div v-if="buttonSelected == 4" class="border-b tab">
                            <div class="relative border-l-2 border-transparent">
                                <input class="absolute z-10 w-full h-10 opacity-0 cursor-pointer top-6" type="checkbox"
                                    id="datosPersonales" checked>
                                <header
                                    class="flex items-center justify-between p-5 pl-8 pr-8 cursor-pointer select-none tab-label"
                                    for="datosPersonales">
                                    <span class="text-xl font-thin text-grey-darkest">
                                        Datos Laborales
                                    </span>
                                    <div
                                        class="flex items-center justify-center border rounded-full border-grey w-7 h-7 test">
                                        <svg aria-hidden="true" class="" data-reactid="266" fill="none" height="24"
                                            stroke="#606F7B" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            viewbox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
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
                                                <ListInputVue v-model="form.departamento_id" list="departamentos"
                                                    @change="getPuestos" class="w-full"
                                                    :options="catalogos.departamentos" />

                                                <InputError :message="form.errors.departamento_id" class="mt-2" />
                                            </div>
                                            <div class="mt-4">
                                                <InputLabel for="puesto_id" value="Puesto:*" />

                                                <Select v-model="form.puesto_id" class="w-full">

                                                    <option v-for="puesto in puestos" :key="puesto.id" :value="puesto.id">
                                                        {{ puesto.name }}
                                                    </option>
                                                </Select>
                                                <InputError :message="form.errors.puesto_id" class="mt-2" />
                                            </div>

                                            <div class="mt-4">
                                                <InputLabel for="horario" value="Horario Laboral:*" />
                                                <Select v-model="form.horario" class="w-full">
                                                    <option disabled selected>Elige un Horario</option>
                                                    <option value="oficina">Oficina</option>
                                                    <option value="turnos">Rolar Turnos</option>
                                                </Select>
                                                <InputError :message="form.errors.horario" class="mt-2" />
                                            </div>
                                            <div class="mt-4">
                                                <InputLabel for="nss" value="Número de Seguridad Social:*" />
                                                <TextInput id="nss" type="text" v-model="form.nss" class="block w-full mt-1"
                                                    placeholder="Número de Seguridad Social" />
                                                <InputError :message="form.errors.nss" class="mt-2" />
                                            </div>
                                            <div class="mt-4">
                                                <InputLabel for="fecha_ingreso" value="Fecha de Ingreso:*" />
                                                <TextInput id="fecha_ingreso" type="date" v-model="form.fecha_ingreso"
                                                    class="block w-full mt-1" placeholder="Fecha de Ingreso"
                                                    max="2030-12-12" />
                                                <InputError :message="form.errors.fecha_ingreso" class="mt-2" />
                                            </div>
                                            <div class="mt-4">
                                                <InputLabel for="fecha_ingreso_real" value="Fecha Real:*" />
                                                <TextInput id="fecha_ingreso_real" type="date"
                                                    v-model="form.fecha_ingreso_real" class="block w-full mt-1"
                                                    placeholder="Fecha Real" max="2030-12-12" />
                                                <InputError :message="form.errors.fecha_ingreso_real" class="mt-2" />
                                            </div>
                                            <div class="mt-4">
                                                <InputLabel for="cat_tipos_nomina_id" value="Tipo de Nomina:*" />
                                                <Select v-model="form.cat_tipos_nomina_id" class="w-full">
                                                    <option disabled selected>Elige un tipo de nomina</option>
                                                    <option value="1">Semanal</option>
                                                    <option value="2">Quincenal</option>
                                                </Select>
                                                <InputError :message="form.errors.cat_tipos_nomina_id" class="mt-2" />
                                            </div>
                                            <div class="mt-4">
                                                <InputLabel for="tipos_contrato_id" value="Tipo de Contrato:*" />
                                                <Select v-model="form.tipos_contrato_id" class="w-full">
                                                    <option v-for="tipo_contrato in catalogos.tiposContratos"
                                                        :key="tipo_contrato.id" :value="tipo_contrato.id">
                                                        {{ tipo_contrato.nombre }}
                                                    </option>
                                                </Select>
                                                <InputError :message="form.errors.tipos_contrato_id" class="mt-2" />
                                            </div>
                                            <div v-if="form.tipos_contrato_id == 1" class="mt-4">
                                                <InputLabel for="fecha_termino" value="Fecha de Termino:" />
                                                <TextInput id="fecha_termino" type="date" v-model="fecha_termino"
                                                    class="block w-full mt-1" disabled />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Fin Datos Laborales -->
                        <!-- Datos Monetarios -->
                        <div v-if="buttonSelected == 5" class="border-b tab">
                            <div class="relative border-l-2 border-transparent">
                                <input checked class="absolute z-10 w-full h-10 opacity-0 cursor-pointer top-6"
                                    type="checkbox" id="datosPersonales">
                                <header
                                    class="flex items-center justify-between p-5 pl-8 pr-8 cursor-pointer select-none tab-label"
                                    for="datosPersonales">
                                    <span class="text-xl font-thin text-grey-darkest">
                                        Monetarios
                                    </span>
                                    <div
                                        class="flex items-center justify-center border rounded-full border-grey w-7 h-7 test">
                                        <svg aria-hidden="true" class="" data-reactid="266" fill="none" height="24"
                                            stroke="#606F7B" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            viewbox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
                                            <polyline points="6 9 12 15 18 9">
                                            </polyline>
                                        </svg>
                                    </div>
                                </header>
                                <div class="tab-content">
                                    <div class="pb-5 pl-8 pr-8">
                                        <div class="grid grid-cols-6 gap-4">
                                            <div class="col-span-2">
                                                <InputLabel for="salario_diario" value="Salario Diario:*" />
                                                <TextInput id="salario_diario" type="text" disabled
                                                    class="block w-full mt-1" placeholder="$ 00.0"
                                                    v-model="form.salario_diario" />
                                            </div>
                                            <div class="col-span-2">
                                                <InputLabel for="salario_bruto" value="Salario Bruto:*" />
                                                <TextInput id="salario_bruto" type="number" v-model="form.salario_bruto"
                                                    class="block w-full mt-1" placeholder="$ 00.0"
                                                    @keyup="calculoSalario()" />
                                                <InputError :message="form.errors.salario_bruto" class="mt-2" />
                                            </div>
                                            <div class="col-span-2">
                                                <InputLabel for="salario_imss" value="Salario IMSS:*" />
                                                <TextInput id="salario_imss" type="text" v-model="form.salario_imss"
                                                    disabled class="block w-full mt-1" placeholder="$ 00.0" />
                                                <InputError :message="form.errors.salario_imss" class="mt-2" />
                                            </div>
                                            <div class="col-span-3">
                                                <InputLabel for="fondo_ahorro" value="Fondo de Ahorro:*" />
                                                <TextInput id="fondo_ahorro" type="text" v-model="form.fondo_ahorro"
                                                    class="block w-full mt-1" placeholder="$ 00.0" disabled />
                                                <InputError :message="form.errors.fondo_ahorro" class="mt-2" />
                                            </div>
                                            <div class="col-span-3">
                                                <InputLabel for="bono_puntualidad" value="Bono de Puntualidad:*" />
                                                <TextInput id="bono_puntualidad" type="text" v-model="form.bono_puntualidad"
                                                    class="block w-full mt-1" placeholder="$ 00.0" disabled />
                                                <InputError :message="form.errors.bono_puntualidad" class="mt-2" />
                                            </div>
                                            <div class="col-span-3">
                                                <InputLabel for="bono_asistencia" value="Bono de Asistencia:*" />
                                                <TextInput id="bono_asistencia" type="text" v-model="form.bono_asistencia"
                                                    class="block w-full mt-1" placeholder="$ 00.0" disabled />
                                                <InputError :message="form.errors.bono_asistencia" class="mt-2" />
                                            </div>
                                            <div class="col-span-3">
                                                <InputLabel for="despensa" value="Despensa:*" />
                                                <TextInput id="despensa" type="number" v-model="form.despensa"
                                                    class="block w-full mt-1" placeholder="$ 00.0"
                                                    @keyup="calculoSalario()" />
                                                <InputError :message="form.errors.despensa" class="mt-2" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Fin Datos Monetarios -->
                        <!-- Aspectos Generales de Salud -->
                        <div v-if="buttonSelected == 6" class="border-b tab">
                            <div class="relative border-l-2 border-transparent">
                                <input checked class="absolute z-10 w-full h-10 opacity-0 cursor-pointer top-6"
                                    type="checkbox" id="datosPersonales">
                                <header
                                    class="flex items-center justify-between p-5 pl-8 pr-8 cursor-pointer select-none tab-label"
                                    for="datosPersonales">
                                    <span class="text-xl font-thin text-grey-darkest">
                                        Aspectos Generales de Salud
                                    </span>
                                    <div
                                        class="flex items-center justify-center border rounded-full border-grey w-7 h-7 test">
                                        <svg aria-hidden="true" class="" data-reactid="266" fill="none" height="24"
                                            stroke="#606F7B" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            viewbox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
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
                                                <Select v-model="form.cat_tipos_sangre_id" class="w-full">
                                                    <option v-for="sangre in catalogos.tiposSangres" :key="sangre.id"
                                                        :value="sangre.id">
                                                        {{ sangre.nombre }}
                                                    </option>
                                                </Select>
                                                <InputError :message="form.errors.cat_tipos_sangre_id" class="mt-2" />
                                            </div>
                                            <div class="mt-4">
                                                <InputLabel for="alergias" value="Alergias:" />
                                                <TextInput id="alergias" type="text" v-model="form.alergias"
                                                    class="block w-full mt-1" placeholder="alergia 1, alergia 2" />
                                                <InputError :message="form.errors.alergias" class="mt-2" />
                                            </div>
                                            <div class="mt-4">
                                                <InputLabel for="enfermedades_cronicas" value="Enfermedades Crónicas:" />
                                                <TextInput id="enfermedades_cronicas" type="text"
                                                    v-model="form.enfermedades_cronicas" class="block w-full mt-1"
                                                    placeholder="enfermedad 1, enfermedad 2" />
                                                <InputError :message="form.errors.enfermedades_cronicas" class="mt-2" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--Fin Aspectos Generales de Salud -->
                        <!-- Expediente -->
                        <div v-show="buttonSelected == 7">
                            <SectionExpendiente ref="sectionExpediente" :expedientes="props.expedientes" />
                        </div>
                        <!--Fin Expediente -->
                        <!-- Finiquitos -->
                        <div v-if="buttonSelected == 8">
                            <div v-if="typeForm == 'update'" class="border-b tab">
                                <div class="relative border-l-2 border-transparent">
                                    <input checked class="absolute z-10 w-full h-10 opacity-0 cursor-pointer top-6"
                                        type="checkbox" id="datosPersonales">
                                    <header
                                        class="flex items-center justify-between p-5 pl-8 pr-8 cursor-pointer select-none tab-label"
                                        for="datosPersonales">
                                        <span class="text-xl font-thin text-grey-darkest">
                                            Finiquitos
                                        </span>
                                        <div
                                            class="flex items-center justify-center border rounded-full border-grey w-7 h-7 test">
                                            <svg aria-hidden="true" class="" data-reactid="266" fill="none" height="24"
                                                stroke="#606F7B" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2" viewbox="0 0 24 24" width="24"
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
                                                    <Select v-model="form.cat_bajas_empleado_id" class="w-full"
                                                        :disabled="!$page.props.can['empleados.delete']">
                                                        <option v-for="motivo in catalogos.motivosBajas" :key="motivo.id"
                                                            :value="motivo.id">
                                                            {{ motivo.nombre }}
                                                        </option>
                                                    </Select>
                                                    <input-error :message="form.errors.cat_bajas_empleado_id"
                                                        class="mt-2" />
                                                </div>
                                                <div class="mt-4">
                                                    <InputLabel for="fecha_baja" value="Fecha de Baja:" />
                                                    <TextInput id="fecha_baja" type="date" max="2030-12-31"
                                                        v-model="form.fecha_baja" class="block w-full mt-1"
                                                        :disabled="!$page.props.can['empleados.delete']" />
                                                    <input-error :message="form.errors.fecha_baja" class="mt-2" />
                                                </div>
                                                <div class="mt-4">
                                                    <InputLabel for="fecha_finiquito" value="Fecha de Finiquito:*" />
                                                    <TextInput id="fecha_finiquito" type="date" max="2030-12-31"
                                                        v-model="form.fecha_finiquito"
                                                        :disabled="!$page.props.can['finiquitos.update']"
                                                        class="block w-full mt-1" />
                                                    <input-error :message="form.errors.fecha_finiquito" class="mt-2" />
                                                </div>
                                                <div class="mt-4">
                                                    <InputLabel for="monto_finiquito" value="Monto de Finiquito:*" />
                                                    <TextInput id="monto_finiquito" type="text" placeholder="$0.0"
                                                        v-model="form.monto_finiquito"
                                                        :disabled="!$page.props.can['finiquitos.update']"
                                                        class="block w-full mt-1" max="99999" />
                                                    <input-error :message="form.errors.monto_finiquito" class="mt-2" />
                                                </div>
                                                <div class="mt-4">
                                                    <InputLabel for="finiquito_pagado" value="Pagado:*" />
                                                    <Select v-model="form.finiquito_pagado" class="w-full"
                                                        :disabled="!$page.props.can['finiquitos.update']">
                                                        <option value="0">NO</option>
                                                        <option value="1">SI</option>
                                                    </Select>
                                                    <input-error :message="form.errors.finiquito_pagado" class="mt-2" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Fin Finiquitos -->
                        <!-- Error forms -->
                        <div class="flex justify-end gap-2">

                            <a :href="route('empleado.indexmanual', { activo: 'activo' })">
                                <ButtonInfo>
                                    Regresar
                                </ButtonInfo>
                            </a>
                            <ButtonAdd @click="createOrUpdate">
                                Guardar
                            </ButtonAdd>
                        </div>


                    </div>
                </div>
            </div>

        </form>
    </app-layout>
</template>


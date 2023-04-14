<script setup>
import { computed } from 'vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import InputSuccess from '@/Components/InputSuccess.vue';
import Select from '@/Components/Select.vue';
import { ObtenerCurp } from '../../../../utils/index.js';

const props = defineProps({
    form: {
        type: Object,
        required: true,
    },
    escolaridades: {
        type: Array,
        require: true,
    },
    estadosCiviles: {
        type: Array,
        require: true,
    },
    roles: {
        type: Array,
        require: true,
    }

});

/*Funciones calculadas*/
const edad = computed(() => {

    if (props.form.fecha_nacimiento) {
        const date = new Date(props.form.fecha_nacimiento);
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
    return ObtenerCurp(props.form.nombre, props.form.apellido_paterno, props.form.apellido_materno, props.form.fecha_nacimiento);
});

const messageCurp = computed(() => {
    if (props.form.curp != '') {
        if (props.form.curp.length === 18 && props.form.curp.startsWith(generateCurp.value)) {
            props.form.errors.curp = '';
            return 'CURP VALIDA';
        } else {
            props.form.errors.curp = 'CURP INVALIDA.';
            return '';
        }
    }
    return '';
});
const messageRFC = computed(() => {
    if (props.form.rfc != '') {
        if (props.form.rfc.length >= 12 && props.form.rfc.startsWith(generateCurp.value)) {
            props.form.errors.rfc = '';
            return 'RCF VALIDO';
        } else {
            props.form.errors.rfc = 'RFC INVALIDO.';
            return '';
        }
    }
    return '';
});

</script>
<template>
    <!-- Datos Personales -->
    <div class="border-b tab">
        <div class="relative border-l-2 border-transparent">
            <input class="absolute z-10 w-full h-10 opacity-0 cursor-pointer top-6" type="checkbox" id="datosPersonales"
                checked>
            <header class="flex items-center justify-between p-5 pl-8 pr-8 cursor-pointer select-none tab-label"
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
                            <TextInput id="numero_empleado" type="text" v-model="props.form.numero_empleado"
                                class="block w-full mt-1" placeholder="No. Empleado" />
                            <InputError :message="props.form.errors.numero_empleado" class="mt-2" />
                        </div>
                        <div class="mt-4">
                            <InputLabel for="nombre" value="Nombre:*" />
                            <TextInput id="nombre" type="text" v-model="props.form.nombre" class="block w-full mt-1"
                                placeholder="Nombre" />
                            <InputError :message="props.form.errors.nombre" class="mt-2" />
                        </div>
                        <div class="mt-4">
                            <InputLabel for="apellido_paterno" value="Apellido Paterno:*" />
                            <TextInput id="apellido_paterno" type="text" v-model="props.form.apellido_paterno"
                                class="block w-full mt-1" placeholder="Apellido Paterno" />
                            <InputError :message="props.form.errors.apellido_paterno" class="mt-2" />
                        </div>
                        <div class="mt-4">
                            <InputLabel for="apellido_materno" value="Apellido Materno:*" />
                            <TextInput id="apellido_materno" type="text" v-model="props.form.apellido_materno"
                                class="block w-full mt-1" placeholder="Apellido Materno" />
                            <InputError :message="props.form.errors.apellido_materno" class="mt-2" />
                        </div>
                        <div class="mt-4">
                            <InputLabel for="fecha_nacimiento" value="Fecha de Nacimiento:*" />
                            <TextInput id="fecha_nacimiento" type="date" v-model="props.form.fecha_nacimiento"
                                class="block w-full mt-1" placeholder="No. Empleado" />
                            <InputError :message="props.form.errors.fecha_nacimiento" class="mt-2" />
                        </div>
                        <div class="mt-4">
                            <InputLabel for="edad" value="Edad:*" />
                            <TextInput id="edad" v-model="edad" type="text" disabled class="block w-full mt-1"
                                placeholder="Edad" />
                        </div>
                        <div class="mt-4">
                            <InputLabel for="telefono" value="Telefono:*" />
                            <TextInput id="telefono" type="text" v-model="props.form.telefono" class="block w-full mt-1"
                                placeholder="+52 722 000 0000" />
                            <InputError :message="props.form.errors.telefono" class="mt-2" />
                        </div>
                        <div class="mt-4">
                            <InputLabel for="curp" value="CURP:*" />
                            <TextInput id="curp" type="text" v-model="props.form.curp" class="block w-full mt-1"
                                :placeholder="generateCurp" maxlength="18" />
                            <InputError :message="props.form.errors.curp" class="mt-2" />
                            <InputSuccess :message="messageCurp" class="mt-2" />
                        </div>
                        <div class="mt-4">
                            <InputLabel for="rfc" value="RFC:*" />
                            <TextInput id="rfc" type="text" v-model="props.form.rfc" class="block w-full mt-1"
                                placeholder="RFC" maxlength="13" />
                            <InputError :message="props.form.errors.rfc" class="mt-2" />
                            <InputSuccess :message="messageRFC" class="mt-2" />
                        </div>
                        <div class="mt-4">
                            <InputLabel for="correo_electronico" value="Correo:*" />
                            <TextInput id="correo_electronico" type="email" v-model="props.form.correo_electronico"
                                class="block w-full mt-1" placeholder="correo@ejemplo.com" />
                            <InputError :message="props.form.errors.correo_electronico" class="mt-2" />
                        </div>
                        <div class="mt-4">
                            <InputLabel for="cat_genero_id" value="Genero:*" />
                            <Select v-model="props.form.cat_genero_id" class="w-full" style="margin-top:0.2rem">
                                <option disabled selected>Elige un Genero</option>
                                <option value="1">Masculino</option>
                                <option value="2">Femenino</option>
                            </Select>
                            <InputError :message="props.form.errors.cat_genero_id" class="mt-2" />

                        </div>
                        <div class="mt-4">
                            <InputLabel for="escolaridade_id" value="Escolaridad:*" />
                            <Select v-model="props.form.escolaridade_id" class="w-full">
                                <option v-for="escolaridad in props.escolaridades" :key="escolaridad.id"
                                    :value="escolaridad.id">
                                    {{ escolaridad.nombre }}
                                </option>
                            </Select>
                            <InputError :message="props.form.errors.escolaridade_id" class="mt-2" />
                        </div>
                        <div class="mt-4">
                            <InputLabel for="cat_estados_civile_id" value="Estado Civil:*" />
                            <Select v-model="props.form.cat_estados_civile_id" class="w-full">
                                <option v-for="estados_civil in props.estadosCiviles" :key="estados_civil.id"
                                    :value="estados_civil.id">
                                    {{ estados_civil.nombre }}
                                </option>
                            </Select>
                            <InputError :message="props.form.errors.cat_estados_civile_id" class="mt-2" />
                        </div>
                        <div class="mt-4">
                            <InputLabel for="contacto_emergencia" value="Contacto de Emergencia:*" />
                            <TextInput id="contacto_emergencia" type="text" v-model="props.form.contacto_emergencia"
                                class="block w-full mt-1" placeholder="Contacto de emergencia" />
                            <InputError :message="props.form.errors.contacto_emergencia" class="mt-2" />
                        </div>
                        <div class="mt-4">
                            <InputLabel for="hijos" value=" Hijos:*" />
                            <TextInput id="hijos" type="number" v-model="props.form.hijos" class="block w-full mt-1"
                                placeholder="Hijos" />
                            <InputError :message="props.form.errors.hijos" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <InputLabel for="password" value="Password:*" />
                            <TextInput id="password" type="password" v-model="props.form.password" class="block w-full mt-1"
                                placeholder="Contraseña" />
                            <InputError :message="props.form.errors.password" class="mt-2" />
                        </div>

                        <div class="mt-4 ">
                            <InputLabel for="cat_estados_civile_id" value="Rol:*" />
                            <Select v-model="props.form.rol_id" class="w-full">
                                <option v-for="rol in props.roles" :key="rol.id" :value="rol.id">
                                    {{ rol.nombre }}
                                </option>
                            </Select>

                            <InputError :message="props.form.errors.rol_id" class="mt-0" />
                        </div>
                        <div class="mt-4">
                            <InputLabel value="Correo Empresarial:*" />
                            <TextInput class="block w-full mt-1" type="email" v-model="props.form.correo_empresarial" />
                        </div>
                        <div class="mt-4">
                            <InputLabel value="Telefono Empresarial:*" />
                            <TextInput class="block w-full mt-1" type="tel" v-model="props.form.telefono_empresarial" />
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
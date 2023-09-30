<script setup>
import { computed } from "vue";
import { useForm, usePage } from "@inertiajs/inertia-vue3";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import InputSuccess from "@/Components/InputSuccess.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import SpinProgress from "@/Components/SpinProgress.vue";
import Select from "@/Components/Select.vue";
import { ObtenerCurp } from "../../../../utils/index.js";

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
    },
    editEmpleadoDisable: {
        type: Boolean,
        default: false,
    },
});

/*Funciones calculadas*/
const edad = computed(() => {
    if (props.form.fecha_nacimiento) {
        const date = new Date(props.form.fecha_nacimiento);
        let dateNow = new Date();
        let auxEdad = dateNow.getFullYear() - date.getFullYear();
        if (dateNow.getMonth() < date.getMonth()) auxEdad--;
        return auxEdad;
    } else {
        return "";
    }
});

const generateCurp = computed(() => {
    return ObtenerCurp(
        props.form.nombre,
        props.form.apellido_paterno,
        props.form.apellido_materno,
        props.form.fecha_nacimiento
    );
});

const messageCurp = computed(() => {
    if (props.form.curp !== "" && props.form.curp !== null) {
        if (
            props.form.curp.length === 18 &&
            props.form.curp.startsWith(generateCurp.value)
        ) {
            console.log(props.form.curp.length);
            props.form.errors.curp = "";
            return "CURP VALIDA";
        } else {
            console.log(props.form.curp.length);
            props.form.errors.curp = "CURP INVALIDA.";
            return "";
        }
    }
    return "";
});
const messageRFC = computed(() => {
    if (props.form.rfc != "" && props.form.rfc !== null) {
        if (
            props.form.rfc.length >= 12 &&
            props.form.rfc.startsWith(generateCurp.value)
        ) {
            props.form.errors.rfc = "";
            return "RCF VALIDO";
        } else {
            props.form.errors.rfc = "RFC INVALIDO.";
            return "";
        }
    }
    return "";
});

const showSendEmail = computed(() => {
    return props.form.id && props.form.rol_id;
});

const userEmailForm = useForm({});

const sendEmail = () => {
    userEmailForm.post(route("welcome.password.first", props.form.id), {
        preserveScroll: true,
        preserveState: true,
    });
};

const canEdit = computed(() => {
    return usePage().props.value.can["edit-users"];
});
</script>
<template>
    <!-- Datos Personales -->
    <div class="border-b tab">
        <div class="relative border-l-2 border-transparent">
            <input
                class="absolute z-10 w-full h-10 opacity-0 cursor-pointer top-6"
                type="checkbox"
                id="datosPersonales"
                checked
            />
            <header
                class="flex items-center justify-between p-5 pl-8 pr-8 cursor-pointer select-none tab-label"
                for="datosPersonales"
            >
                <span class="text-xl font-thin text-grey-darkest">
                    Datos Personales
                </span>
            </header>

            <div class="tab-content">
                <div class="pb-8 pl-8 pr-8">
                    <div class="grid grid-cols-3 gap-4">
                        <div class="mt-4">
                            <InputLabel
                                for="numero_empleado"
                                value="ID de Empleado:*"
                            />
                            <TextInput
                                v-if="canEdit"
                                id="numero_empleado"
                                type="text"
                                v-model="props.form.numero_empleado"
                                class="block w-full mt-1"
                                placeholder="No. Empleado"
                                :disabled="props.editEmpleadoDisable"
                            />
                            <p v-else class="block w-full mt-1">
                                {{ props.form.numero_empleado }}
                            </p>
                            <InputError
                                :message="props.form.errors.numero_empleado"
                                class="mt-2"
                            />
                        </div>
                        <div class="mt-4">
                            <InputLabel for="nombre" value="Nombre:*" />
                            <TextInput
                                v-if="canEdit"
                                id="nombre"
                                type="text"
                                v-model="props.form.nombre"
                                class="block w-full mt-1"
                                placeholder="Nombre"
                                :disabled="props.editEmpleadoDisable"
                            />
                            <p v-else class="block w-full mt-1">
                                {{ props.form.nombre }}
                            </p>
                            <InputError
                                :message="props.form.errors.nombre"
                                class="mt-2"
                            />
                        </div>
                        <div class="mt-4">
                            <InputLabel
                                for="apellido_paterno"
                                value="Apellido Paterno:*"
                            />
                            <TextInput
                                v-if="canEdit"
                                id="apellido_paterno"
                                type="text"
                                v-model="props.form.apellido_paterno"
                                class="block w-full mt-1"
                                placeholder="Apellido Paterno"
                                :disabled="props.editEmpleadoDisable"
                            />
                            <p v-else class="block w-full mt-1">
                                {{ props.form.apellido_paterno }}
                            </p>
                            <InputError
                                :message="props.form.errors.apellido_paterno"
                                class="mt-2"
                            />
                        </div>
                        <div class="mt-4">
                            <InputLabel
                                for="apellido_materno"
                                value="Apellido Materno:*"
                            />
                            <TextInput
                                v-if="canEdit"
                                id="apellido_materno"
                                type="text"
                                v-model="props.form.apellido_materno"
                                class="block w-full mt-1"
                                placeholder="Apellido Materno"
                                :disabled="props.editEmpleadoDisable"
                            />
                            <p v-else class="block w-full mt-1">
                                {{ props.form.apellido_materno }}
                            </p>
                            <InputError
                                :message="props.form.errors.apellido_materno"
                                class="mt-2"
                            />
                        </div>
                        <div class="mt-4">
                            <InputLabel
                                for="fecha_nacimiento"
                                value="Fecha de Nacimiento:*"
                            />
                            <TextInput
                                v-if="canEdit"
                                id="fecha_nacimiento"
                                type="date"
                                v-model="props.form.fecha_nacimiento"
                                class="block w-full mt-1"
                                placeholder="No. Empleado"
                                :disabled="props.editEmpleadoDisable"
                            />
                            <p v-else class="block w-full mt-1">
                                {{ props.form.fecha_nacimiento }}
                            </p>
                            <InputError
                                :message="props.form.errors.fecha_nacimiento"
                                class="mt-2"
                            />
                        </div>
                        <div class="mt-4">
                            <InputLabel for="edad" value="Edad:*" />
                            <TextInput
                                id="edad"
                                v-model="edad"
                                type="text"
                                disabled
                                class="block w-full mt-1"
                                placeholder="Edad"
                                :disabled="props.editEmpleadoDisable"
                            />
                        </div>
                        <div class="mt-4">
                            <InputLabel for="telefono" value="Telefono:*" />
                            <TextInput
                                v-if="canEdit"
                                id="telefono"
                                type="text"
                                v-model="props.form.telefono"
                                class="block w-full mt-1"
                                placeholder="+52 722 000 0000"
                                :disabled="props.editEmpleadoDisable"
                            />
                            <p v-else class="block w-full mt-1">
                                {{ props.form.telefono }}
                            </p>
                            <InputError
                                :message="props.form.errors.telefono"
                                class="mt-2"
                            />
                        </div>
                        <div class="mt-4">
                            <InputLabel for="curp" value="CURP:*" />
                            <TextInput
                                v-if="canEdit"
                                id="curp"
                                type="text"
                                v-model="props.form.curp"
                                class="block w-full mt-1"
                                :placeholder="generateCurp"
                                maxlength="18"
                                :disabled="props.editEmpleadoDisable"
                            />
                            <p v-else class="block w-full mt-1">
                                {{ props.form.curp }}
                            </p>
                            <InputError
                                :message="props.form.errors.curp"
                                class="mt-2"
                            />
                            <InputSuccess :message="messageCurp" class="mt-2" />
                        </div>
                        <div class="mt-4">
                            <InputLabel for="rfc" value="RFC:*" />
                            <TextInput
                                v-if="canEdit"
                                id="rfc"
                                type="text"
                                v-model="props.form.rfc"
                                class="block w-full mt-1"
                                placeholder="RFC"
                                maxlength="13"
                                :disabled="props.editEmpleadoDisable"
                            />
                            <p v-else class="block w-full mt-1">
                                {{ props.form.rfc }}
                            </p>
                            <InputError
                                :message="props.form.errors.rfc"
                                class="mt-2"
                            />
                            <InputSuccess :message="messageRFC" class="mt-2" />
                        </div>
                        <div class="mt-4">
                            <InputLabel
                                for="correo_electronico"
                                value="Correo:*"
                            />
                            <div class="flex gap-1">
                                <TextInput
                                    v-if="canEdit"
                                    id="correo_electronico"
                                    type="email"
                                    v-model="props.form.correo_electronico"
                                    class="block w-full mt-1"
                                    placeholder="correo@ejemplo.com"
                                    :disabled="props.editEmpleadoDisable"
                                />
                                <p v-else class="block w-full mt-1">
                                    {{ props.form.correo_electronico }}
                                </p>
                                <SecondaryButton
                                    v-if="showSendEmail"
                                    @click="sendEmail"
                                    class="py-0"
                                    :disabled="userEmailForm.processing"
                                >
                                    <SpinProgress
                                        :inprogress="userEmailForm.processing"
                                    />
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="currentColor"
                                        class="w-6 h-6"
                                        viewBox="0 0 16 16"
                                    >
                                        <path
                                            d="M2 2a2 2 0 0 0-2 2v8.01A2 2 0 0 0 2 14h5.5a.5.5 0 0 0 0-1H2a1 1 0 0 1-.966-.741l5.64-3.471L8 9.583l7-4.2V8.5a.5.5 0 0 0 1 0V4a2 2 0 0 0-2-2H2Zm3.708 6.208L1 11.105V5.383l4.708 2.825ZM1 4.217V4a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v.217l-7 4.2-7-4.2Z"
                                        />
                                        <path
                                            d="M14.247 14.269c1.01 0 1.587-.857 1.587-2.025v-.21C15.834 10.43 14.64 9 12.52 9h-.035C10.42 9 9 10.36 9 12.432v.214C9 14.82 10.438 16 12.358 16h.044c.594 0 1.018-.074 1.237-.175v-.73c-.245.11-.673.18-1.18.18h-.044c-1.334 0-2.571-.788-2.571-2.655v-.157c0-1.657 1.058-2.724 2.64-2.724h.04c1.535 0 2.484 1.05 2.484 2.326v.118c0 .975-.324 1.39-.639 1.39-.232 0-.41-.148-.41-.42v-2.19h-.906v.569h-.03c-.084-.298-.368-.63-.954-.63-.778 0-1.259.555-1.259 1.4v.528c0 .892.49 1.434 1.26 1.434.471 0 .896-.227 1.014-.643h.043c.118.42.617.648 1.12.648Zm-2.453-1.588v-.227c0-.546.227-.791.573-.791.297 0 .572.192.572.708v.367c0 .573-.253.744-.564.744-.354 0-.581-.215-.581-.8Z"
                                        />
                                    </svg>
                                </SecondaryButton>
                            </div>
                            <InputError
                                :message="props.form.errors.correo_electronico"
                                class="mt-2"
                            />
                        </div>
                        <div class="mt-4">
                            <InputLabel for="cat_genero_id" value="Genero:*" />
                            <Select
                                v-if="canEdit"
                                v-model="props.form.cat_genero_id"
                                class="w-full"
                                style="margin-top: 0.2rem"
                                :disabled="props.editEmpleadoDisable"
                            >
                                <option disabled selected>
                                    Elige un Genero
                                </option>
                                <option value="1">Masculino</option>
                                <option value="2">Femenino</option>
                            </Select>
                            <Select
                                v-else
                                v-model="props.form.cat_genero_id"
                                class="w-full"
                                style="margin-top: 0.2rem"
                                :disabled="true"
                            >
                                <option disabled selected>
                                    Elige un Genero
                                </option>
                                <option value="1">Masculino</option>
                                <option value="2">Femenino</option>
                            </Select>
                            <InputError
                                :message="props.form.errors.cat_genero_id"
                                class="mt-2"
                            />
                        </div>
                        <div class="mt-4">
                            <InputLabel
                                for="escolaridade_id"
                                value="Escolaridad:*"
                            />
                            <Select
                                v-if="canEdit"
                                v-model="props.form.escolaridade_id"
                                class="w-full"
                                :disabled="props.editEmpleadoDisable"
                            >
                                <option
                                    v-for="escolaridad in props.escolaridades"
                                    :key="escolaridad.id"
                                    :value="escolaridad.id"
                                >
                                    {{ escolaridad.nombre }}
                                </option>
                            </Select>
                            <Select
                                v-else
                                v-model="props.form.escolaridade_id"
                                class="w-full"
                                :disabled="true"
                            >
                                <option
                                    v-for="escolaridad in props.escolaridades"
                                    :key="escolaridad.id"
                                    :value="escolaridad.id"
                                >
                                    {{ escolaridad.nombre }}
                                </option>
                            </Select>
                            <InputError
                                :message="props.form.errors.escolaridade_id"
                                class="mt-2"
                            />
                        </div>
                        <div class="mt-4">
                            <InputLabel
                                for="cat_estados_civile_id"
                                value="Estado Civil:*"
                            />
                            <Select
                                v-if="canEdit"
                                v-model="props.form.cat_estados_civile_id"
                                class="w-full"
                                :disabled="props.editEmpleadoDisable"
                            >
                                <option
                                    v-for="estados_civil in props.estadosCiviles"
                                    :key="estados_civil.id"
                                    :value="estados_civil.id"
                                >
                                    {{ estados_civil.nombre }}
                                </option>
                            </Select>
                            <Select
                                v-else
                                v-model="props.form.cat_estados_civile_id"
                                class="w-full"
                                :disabled="true"
                            >
                                <option
                                    v-for="estados_civil in props.estadosCiviles"
                                    :key="estados_civil.id"
                                    :value="estados_civil.id"
                                >
                                    {{ estados_civil.nombre }}
                                </option>
                            </Select>
                            <InputError
                                :message="
                                    props.form.errors.cat_estados_civile_id
                                "
                                class="mt-2"
                            />
                        </div>
                        <div class="mt-4">
                            <InputLabel
                                for="contacto_emergencia"
                                value="Contacto de Emergencia:*"
                            />
                            <TextInput
                                v-if="canEdit"
                                id="contacto_emergencia"
                                type="text"
                                v-model="props.form.contacto_emergencia"
                                class="block w-full mt-1"
                                placeholder="Contacto de emergencia"
                                :disabled="props.editEmpleadoDisable"
                            />
                            <p v-else class="block w-full mt-1">
                                {{ props.form.contacto_emergencia }}
                            </p>
                            <InputError
                                :message="props.form.errors.contacto_emergencia"
                                class="mt-2"
                            />
                        </div>
                        <div class="mt-4">
                            <InputLabel for="hijos" value=" Hijos:*" />
                            <TextInput
                                v-if="canEdit"
                                id="hijos"
                                type="number"
                                v-model="props.form.hijos"
                                class="block w-full mt-1"
                                placeholder="Hijos"
                                :disabled="props.editEmpleadoDisable"
                            />
                            <p v-else class="block w-full mt-1">
                                {{ props.form.hijos }}
                            </p>
                            <InputError
                                :message="props.form.errors.hijos"
                                class="mt-2"
                            />
                        </div>

                        <div class="mt-4">
                            <InputLabel for="password" value="Password:*" />
                            <TextInput
                                v-if="canEdit"
                                id="password"
                                type="password"
                                v-model="props.form.password"
                                class="block w-full mt-1"
                                placeholder="Contraseña"
                                :disabled="props.editEmpleadoDisable"
                            />
                            <p v-else class="block w-full mt-1">
                                {{ props.form.password }}
                            </p>
                            <InputError
                                :message="props.form.errors.password"
                                class="mt-2"
                            />
                        </div>

                        <div class="mt-4">
                            <InputLabel
                                for="cat_estados_civile_id"
                                value="Rol:*"
                            />
                            <Select
                                v-if="canEdit"
                                v-model="props.form.rol_id"
                                class="w-full"
                                :disabled="props.editEmpleadoDisable"
                            >
                                <option
                                    v-for="rol in props.roles"
                                    :key="rol.id"
                                    :value="rol.id"
                                >
                                    {{ rol.nombre }}
                                </option>
                            </Select>
                            <Select
                                v-else
                                v-model="props.form.rol_id"
                                class="w-full"
                                :disabled="true"
                            >
                                <option
                                    v-for="rol in props.roles"
                                    :key="rol.id"
                                    :value="rol.id"
                                >
                                    {{ rol.nombre }}
                                </option>
                            </Select>
                            <InputError
                                :message="props.form.errors.rol_id"
                                class="mt-0"
                            />
                        </div>
                        <div class="mt-4">
                            <InputLabel value="Correo Empresarial:*" />
                            <TextInput
                                v-if="canEdit"
                                class="block w-full mt-1"
                                type="email"
                                v-model="props.form.correo_empresarial"
                                :disabled="props.editEmpleadoDisable"
                            />
                            <p v-else class="block w-full mt-1">
                                {{ props.form.correo_empresarial }}
                            </p>
                        </div>
                        <div class="mt-4">
                            <InputLabel value="Telefono Empresarial:*" />
                            <TextInput
                                v-if="canEdit"
                                class="block w-full mt-1"
                                type="tel"
                                v-model="props.form.telefono_empresarial"
                                :disabled="props.editEmpleadoDisable"
                            />
                            <p v-else class="block w-full mt-1">
                                {{ props.form.telefono_empresarial }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

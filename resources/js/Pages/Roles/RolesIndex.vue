<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import RolesTable from './Partials/RolesTable.vue';
import Pagination from '@/Components/Pagination.vue';
import { ref, reactive, watch } from 'vue';
import { throttle, pickBy } from 'lodash';
import RoleModal from './Modals/RoleModal.vue';
import ErrorModal from '@/Components/ErrorModal.vue';
import PermissionsTable from './Partials/PermissionsTable.vue';
import SelectComponent from '../../Components/SelectComponent.vue';
import InputLabel from '../../Components/InputLabel.vue';
import { Inertia } from '@inertiajs/inertia';

const props = defineProps({
    laravelRoles: {
        type: Object,
        required: true,
    },
    laravelPermissions: {
        type: Array,
        required: true,
    },
    laravelPlataformas: {
        type: Array,
        required: true,
    },
    filters: {
        require: true
    },
});

const roleSelect = ref({ id: -1 });
const typeForm = ref('create');
const showingModalRole = ref(false);
const showingModalError = ref(false)
const messageError = ref('');

const params = reactive({
    plataforma_id: props.filters.plataforma_id,
})


const showError = (message) => {
    if (message) {
        messageError.value = message;
        showingModalError.value = true
    } else {
        messageError.value = '';
        showingModalError.value = false
    }
}
const closeModalRole = () => {
    showingModalRole.value = false;
}

const createRole = () => {
    typeForm.value = 'create';
    roleSelect.value = { id: -1 }
    showingModalRole.value = true;
}


const editRole = (role) => {
    typeForm.value = 'update';
    roleSelect.value = role;
    showingModalRole.value = true;
}

const selectRole = async (role) => {
    roleSelect.value = role
}

//Wath plataforma
watch(params, throttle(function () {
    let paramsClear = pickBy(params);
    Inertia.get(route("roles.index"), paramsClear, {
        replace: true,
        only: ['laravelPermissions'],
        preserveScroll: true,
        preserveState: true,
    });
}, 150));


</script>

<template>
    <AppLayout title="Users">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Usuarios
            </h2>
        </template>

        <div class="px-2 py-6">
            <div class="grid grid-cols-1 md:grid-cols-2 ">
                <div class="px-1 py-2 overflow-hidden shadow-xl white-transparent sm:rounded-lg">
                    <h1 class="mx-10 mt-4 text-lg font-bold border-b-2">Roles de Usuario</h1>
                    <div class="overflow-hidden">
                        <RolesTable @create="createRole" @select="selectRole" @edit="editRole"
                            :roles="laravelRoles.data" :filters="filters" />
                        <Pagination :pagination="laravelRoles" />
                    </div>
                </div>
                <div class="px-1 ml-5 overflow-hidden shadow-xl white-transparent sm:rounded-lg">
                    <h1 class="mx-10 mt-4 text-lg font-bold border-b-2">Permisos</h1>
                    <div class="flex justify-center">
                        <InputLabel for="plataforma" vale="Plataforma" />
                        <SelectComponent v-model="params.plataforma_id">
                            <option value="">Selecciona una plataforma</option>
                            <option v-for="plataforma in laravelPlataformas" :key="plataforma.id"
                                :value="plataforma.id">
                                {{ plataforma.nombre }}
                            </option>
                        </SelectComponent>
                    </div>
                    <PermissionsTable :role="roleSelect" :permissions="laravelPermissions" @message-error="showError" />
                </div>
            </div>
        </div>
        <RoleModal :show="showingModalRole" :role="roleSelect" :type-form="typeForm" @close="closeModalRole" />
        <ErrorModal :show="showingModalError" @close="showError">
            {{ messageError }}
        </ErrorModal>
    </AppLayout>
</template>

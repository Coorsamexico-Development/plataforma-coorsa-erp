<script setup>
import DataTable from '@/Components/DataTable.vue';
import InputSearch from '@/Components/InputSearch.vue';
import Checkbox from '@/Components/Checkbox.vue';
import { computed, reactive, ref, watch } from 'vue';
import { throttle } from 'lodash';
const emit = defineEmits(['messageError'])


const props = defineProps({
    permissions: {
        type: Object,
        required: true
    },
    role: {
        type: Object,
        required: true
    }
})

const params = reactive({ field: '', search: '', direction: '' });
const permissChecked = ref([])
const getPermissonToRole = async () => {
    try {
        const response = await axios.get(route('roles.permissions', props.role.id));
        permissChecked.value = response.data.permissonId;
    } catch (error) {
        if (error.response) {
            console.log(error.response);
            let messageError = '';
            const messageServer = error.response.data.message
            if (error.response.status != 500) {
                messageError = messageServer;
            } else {
                messageError = 'Internal Server Error';
            }
            emit('messageError', messageError);
        }
    }
}
const setPermissonToRole = async (permission_id, checked) => {
    try {
        const response = await axios.put(route('roles.permissions', props.role.id), {
            permission_id,
            checked
        });
    } catch (error) {
        if (error.response) {
            console.log(error.response);
            let messageError = '';
            const messageServer = error.response.data.message
            if (error.response.status != 500) {
                messageError = messageServer;
            } else {
                messageError = 'Internal Server Error';
            }
            emit('messageError', messageError);
        }
    }
}

const sort = (field) => {
    params.field = field
    params.direction = params.direction === "asc" ? "desc" : "asc";
};


const permisionsSearch = computed(() => {
    let search = params.search.toLowerCase();
    let auxPermision = props.permissions.filter(item => {
        const objeto = JSON.stringify(item).toLowerCase();
        if (objeto.includes(search)) return item;
    });
    if (params.field !== '') {
        auxPermision.sort((a, b) => {
            let x = a[params.field].toLowerCase();
            let y = b[params.field].toLowerCase();
            if (params.direction === "asc") {
                if (x < y) { return -1; }
                if (x > y) { return 1; }
                return 0;
            } else {
                if (x > y) { return -1; }
                if (x < y) { return 1; }
                return 0;
            }
        });
    }
    return auxPermision;
});

const disableCheck = computed(() => {
    return props.role.id == -1
});

watch(props, throttle(function () {
    if (props.role.id != -1) {
        getPermissonToRole()
    }
}, 150))


</script>
<template>
    <DataTable>
        <template #section-header>
            <InputSearch v-model="params.search" />
        </template>
        <template #table-header>
            <tr class="header-sticky">
                <th scope="col" class="w-1/12 px-6 py-3 text-xs font-semibold tracking-wider uppercase ">
                    <span class="">
                    </span>
                </th>
                <th scope="col" class="w-6/12 px-6 py-3 text-xs font-semibold tracking-wider uppercase ">
                    <span class="" @click="sort('nombre')">Permiso
                        <svg v-if="params.field === 'nombre' && params.direction === 'asc'"
                            xmlns="http://www.w3.org/2000/svg" class="inline w-4 h-4" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path
                                d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z" />
                        </svg>
                        <svg v-if="params.field === 'name' && params.direction === 'desc'"
                            xmlns="http://www.w3.org/2000/svg" class="inline w-4 h-4" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path
                                d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z" />
                        </svg>
                    </span>
                </th>
            </tr>
        </template>
        <template #table-body>
            <tr v-for="(permission, index) in permisionsSearch" :key="index">
                <td class="w-1/12 px-4 py-4 text-sm ">
                    <Checkbox :name="'checke-' + index"
                        @change.self.stop="setPermissonToRole(permission.id, $event.target.checked)"
                        :value="'' + permission.id" v-model:checked="permissChecked" :disabled="disableCheck" />
                </td>
                <td class="w-1/12 px-4 py-4 text-sm ">
                    {{ permission.nombre }}
                </td>
            </tr>
        </template>
    </DataTable>
</template>

<template>
  <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <input 
      type="text" 
      v-model="search" 
      @input="searchUsers(search)" 
      placeholder="Buscar usuario por numero empleado o nombre..."
      class="w-80 flex mx-auto m-5 px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
    >
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400" v-if="porcUsers.length > 0" >
      <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
        <th class="px-6 py-3">Id</th>
        <th class="px-6 py-3">NO. empleado</th>
        <th class="px-6 py-3">Nombre</th>
        <th class="px-6 py-3">Apellido Paterno</th>
        <th class="px-6 py-3">Apellido Materno</th>
      </thead>
      <template v-for="(porcUser,index) in porcUsers">
        <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
        <td class="px-6 py-4">
          {{ porcUser.id }}
        </td>
        <td class="px-6 py-4">
          {{ porcUser.numero_empleado }}
        </td>
        <td class="px-6 py-4">
          {{ porcUser.name }}
        </td>
        <td class="px-6 py-4">
          {{ porcUser.apellido_paterno }}
        </td>
        <td class="px-6 py-4">
          {{ porcUser.apellido_materno }}
        </td>
        <td>
          <a :href="route('users.deducciones', porcUser.id)">
            <button type="button" 
                    class="text-blue-700 hover:text-white border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-500 dark:focus:ring-blue-800" >
              Deducciones
            </button>
          </a> 
        </td>
        </tr>
      </template>
    </table>
    <p v-else>No se encontraron usuarios.</p>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from "axios";

const search = ref(null);
const porcUsers = ref([]);

const searchUsers = async (search) => {
    axios
        .get(route('users.search', {search: search}))
        .then(({ data }) => {
            porcUsers.value = data;
        })
        .catch((e) => {
            console.log(e.response);
        });
};
</script>

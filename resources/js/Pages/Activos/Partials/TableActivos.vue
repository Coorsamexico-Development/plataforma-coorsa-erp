<script setup>

var props = defineProps(
    {
        activos:Object
    }
);

</script>
<template>
     <table class="table-auto w-full md:table-fixed">
        <thead class="drop-shadow-xl border-b-2 border-gray-300 text-gray-400 font-extralight" style="font-family: 'Montserrat';">
            <tr>
                <th>Acciones</th>
                <th>Codigo</th>
                <th>Alta</th>
                <th>Status</th>
                <th>Documento</th>
                <th>Usuarios</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="activo in activos" :key="activo.id">
               <td></td>
               <td></td>
               <td>{{ activo.fecha }}</td>
               <td  :class="{'text-green-500':activo.status, 'text-red-500':!activo.status }">
                <p v-if="activo.status">Activo</p>
                <p v-else>Inactivo</p>
              </td>
            </tr>
        </tbody>
    </table>
</template>
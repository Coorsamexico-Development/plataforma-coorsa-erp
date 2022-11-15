<script setup>
import { ref } from 'vue';
import DialogModal from '@/Components/DialogModal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DataTable from '@/Components/DataTable.vue';

var props = defineProps({
    user: Object
});

const emit = defineEmits(["close",])

const close = () => {
        
        emit('close');
    };

</script>
<template>
     <DialogModal :show="show" @close="close()">
           <template #title>
               <h2>Información</h2>
            </template>
            <template #content>
         <div style="margin:0.5rem">
            <DataTable>
                <template #table-header>
                    <tr scope="col" class="w-3/12 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">
                        <th>Nombre</th>
                        <th>Apellido paterno</th>
                        <th>Apellido materno</th>
                        <th>NSS</th>
                    </tr>
                 </template>
                 <template #table-body>
                    <tr>
                        <td  class="flex w-8 gap-1 px-2 py-4 text-sm">
                            {{user.name}}
                        </td>
                        <td  class="flex w-8 gap-1 px-2 py-4 text-sm">
                            {{user.apellido_paterno}}
                        </td>
                        <td  class="flex w-8 gap-1 px-2 py-4 text-sm">
                            {{user.apellido_materno}}
                        </td>
                        <td  class="flex w-8 gap-1 px-2 py-4 text-sm">
                            {{user.nss}}
                        </td>
                    </tr>
                 </template>
               </DataTable>
         </div>
            </template>
            <template #footer>
                <SecondaryButton  @click="close()" class="closeModal1" style="float:right">
                      Cerrar
                </SecondaryButton>
            </template>
        </DialogModal>
</template>
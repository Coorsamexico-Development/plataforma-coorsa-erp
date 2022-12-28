<script setup>
import { ref } from 'vue';
import DialogModal from '@/Components/DialogModal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DataTable from '@/Components/DataTable.vue';

var props = defineProps({
    descripcion: String
});

const emit = defineEmits(["close",])

const close = () => {
        
        emit('close');
    };

</script>
<template>
     <DialogModal :show="show" @close="close()">
           <template #title>
               <h2>Más información</h2>
            </template>
            <template #content>
                 {{ descripcion }}
            </template>
            <template #footer>
                <SecondaryButton  @click="close()" class="closeModal1" style="float:right">
                      Cerrar
                </SecondaryButton>
            </template>
        </DialogModal>
</template>
<script setup>
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import DialogModal from '@/Components/DialogModal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { usePage } from '@inertiajs/inertia-vue3'
import SelectComponent from '@/Components/SelectComponent.vue';
import Checkbox from '@/Components/Checkbox.vue';
import ButtonAdd from '@/Components/ButtonAdd.vue';
import TableCampos from '../Partials/TableCampos.vue'
import { Inertia } from "@inertiajs/inertia";
import axios from 'axios';

const emit = defineEmits(["close", "axios"]);

var props = defineProps(
    {
        campo:Object,
        campos:Object
    }
);

//console.log(props.campo)

const close = () => 
{
    emit('close');
};

</script>
<template>
         <DialogModal :maxWidth="'3xl'"  @close="close()">
           <template #title>
                 <h1>Campos de {{ campo.campo }}</h1>
            </template>
            <template #content>
              <div class="flex justify-center w-full">
                  <table class="w-full">
                     <thead>
                        <tr class="border-b ">
                           <th class="text-center p-8 pt-0 pb-1" v-for="campose in campos" :key="campose.id">
                             {{ campose.campo }}
                           </th>
                        </tr>
                     </thead>
                  </table>
              </div>
            </template>
            <template #footer>
                <SecondaryButton  @click="close()" class="closeModal1" style="float:right">
                      Cerrar
                </SecondaryButton>
            </template>
        </DialogModal>
</template>

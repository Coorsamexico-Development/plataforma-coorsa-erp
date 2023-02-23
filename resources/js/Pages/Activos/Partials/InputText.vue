<script setup>
import { onMounted, ref } from 'vue';

const emit = defineEmits(['updateCampo'])
var props = defineProps({
   disable:Boolean,
   valore:Object
});


let campoPivot = ref(
    {
        value:""
    }
)

if (props.valore)
{
   if(props.valore.valor == null)
   {
     campoPivot.value.value = "";
   } 
   else
    {
      campoPivot.value.value = props.valore.valor;
    }
}

const editCampo = () =>  //funcion para retornar el valor y actualizar en la BD
{
   //console.log("prueba");funciona si el input ya no esta en estado focus
   console.log(props.valore);
   if(props.valore)
   {
      emit('updateCampo',campoPivot.value, props.valore.campoId )
   }
   else
   {
    emit('updateCampo',campoPivot.value)
   }
 
}
</script>

<template>
    <input ref="input" 
        :disabled="disable"
         v-model="campoPivot.value"
         @blur="editCampo"
         style="border:1px solid black"
         class="border-indigo-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 read-only:bg-gray-300"
        >
</template>

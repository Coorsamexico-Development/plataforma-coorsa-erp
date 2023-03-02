<script setup>
import { onMounted, ref } from 'vue';

const emit = defineEmits(['updateCampo'])
var props = defineProps({
   disable:Boolean,
   columna:Object,
   fila:Object,
   valore:Object
});


let campoPivot = ref(
    {
        value:""
    }
)

if(props.valore)
{
   campoPivot.value.value = props.valore
}
else{
  campoPivot.value.value = ""
}

const editCampo = () =>  //funcion para retornar el valor y actualizar en la BD
{
   //console.log("prueba");funciona si el input ya no esta en estado focus
   //console.log(props.valore);
   if(props.columna)
   {
     if(props.fila)
     {
       emit('updateCampo',campoPivot.value, props.columna, props.fila)
     }
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

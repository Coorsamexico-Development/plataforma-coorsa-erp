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
);


if(props.valore)
{
   campoPivot.value.value = props.valore.valor
}
else
{
  campoPivot.value.value = ""
}


const editCampo = () =>  //funcion para retornar el valor y actualizar en la BD
{
   //console.log("prueba");funciona si el input ya no esta en estado focus
   //console.log(campoPivot.value)
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
    <input class="rounded-xl" 
    type="date"
    v-model="campoPivot.value"
    @change="editCampo" />
</template>
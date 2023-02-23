<script setup>
import { ref } from 'vue';
const emit = defineEmits(["close", "retornar" ])

var props = defineProps({
   valore:Object
});


let imagesArray = ref([]);
const recuperarArchivo = (fieldName, fileList) =>
{
    imagesArray.value = fileList;    
    if(props.valore)
    {
        emit('retornar', imagesArray.value, props.valore.campoId);
    }
    else
    {
        emit('retornar', imagesArray.value);
    }
}
</script>
<template>
   <input  @change="recuperarArchivo($event.target.name, $event.target.files);"  type="file"  />
</template>
<script setup>
import { ref } from 'vue';
const emit = defineEmits(["close", "retornar" ])

var props = defineProps({
    columna:Object,
    fila:Object
});


let imagesArray = ref([]);
const recuperarArchivo = (fieldName, fileList) =>
{
    imagesArray.value = fileList;    
    if(props.columna)
    {
        if(props.fila)
    {
        emit('retornar', imagesArray.value, props.columna, props.fila);
    }
    else
    {
        emit('retornar', imagesArray.value);
    }
    }
}
</script>
<template>
   <input  @change="recuperarArchivo($event.target.name, $event.target.files);"  type="file"  />
</template>
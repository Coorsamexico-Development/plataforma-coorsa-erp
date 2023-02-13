<script setup>
import { ref } from 'vue';
const emit = defineEmits(["close", "retornar" ])

let imagesArray = ref([]);
const recuperarArchivo = (fieldName, fileList) =>
{
    imagesArray.value = fileList;
    emit('retornar', imagesArray.value);
}
</script>
<template>
   <input  @change="recuperarArchivo($event.target.name, $event.target.files);"  type="file"  />
</template>
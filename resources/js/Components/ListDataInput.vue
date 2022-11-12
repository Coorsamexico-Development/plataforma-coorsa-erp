<script setup>
import { computed, onMounted, ref, watch, watchEffect } from 'vue';


const emit = defineEmits(['update:modelValue', 'value']);


const props = defineProps({
    'modelValue': {
        require: true
    },
    'list': {
        require: true
    },
    'disabled': {
        default: false
    },
    'options': {
        default: []
    },
    'keyOption': {
        type: String,
        default: 'id'
    },
    'nameOption': {
        type: String,
        default: 'name'
    },
    'value': {
        default: ''
    },
    'class': {
        default: ''
    }
})


const valueText = ref("");

const changeText = (text) => {
    valueText.value = text

    if (text === "" && props.modelValue === "-1") {
        emit('update:modelValue', '');
    }
}



const error = computed(() => {
    if (valueText.value !== "" || props.modelValue != "") {
        if (valueText.value !== "") {
            const selectOpcion = props.options.find(opcion => {
                return (opcion[props.nameOption] + "").toUpperCase() == (valueText.value + "").toUpperCase()
            });
            if (selectOpcion !== undefined) {
                emit('update:modelValue', selectOpcion[props.keyOption]);
            } else {

                emit('update:modelValue', "-1");
                return true;
            }
        } else {
            const selectOpcion = props.options.find(opcion => {
                return opcion[props.keyOption] == props.modelValue
            });
            if (selectOpcion !== undefined) {
                valueText.value = selectOpcion[props.nameOption]
            }

        }
    } else {
        emit('update:modelValue', '');
    }
    return false
})


const inputlist = ref(null);

watchEffect(() => {
    valueText.value = props.value
})

onMounted(() => {
    if (inputlist.value.hasAttribute('autofocus')) {
        inputlist.value.focus();
    }
});

defineExpose({ focus: () => inputlist.value.focus() });

</script>
<template>
    <div :class="'text-gray-600 ' + props.class">
        <input type="text" :list="list"
            class="block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 disabled:bg-gray-300"
            :class="{ 'border-red-400': error, 'text-red-400': error }" :value="valueText"
            @keyup="emit('value', valueText)" @input="changeText($event.target.value)" ref="inputlist"
            :disabled="disabled">
        <datalist :id="list">
            <option v-for="opcion in props.options" :key="opcion[props.keyOption]">
                {{ opcion[props.nameOption] }}
            </option>
        </datalist>
    </div>
</template>

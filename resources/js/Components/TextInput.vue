<script setup>
import { onMounted, ref } from "vue";

defineProps({
    modelValue: String | Number,
    disable: Boolean,
    min: {},
});

defineEmits(["update:modelValue"]);

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute("autofocus")) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        ref="input"
        style="border: 1px black solid"
        :disabled="disable"
        class="border-black rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 read-only:bg-gray-300"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
        :min="min"
    />
</template>

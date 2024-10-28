<script setup>
import { onMounted, ref } from "vue";

defineProps({
    modelValue: String | Number,
    disable: Boolean,
    min: {},
    keyPress: {
        type: String,
        default:
            "if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;",
    },
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
        :disabled="disable"
        class="border-0 rounded-full shadow-sm outline-none focus:border-0 focus:ring-0 read-only:bg-gray-300 ps-2 bg-[#F4F5F9] text-center"
        :onkeypress="keyPress"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
        :min="min"
    />
</template>

<script setup>
import { ref, onMounted } from "vue";

const props = defineProps({
    modelValue: String,
    disabled: {
        type: Boolean,
        default: false,
    },
    labelName: String,
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
    <div>
        <label :for="labelName">{{ labelName }}</label>
        <select
            :id="labelName"
            ref="input"
            :value="modelValue"
            @input="$emit('update:modelValue', $event.target.value)"
            class="block w-full py-0.5 text-[12px] text-[#989FB5] border-0 rounded-2xl ps-2 pe-6 bg-[#F4F5F9] focus:ring-0 focus:border-0"
        >
            <slot />
        </select>
    </div>
</template>

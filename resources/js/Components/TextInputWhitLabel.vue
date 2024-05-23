<script setup>
import { type } from "@amcharts/amcharts5";
import { onMounted, ref } from "vue";

defineProps({
    modelValue: String,
    placeholder: {
        type: String,
        required: true,
    },
    minlength: {
        type: String,
        default: null,
    },
    min: {
        type: String,
        default: null,
    },
    maxlength: {
        type: String,
        default: null,
    },
    max: {
        type: String,
        default: null,
    },
    required: {
        type: Boolean,
        default: false,
    },
    class: String,
    type: String,
    onkeypress: String,
    label: {
        type: String,
        default: "label",
    },
    id: {
        type: String,
        default: "id",
    },
    disabled: {
        type: Boolean,
        default: false,
    },
});

defineEmits(["input"]);

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute("autofocus")) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <div class="grid w-full">
        <label :for="id" class="uppercase ps-1 text-[13px] font-medium">
            {{ label }}
        </label>
        <input
            :id="id"
            ref="input"
            class="bg-[#F4F5F9] text-[#989FB5] border-0 rounded-lg focus:border-0 focus:ring-0 focus:outline-none text-[17px] h-fit px-[21px] py-[6px] w-full drop-shadow-[1px_1px_2px_#B5B5B5] disabled:bg-[#F2F2F2] font-light"
            :value="modelValue"
            @input="$emit('input', $event.target.value)"
            :placeholder="placeholder"
            :minlength="minlength"
            :maxlength="maxlength"
            :min="min"
            :max="max"
            :required="required"
            :type="type"
            :onkeypress="onkeypress"
            :class="class"
            :disabled="disabled"
        />
    </div>
</template>

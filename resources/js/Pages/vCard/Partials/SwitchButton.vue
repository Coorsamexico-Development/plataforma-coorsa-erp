<script setup>
import { ref, watchEffect } from "vue";

const props = defineProps({
    modelValue: {
        type: String,
        required: true,
    },
    disabled: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(["update:modelValue", "click"]);

const isChecked = ref(props.modelValue);

const handleCheckboxChange = () => {
    isChecked.value = !isChecked.value;
    emit("update:modelValue", isChecked.value);
};

watchEffect(() => {
    isChecked.value = props.modelValue;
});
</script>
<template>
    <label
        class="relative grid content-center w-fit"
        :class="disabled ? 'cursor-auto' : 'cursor-pointer'"
    >
        <div class="flex items-center">
            <input
                type="checkbox"
                :value="modelValue"
                class="sr-only peer disabled:cursor-auto"
                @change="handleCheckboxChange"
                @click="$emit('click', $event)"
                :checked="isChecked"
                :disabled="disabled"
            />
            <div
                class="w-14 h-5 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-9 rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute peer-checked:after:bg-[#000000] after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#0000001f]"
                :class="
                    !disabled
                        ? 'bg-[#0000001f] after:bg-[#000000]'
                        : 'bg-[#C8C8C8] after:bg-[#858585] after:border-[#858585]'
                "
            ></div>
        </div>
    </label>
</template>

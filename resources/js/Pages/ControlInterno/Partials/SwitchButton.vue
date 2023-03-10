<template>
    <div class="relative inline-block w-12 mr-2 align-middle transition duration-200 ease-in select-none ">
      <input type="checkbox" :value="value" v-model="proxyChecked"
             class="absolute block w-6 h-6 bg-white border-4 border-white rounded-full appearance-none cursor-pointer toggle-checkbox checked:border-white checked:bg-red-500"/>
      <label
      class="block h-6 overflow-hidden bg-[#F1687B] rounded-full cursor-pointer  checked:bg-red-500">
      </label>
    </div>
  </template>
  
  <script>
  import { defineComponent } from 'vue'
  
  export default defineComponent({
      emits: ['update:checked'],
  
      props: {
          checked: {
              type: [Array, Boolean],
              default: false,
          },
          value: {
              default: null,
          },
      },
  
      computed: {
          proxyChecked: {
              get() {
                  return this.checked;
              },
  
              set(val) {
                  this.$emit("update:checked", val);
              },
          },
      },
  })
  </script>
  
  
  
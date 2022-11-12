<template>
    <div class="relative inline-block w-10 mr-2 align-middle transition duration-200 ease-in select-none ">
  
      <input type="checkbox" :value="value" v-model="proxyChecked"
             class="absolute block w-6 h-6 bg-white border-4 rounded-full appearance-none cursor-pointer toggle-checkbox"/>
      <label
      class="block h-6 overflow-hidden bg-gray-300 rounded-full cursor-pointer toggle-label">
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
  
  
  
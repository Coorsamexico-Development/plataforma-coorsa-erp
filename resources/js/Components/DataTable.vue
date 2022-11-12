<template>
    <div class="py-12">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="flex flex-wrap mb-4 space-x-4 md:flex-row">
            <slot name="section-header"></slot>
        </div>
  
        <div class="overflow-hidden bg-white shadow-md sm:rounded-lg">
          <div class="flex flex-col">
            <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
              <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <div class="overflow-hidden border-b border-gray-200 shadow sm:rounded-lg">
                  <table class="min-w-full divide-y divide-gray-200 table-fixed">
                    <thead >
                      <tr>
                        <slot name="table-header"></slot>
                      </tr>
                    </thead>
  
                    <tbody class="text-sm text-gray-500 bg-white divide-y divide-gray-200 ">
                      <slot name="table-body"></slot>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import { defineComponent } from 'vue'
  
      export default defineComponent({
  
      })
  </script>
  
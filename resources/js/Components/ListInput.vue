<template>
    <div class="text-gray-600">
         <input type="text" :list="list"
         class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 disabled:bg-gray-300"
         :class="{'border-red-400': error, 'text-red-400':error}"
         :value="valueShow"
         @keyup="$emit('value', valueShow)"
         @input="opcionSelect($event.target.value)"
         ref="inputlist" :disabled="disabled">
         <datalist :id="list">
             <option v-for="opcion in opciones"
                 :key="opcion[keyOpcion]"
                 :data-value="opcion[keyOpcion]">
                 {{opcion[nameOpcion]}}
             </option>
         </datalist>
    </div>
 </template>
 
 <script>
     import {  defineComponent } from 'vue'
     export default defineComponent({
         props: {
                 'modelValue': {
                     require:true
                 },
                 'list': {
                     require:true
                 },
                 'disabled': {
                     require:false,
                     default:false
                 },
                 'opciones':{
                     default:[]
                 },
                 'keyOpcion': {
                     type: String,
                     default: 'id'
                 },
                 'nameOpcion': {
                     type: String,
                     default: 'nombre'
                 }
             },
         emits: ['update:modelValue', 'value'],
         data() {
            return {
                valueShow:'',
            }
         },
         methods: {
             focus() {
                 this.$refs.inputlist.focus()
             },
             opcionSelect: function(value){
                    this.valueShow =  value;
 
                    if(this.valueShow != ''){
                     const selectOpcion = this.opciones.find(opcion => {
                        return  opcion[this.nameOpcion] ==this.valueShow
                     });
                     if(selectOpcion){
                         this.$emit('update:modelValue', selectOpcion.id);
                     }else{
                         this.$emit('update:modelValue', -2);
                     }
                 }else{
                     this.$emit('update:modelValue', '');
                 }
             }
         },
         computed: {
             error: function(){
                 if(this.modelValue !=="" && this.modelValue !==-1){
 
                     const existOpcion = this.opciones.find((opcion) => {
                         return opcion[this.keyOpcion] == this.modelValue;
                     });
                     if(existOpcion != undefined){
                         this.valueShow = existOpcion[this.nameOpcion];
                         return false
                     }else{
                         return true;
                     }
                 }
                 return false;
             }
         }
     })
 </script>
 
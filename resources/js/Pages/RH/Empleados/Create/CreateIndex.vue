<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, useForm, usePage } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';
import { reactive, watch, computed } from 'vue';
import { ref } from 'vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import InputSuccess from '@/Components/InputSuccess.vue';
import Select from '@/Components/Select.vue';
import TextInput from '@/Components/TextInput.vue';
import ListInput from '@/Components/ListInput.vue';
import DropZone from '@/Components/DropZone.vue';
import ButtonSeccion from '@/Components/ButtonSeccion.vue';
import ButtonInfo from '@/Components/ButtonInfo.vue';
import ButtonAdd from '@/Components/ButtonAdd.vue';
import swal from 'sweetalert';
import ListInputVue from '../../../../Components/ListInput.vue';
import {ObtenerCurp} from '../../../../utils/index.js';

var props = defineProps(
    {
        escolaridades:Object,
        estados_civiles:Object,
        cat_tipo_sangre:Object,
        bancos:Object,
        departamentos:Object
    }
);

let typeForm = ref('create');

let catalogos = ref( {
                    departamentos: props.departamentos,
                    escolaridades: props.escolaridades,
                    bancos: props.bancos,
                    tiposContratos: [],
                    tiposSangres: props.cat_tipo_sangre,
                    motivosBajas: []
                })

let puestos = ref([]);

const fotografia = ref(null);


const form = useForm
({
    'numero_empleado': '',
    'banco_id': '',
    'escolaridade_id': '',
    'nombre': '',
    'apellido_paterno': '',
    'apellido_materno': '',
    'fecha_nacimiento': '',
    'fecha_ingreso': '',
    'fecha_ingreso_real': '',
    'fecha_baja': '',
    'departamento_id': '',
    'puesto_id': '',
    'nss': 0,
    'curp': '',
    'rfc': '',
    'contacto_emergencia': '',
    'telefono': '',
    'correo_electronico': '',
    'hijos': 0,
    'clave_bancaria': '',
    'numero_cuenta_bancaria': '',
    'direccion_estado_id': '',
    'direccion_municipio_id': '',
    'direccion_localidade_id': '',
    'calle': '',
    'numero': '',
    'colonia': '',
    'codigo_postal': '',
    'lote': '',
    'manzana': '',
    'cat_tipos_nomina_id': '',
    'tipos_contrato_id': '',
    'salario_bruto': 0.0,
    'salario_diario': 0.0,
    'salario_imss': 0.0,
    'bono_puntualidad': 0.0,
    'bono_asistencia': 0.0,
    'despensa': 1057.16,
    'fondo_ahorro': 0.0,
    'horario': '',
    'cat_estados_civile_id': '',
    'fotografia': '',
    'expediente': '',
    'contrato': '',
    'cat_tipos_sangre_id': '',
    'alergias': '',
    'enfermedades_cronicas': '',
    'cat_genero_id': '',
    'cat_bajas_empleado_id': '',
    'fecha_finiquito': '',
    'monto_finiquito': 0,
    'finiquito_pagado': false
});


const createOrUpdate = () => 
{
  if(typeForm.value  == 'create')
  {
    console.log(form.fotografia);
    form.post(route('empleado.store'), 
    {
        preserveScroll:true,
        preserveState:true,
        onSuccess: () => 
        {
          form.reset();
          swal("Create", "Exitosamente", "success");
        },
        onError: (error) =>
         {
            console.log(error.response);
            swal("Error al crear", "Favor de validar los datos", "error");
         }
    });
  }

}

/*Obtener datos direcciones*/ 
const getEstados = () => 
{
     axios.get(route('catalogos.formularioEmpleado')).then( (response) => 
    {
      catalogos.value.estadosDireccion = response.data.estadosDireccion;
      //console.log(response.data);
              }).catch(error => {
                if (error.response) {
                    let messageError = '';
                    const messageServer = error.response.data.message
                    if(error.response.status != 500){
                       messageError = messageServer;
                    }else{
                        messageError = 'Internal Server Error';
                    }
                    console.log(error.response);
                    swal("Error get Catalogos", messageError, "error");
                }
    });
}



const getMunicipios  = () =>
{
    if(form.direccion_estado_id !== '' && form.direccion_estado_id > 0)
    {
         axios.get(route('municipos.estado', form.direccion_estado_id)).
                then((response) => {
                        catalogos.value.municipiosDireccion = response.data;
                    }).catch(error =>{
                        if (error.response) {
                            let messageError = '';
                            const messageServer = error.response.data.message
                            if(error.response.status != 500){
                                messageError = messageServer;
                            }else{
                                messageError = 'Internal Server Error';
                            }
                            console.log(error.response);
                            swal("Error get Municipios", messageError, "error");
                        }
                    });
    }
}

const getLocalidades =  () => 
{
    if(form.direccion_municipio_id !== '' && form.direccion_municipio_id > 0)
    {
        axios.get(route('localidades.municipio', form.direccion_municipio_id)).
           then((response) => {
               catalogos.value.localidadesDireccion = response.data;
           }).catch(error =>{
               if (error.response) {
                   let messageError = '';
                   const messageServer = error.response.data.message
                   if(error.response.status != 500){
                       messageError = messageServer;
                   }else{
                       messageError = 'Internal Server Error';
                   }
                   console.log(error.response);
                   swal("Error get Localidades", messageError, "error");
               }
           });
    }
}

/*OBTENCION DE PUESTOS*/
const getPuestos = () =>
{
    axios.get(route('departamento.puestos.list', form.departamento_id))
            .then( (response) => 
            {
                puestos.value = response.data
           }).catch(error => 
           {
             if (error.response) 
             {
                let messageError = '';
                const messageServer = error.response.data.message
                if(error.response.status != 500)
                {
                  messageError = messageServer;
                }
                else
                {
                  messageError = 'Internal Server Error';
                }
                 swal('Error get Puesto Departamento', messageError, 'error')
              }
         });
}


/*CAMBIAR ELEMENTO*/ 

let buttonSelected = ref(1);

const cambiar = (id) => 
{
  buttonSelected.value = id;
}

/*Funciones calculadas*/
const edad = computed(() => 
{
    let date;
    if(form.fecha_nacimiento)
    {
        date = new  Date(form.fecha_nacimiento);
        let dateNow = new Date();
        let auxEdad = dateNow.getFullYear()-date.getFullYear();
        if(dateNow.getMonth() < date.getMonth()) auxEdad--;
        return auxEdad;
     }
     else
     {
        return ""
     }
});

const generateCurp = computed(() => 
{
    return ObtenerCurp(form.nombre, form.apellido_paterno, form.apellido_materno, form.fecha_nacimiento);  
});

const fecha_termino = computed(() => 
{
    let date;
    if(form.fecha_ingreso_real){
        date =new Date(form.fecha_ingreso_real);
    }else
    {
        date =new  Date();
    }

    date.setDate(date.getDate() + 30);
    let month = date.getMonth()+1;
    if(month<10){
        month = '0'+month;
    }
    let day = date.getDate();
    if(day<10)
    {
        day = '0'+day;
    }
    return date.getFullYear()  + "-" + month + "-" +day;
});

</script>

<template>
    <app-layout title="Dashboard">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
              Nuevo Empleado
            </h2>
        </template>

        <div class="space">
          <div class="pb-1 pr-1">
           <div class="grid grid-cols-4 gap-1">
               <div class="divseccion"  style="margin-right:0rem">
                  <ButtonSeccion style="width:10rem" @click="cambiar(1)" href="1">
                    <div class="grid grid-rows-2 gap-1" style="justify-items:center;">
                      <img  :src="'/assets/img/datos_personales.png'"/>
                      <p>Datos Personales</p>
                    </div>
                  </ButtonSeccion>
               </div>
               <div class="divseccion" >
                <ButtonSeccion  style="width:10rem" @click="cambiar(2)">
                    <div class="grid grid-rows-2 " style="justify-items:center;">
                      <img  :src="'/assets/img/direccion.png'"/>
                      <p>Dirección del Empleado</p>
                    </div>
                  </ButtonSeccion>
               </div>
               <div class="divseccion">
                  <ButtonSeccion style="width:10rem" @click="cambiar(3)">
                    <div class="grid grid-rows-2 " style="justify-items:center;">
                      <img  :src="'/assets/img/datos_bancarios.png'"/>
                      <p>Datos Bancarios</p>
                    </div>
                  </ButtonSeccion>
               </div>
               <div class="divseccion">
                <ButtonSeccion style="width:10rem" @click="cambiar(4)">
                    <div class="grid grid-rows-2 " style="justify-items:center;">
                      <img  :src="'/assets/img/datos_laborales.png'"/>
                      <p>Datos Laborales</p>
                    </div>
                  </ButtonSeccion>
               </div>
               <div class="divseccion">
                <ButtonSeccion style="width:10rem" @click="cambiar(5)">
                    <div class="grid grid-rows-2 " style="justify-items:center;">
                      <img  :src="'/assets/img/datos_monetarios.png'"/>
                      <p>Datos Monetarios</p>
                    </div>
                  </ButtonSeccion>
               </div>
               <div class="divseccion">
                <ButtonSeccion style="width:10rem" @click="cambiar(6)">
                    <div class="grid grid-rows-2 " style="justify-items:center;">
                      <img  :src="'/assets/img/datos_salud.png'"/>
                      <p>Aspectos Generales De Salud</p>
                    </div>
                  </ButtonSeccion>
               </div>
               <div class="divseccion">
                <ButtonSeccion style="width:10rem" @click="cambiar(7)">
                    <div class="grid grid-rows-2 " style="justify-items:center;">
                      <img  :src="'/assets/img/datos_salud.png'"/>
                      <p>Archivos expedientes</p>
                    </div>
                  </ButtonSeccion>
               </div>
               <div class="divseccion"  v-if="typeForm == 'update'">
                <ButtonSeccion style="width:10rem" @click="cambiar(8)">
                    <div class="grid grid-rows-2 " style="margin-left: 1.5rem;justify-content: center;">
                      <img style="margin-left:0.8rem"  :src="'/assets/img/finiquitos.png'"/>
                      <p>Finiquitos</p>
                    </div>
                  </ButtonSeccion>
               </div>
            </div>
         </div>
        </div>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg" style="padding:6rem">
                  <div class="pb-8 pl-8 pr-8">
                     <!-- Datos Personales -->
                       <div v-if="buttonSelected == 1" v-show="true" id="1">
                          <div class="border-b tab">
                              <div class="relative border-l-2 border-transparent">
                                  <input class="absolute z-10 w-full h-10 opacity-0 cursor-pointer top-6"
                                  type="checkbox" id="datosPersonales" checked>
                                  <header class="flex items-center justify-between p-5 pl-8 pr-8 cursor-pointer select-none tab-label" for="datosPersonales">
                                      <span class="text-xl font-thin text-grey-darkest">
                                          Datos Personales
                                      </span>           
                                  </header>
                                  <div class="tab-content">
                                      <div class="pb-8 pl-8 pr-8">
                                          <div class="grid grid-cols-3 gap-4">
                                              <div class="mt-4">
                                                  <InputLabel for="numero_empleado" value="ID de Empleado:*" />
                                                  <TextInput id="numero_empleado" type="text" v-model="form.numero_empleado"
                                                  class="block w-full mt-1"  placeholder="No. Empleado" :disabled="editEmpleadoDisable"/>
                                                  <InputError :message="form.errors.numero_empleado" class="mt-2" />
                                              </div>
                                              <div class="mt-4">
                                                  <InputLabel for="nombre" value="Nombre:*" />
                                                  <TextInput id="nombre" type="text" v-model="form.nombre"
                                                  class="block w-full mt-1"  placeholder="Nombre"  :disabled="editEmpleadoDisable"/>
                                                  <InputError :message="form.errors.nombre" class="mt-2" />
                                              </div>
                                              <div class="mt-4">
                                                  <InputLabel for="apellido_paterno" value="Apellido Paterno:*" />
                                                  <TextInput id="apellido_paterno" type="text" v-model="form.apellido_paterno"
                                                  class="block w-full mt-1"  placeholder="Apellido Paterno"  :disabled="editEmpleadoDisable" />
                                                  <InputError :message="form.errors.apellido_paterno" class="mt-2" />
                                              </div>
                                              <div class="mt-4">
                                                  <InputLabel for="apellido_materno" value="Apellido Materno:*" />
                                                  <TextInput id="apellido_materno" type="text" v-model="form.apellido_materno"
                                                  class="block w-full mt-1"  placeholder="Apellido Materno"  :disabled="editEmpleadoDisable"/>
                                                  <InputError :message="form.errors.apellido_materno" class="mt-2" />
                                              </div>
                                              <div class="mt-4">
                                                  <InputLabel for="fecha_nacimiento" value="Fecha de Nacimiento:*" />
                                                  <TextInput id="fecha_nacimiento" type="date" v-model="form.fecha_nacimiento"
                                                  class="block w-full mt-1"  placeholder="No. Empleado"  :disabled="editEmpleadoDisable"/>
                                                  <InputError :message="form.errors.fecha_nacimiento" class="mt-2" />
                                              </div>
                                              <div class="mt-4">
                                                  <InputLabel for="edad" value="Edad:*" />
                                                  <TextInput id="edad" v-model="edad" type="text" disabled
                                                  class="block w-full mt-1"  placeholder="Edad" />
                                              </div>
                                              <div class="mt-4">
                                                  <InputLabel for="telefono" value="Telefono:*" />
                                                  <TextInput id="telefono" type="text" v-model="form.telefono"
                                                  class="block w-full mt-1"  placeholder="+52 722 000 0000" :disabled="editEmpleadoDisable"/>
                                                  <InputError :message="form.errors.telefono" class="mt-2"  />
                                              </div>
                                              <div class="mt-4">
                                                  <InputLabel for="curp" value="CURP:*" />
                                                  <TextInput id="curp" type="text" v-model="form.curp"
                                                  class="block w-full mt-1"  :placeholder="generateCurp" maxlength="18"  :disabled="editEmpleadoDisable"/>
                                                  <InputError :message="form.errors.curp" class="mt-2" />
                                                  <InputSuccess :message="messageCurp" class="mt-2" />
                                              </div>
                                              <div class="mt-4">
                                                  <InputLabel for="rfc" value="RFC:*" />
                                                  <TextInput id="rfc" type="text" v-model="form.rfc"
                                                  class="block w-full mt-1"  placeholder="RFC" maxlength="13"  :disabled="editEmpleadoDisable"/>
                                                  <InputError :message="form.errors.rfc" class="mt-2" />
                                                  <InputSuccess :message="messageRFC" class="mt-2" />
                                              </div>
                                              <div class="mt-4">
                                                  <InputLabel for="correo_electronico" value="Correo:*" />
                                                  <TextInput  id="correo_electronico" type="text" v-model="form.correo_electronico"
                                                  class="block w-full mt-1"  placeholder="correo@ejemplo.com"  :disabled="editEmpleadoDisable"/>
                                                  <InputError :message="form.errors.correo_electronico" class="mt-2" />
                                              </div>
                                              <div class="mt-4">
                                                  <InputLabel for="cat_genero_id"  value="Genero:*" />
                                                  <Select v-model="form.cat_genero_id" class="w-full"  style="margin-top:0.2rem" :disabled="editEmpleadoDisable">
                                                      <option disabled selected>Elige un Genero</option>
                                                      <option value="1">Masculino</option>
                                                      <option value="2">Femenino</option>
                                                  </Select>
                                                  <InputError :message="form.errors.cat_genero_id" class="mt-2" />
               
                                              </div>
                                              <div class="mt-4">
                                                  <InputLabel for="escolaridade_id"  value="Escolaridad:*" />
                                                  <Select v-model="form.escolaridade_id" class="w-full"  :disabled="editEmpleadoDisable">
                                                      <option v-for="escolaridad in catalogos.escolaridades" :key="escolaridad.id" :value="escolaridad.id">
                                                          {{escolaridad.nombre}}
                                                      </option>
                                                  </Select>
                                                  <InputError :message="form.errors.escolaridade_id" class="mt-2" />
                                              </div>
                                              <div class="mt-4">
                                                  <InputLabel for="cat_estados_civile_id" value="Estado Civil:*" />
                                                  <Select v-model="form.cat_estados_civile_id" class="w-full"  :disabled="editEmpleadoDisable">
                                                      <option v-for="estados_civil in estados_civiles" :key="estados_civil.id" :value="estados_civil.id">
                                                          {{estados_civil.nombre}}
                                                      </option>
                                                  </Select>
                                                  <InputError :message="form.errors.cat_estados_civile_id" class="mt-2" />
                                              </div>
                                              <div class="mt-4">
                                                  <InputLabel for="contacto_emergencia" value="Contacto de Emergencia:*" />
                                                  <TextInput id="contacto_emergencia" type="text" v-model="form.contacto_emergencia"
                                                  class="block w-full mt-1"  placeholder="Contacto de emergencia"  :disabled="editEmpleadoDisable"/>
                                                  <InputError :message="form.errors.contacto_emergencia" class="mt-2" />
                                              </div>
                                              <div class="mt-4">
                                                  <InputLabel for="hijos" value=" Hijos:*" />
                                                  <TextInput id="hijos" type="number" v-model="form.hijos"
                                                  class="block w-full mt-1"  placeholder="Hijos"  :disabled="editEmpleadoDisable"/>
                                                  <InputError :message="form.errors.hijos" class="mt-2" />
                                              </div>
               
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>
                       </div>
                     <!-- Fin Datos Personales -->
                     <!-- Dirección del Empleado -->
                       <div v-if="buttonSelected == 2">  
                         <div class="border-b tab">
                             <div class="relative border-l-2 border-transparent">
                                 <input class="absolute z-10 w-full h-10 opacity-0 cursor-pointer top-6"
                                  type="checkbox" id="datosPersonales" checked>
                                 <header class="flex items-center justify-between p-5 pl-8 pr-8 cursor-pointer select-none tab-label" for="datosPersonales">
                                     <span class="text-xl font-thin text-grey-darkest">
                                     Dirección del Empleado
                                     </span>
                                     <div class="flex items-center justify-center border rounded-full border-grey w-7 h-7 test">
                                         <svg aria-hidden="true" class="" data-reactid="266" fill="none" height="24" stroke="#606F7B" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewbox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
                                             <polyline points="6 9 12 15 18 9">
                                             </polyline>
                                         </svg>
                                     </div>
                                 </header>
                                 <div class="tab-content">
                                     <div class="pb-5 pl-8 pr-8">
                                         <div class="grid grid-cols-3 gap-4">
                                             <div class="mt-4">
                                                 <InputLabel for="calle" value="Calle:*" />
                                                 <TextInput id="calle" type="text" v-model="form.calle"
                                                 class="block w-full mt-1"  placeholder="Calle"   :disabled="editEmpleadoDisable"/>
                                                 <InputError :message="form.errors.calle" class="mt-2" />
                                             </div>
                                             <div class="mt-4">
                                                 <InputLabel for="numero" value="Colonia:*" />
                                                 <TextInput id="numero" type="text" v-model="form.numero"
                                                 class="block w-full mt-1"  placeholder="Nombre"   :disabled="editEmpleadoDisable"/>
                                                 <InputError :message="form.errors.numero" class="mt-2" />
                                             </div>
                                             <div class="mt-4">
                                                 <InputLabel for="colonia" value="Número:*" />
                                                 <TextInput id="colonia" type="text" v-model="form.colonia"
                                                 class="block w-full mt-1"  placeholder="Número"   :disabled="editEmpleadoDisable"/>
                                                 <InputError :message="form.errors.colonia" class="mt-2" />
                                             </div>
                                             <div class="mt-4">
                                                 <InputLabel for="codigo_postal" value="CP:*" />
                                                 <TextInput id="codigo_postal" type="number" maxlength="5" v-model="form.codigo_postal"
                                                 class="block w-full mt-1"  placeholder="Codigo Postal"   :disabled="editEmpleadoDisable"/>
                                                 <InputError :message="form.errors.codigo_postal" class="mt-2" />
                                             </div>
                                             <div class="mt-4">
                                                 <InputLabel for="lote" value="Lote:*" />
                                                 <TextInput id="lote" type="text" v-model="form.lote"
                                                 class="block w-full mt-1"  placeholder="Lote"   :disabled="editEmpleadoDisable"/>
                                                 <InputError :message="form.errors.lote" class="mt-2" />
                                             </div>
                                             <div class="mt-4">
                                                 <InputLabel for="manzana" value="Manzana:*" />
                                                 <TextInput id="manzana" type="text" v-model="form.manzana"
                                                 class="block w-full mt-1"  placeholder="Manzana"   :disabled="editEmpleadoDisable"/>
                                                 <InputError :message="form.errors.manzana" class="mt-2" />
                                             </div>
                                            
                                             <!-- ----------------------------------------------------------------- -->
                                             <div class="mt-4">
                                                 <InputLabel for="direccion_estado_id"  value="Estado:*" />
                                                 <ListInputVue list="listaEstados"
                                                   :disabled="editEmpleadoDisable"
                                                   v-model="form.direccion_estado_id" :options="catalogos.estadosDireccion"
                                                   class="block w-full mt-1" @click="getEstados()" />
                                                 <InputError :message="form.errors.direccion_estado_id" class="mt-2" />
                                             </div>
                                             <div class="mt-4">
                                                  <InputLabel for="direccion_municipio_id"  value="Municipio:*" />
                                                  <ListInputVue v-if="form.direccion_estado_id != 0" list="listaMunicipios"  :disabled="false"
                                                  v-model="form.direccion_municipio_id"  :options="catalogos.municipiosDireccion"
                                                  class="block w-full mt-1" @click="getMunicipios()" />
                                                  <ListInputVue v-if="form.direccion_estado_id == 0" list="listaMunicipios"  :disabled="true" />
                                                 <InputError :message="form.errors.direccion_municipio_id"  class="mt-2"/>
                                             </div>
                                             <div class="mt-4">
                                                 <InputLabel for="direccion_localidade_id"  value="Localidad:*" />
                                                 <ListInputVue list="listaLocalidades" 
                                                  v-if="form.direccion_municipio_id != 0"
                                                   :disabled="false"
                                                  v-model="form.direccion_localidade_id"  :options="catalogos.localidadesDireccion"
                                                  class="block w-full mt-1" @click="getLocalidades()" />
                                                  <ListInputVue v-if="form.direccion_municipio_id == 0" :disabled="true" />
                                                 <InputError :message="form.errors.direccion_localidade_id"  class="mt-2"/>
                                             </div>
                                             <!-- ----------------------------------------------------------------- -->
                                         </div>
                                     </div>
                                 </div>
                             </div>
                         </div>
                       </div>
                    <!--Fin Dirección del Empleado -->
                    <!-- Datos Bancarios -->
                       <div v-if="buttonSelected == 3">
                         <div class="border-b tab">
                             <div class="relative border-l-2 border-transparent">
                                 <input class="absolute z-10 w-full h-10 opacity-0 cursor-pointer top-6" type="checkbox" id="datosPersonale" checked> 
                                 <header class="flex items-center justify-between p-5 pl-8 pr-8 cursor-pointer select-none tab-label" for="datosPersonales">
                                     <span class="text-xl font-thin text-grey-darkest">
                                     Datos Bancarios
                                     </span>
                                     <div class="flex items-center justify-center border rounded-full border-grey w-7 h-7 test">
                                         <svg aria-hidden="true" class="" data-reactid="266" fill="none" height="24" stroke="#606F7B" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewbox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
                                             <polyline points="6 9 12 15 18 9">
                                             </polyline>
                                         </svg>
                                     </div>
                                 </header>
                                 <div class="tab-content">
                                     <div class="pb-5 pl-8 pr-8">
                                         <div class="grid grid-cols-3 gap-4">
                                             <div class="col-span-3">
                                                 <InputLabel for="banco_id"  value="Banco:*" />
                                                 <Select v-model="form.banco_id" class="w-full" :disabled="editEmpleadoDisable">
                                                     <option v-for="banco in catalogos.bancos" :key="banco.id" :value="banco.id">
                                                         {{banco.nombre}}
                                                     </option>
                                                 </Select>
                                                 <InputError :message="form.errors.banco_id" class="mt-2" />
                                             </div>
                                             <div class="mt-4">
                                                 <InputLabel for="clave_bancaria" value="Clave Interbancaria:*" />
                                                 <TextInput id="clave_bancaria" type="text" v-model="form.clave_bancaria"
                                                 class="block w-full mt-1"  placeholder="Clave Interbancaria" :disabled="editEmpleadoDisable"/>
                                                 <InputError :message="form.errors.clave_bancaria" class="mt-2" />
                                             </div>
                                             <div class="mt-4">
                                                 <InputLabel for="numero_cuenta_bancaria" value="No. Cuenta:*" />
                                                 <TextInput id="numero_cuenta_bancaria" type="text" v-model="form.numero_cuenta_bancaria"
                                                 class="block w-full mt-1"  placeholder="No. Cuenta" :disabled="editEmpleadoDisable"/>
                                                 <InputError :message="form.errors.numero_cuenta_bancaria" class="mt-2" />
                                             </div>
                                         </div>
                                     </div>
                                 </div>
                             </div>
                         </div>
                       </div>
                    <!-- Fin  Datos Bancarios -->
                    <!-- Datos Laborales -->
                       <div v-if="buttonSelected == 4">
                          <div class="border-b tab">
                              <div class="relative border-l-2 border-transparent">
                                  <input class="absolute z-10 w-full h-10 opacity-0 cursor-pointer top-6" type="checkbox" id="datosPersonales" checked>
                                  <header class="flex items-center justify-between p-5 pl-8 pr-8 cursor-pointer select-none tab-label" for="datosPersonales">
                                      <span class="text-xl font-thin text-grey-darkest">
                                      Datos Laborales
                                      </span>
                                      <div class="flex items-center justify-center border rounded-full border-grey w-7 h-7 test">
                                          <svg aria-hidden="true" class="" data-reactid="266" fill="none" height="24" stroke="#606F7B" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewbox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
                                              <polyline points="6 9 12 15 18 9">
                                              </polyline>
                                          </svg>
                                      </div>
                                  </header>
                                 <div class="tab-content">
                                      <div class="pb-5 pl-8 pr-8">
                                          <div class="grid grid-cols-3 gap-4">
                                              <div class="mt-4">
                                                  <InputLabel for="departamento_id"  value="Departamento:*" />
                                                  <ListInputVue v-model="form.departamento_id" list="departamentos"
                                                      @change="getPuestos"
                                                      class="w-full" :options="catalogos.departamentos" />
              
                                                  <InputError :message="form.errors.departamento_id" class="mt-2" />
                                              </div>
                                              <div class="mt-4">
                                                  <InputLabel for="puesto_id"  value="Puesto:*" />
                                                  <Select v-model="form.puesto_id" class="w-full" :disabled="editEmpleadoDisable">
                                                       <option v-for="puesto in puestos" :key="puesto.id" :value="puesto.id">
                                                          {{puesto.name}}
                                                      </option>
                                                  </Select>
                                                  <InputError :message="form.errors.puesto_id" class="mt-2" />
                                              </div>
              
                                              <div class="mt-4">
                                                  <InputLabel for="horario"  value="Horario Laboral:*" />
                                                  <Select v-model="form.horario" class="w-full" :disabled="editEmpleadoDisable">
                                                      <option disabled selected>Elige un Horario</option>
                                                      <option value="oficina">Oficina</option>
                                                      <option value="turnos">Rolar Turnos</option>
                                                  </Select>
                                                  <InputError :message="form.errors.horario" class="mt-2" />
                                              </div>
              
                                              <div class="mt-4">
                                                  <InputLabel for="nss" value="Número de Seguridad Social:*" />
                                                  <TextInput id="nss" type="text" v-model="form.nss"
                                                  class="block w-full mt-1"  placeholder="Número de Seguridad Social" :disabled="editEmpleadoDisable"/>
                                                  <InputError :message="form.errors.nss" class="mt-2" />
                                              </div>
                                              <div class="mt-4">
                                                  <InputLabel for="fecha_ingreso" value="Fecha de Ingreso:*" />
                                                  <TextInput id="fecha_ingreso" type="date" v-model="form.fecha_ingreso"
                                                  class="block w-full mt-1"  placeholder="Fecha de Ingreso" max="2030-12-12" :disabled="editEmpleadoDisable" />
                                                  <InputError :message="form.errors.fecha_ingreso" class="mt-2"  />
                                              </div>
                                              <div class="mt-4">
                                                  <InputLabel for="fecha_ingreso_real" value="Fecha Real:*" />
                                                  <TextInput id="fecha_ingreso_real" type="date" v-model="form.fecha_ingreso_real"
                                                  class="block w-full mt-1"  placeholder="Fecha Real"  max="2030-12-12" :disabled="editEmpleadoDisable"/>
                                                  <InputError :message="form.errors.fecha_ingreso_real" class="mt-2"/>
                                              </div>
                                              <div class="mt-4">
                                                  <InputLabel for="cat_tipos_nomina_id"  value="Tipo de Nomina:*" />
                                                  <Select v-model="form.cat_tipos_nomina_id" class="w-full"  :disabled="editEmpleadoDisable">
                                                      <option disabled selected>Elige un tipo de nomina</option>
                                                      <option value="1">Semanal</option>
                                                      <option value="2">Quincenal</option>
                                                  </Select>
                                                  <InputError :message="form.errors.cat_tipos_nomina_id" class="mt-2" />
                                              </div>
                                              <div class="mt-4">
                                                  <InputLabel for="tipos_contrato_id"  value="Tipo de Contrato:*" />
                                                  <Select v-model="form.tipos_contrato_id" class="w-full"  :disabled="editEmpleadoDisable">
                                                      <option v-for="tipo_contrato in catalogos.tiposContratos" :key="tipo_contrato.id"  :value="tipo_contrato.id">
                                                          {{tipo_contrato.nombre}}
                                                      </option>
                                                  </Select>
                                                  <InputError :message="form.errors.tipos_contrato_id" class="mt-2" />
                                              </div>
                                              <div v-if="form.tipos_contrato_id == 1" class="mt-4">
                                                  <InputLabel for="fecha_termino" value="Fecha de Termino:" />
                                                  <TextInput id="fecha_termino" type="date"  v-model="fecha_termino"
                                                  class="block w-full mt-1" disabled/>
                                              </div>
                                          </div>
                                      </div>
                                 </div>
                              </div>
                          </div>
                       </div>
                    <!-- Fin Datos Laborales -->
                    <!-- Datos Monetarios -->
                       <div v-if="buttonSelected == 5">
                        <div class="border-b tab">
                            <div class="relative border-l-2 border-transparent">
                                <input checked class="absolute z-10 w-full h-10 opacity-0 cursor-pointer top-6" type="checkbox" id="datosPersonales" >
                                <header class="flex items-center justify-between p-5 pl-8 pr-8 cursor-pointer select-none tab-label" for="datosPersonales">
                                    <span class="text-xl font-thin text-grey-darkest">
                                    Monetarios
                                    </span>
                                    <div class="flex items-center justify-center border rounded-full border-grey w-7 h-7 test">
                                        <svg aria-hidden="true" class="" data-reactid="266" fill="none" height="24" stroke="#606F7B" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewbox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
                                            <polyline points="6 9 12 15 18 9">
                                            </polyline>
                                        </svg>
                                    </div>
                                </header>
                               <div class="tab-content">
                                    <div class="pb-5 pl-8 pr-8">
                                        <div class="grid grid-cols-6 gap-4">
                                            <div class="col-span-2">
                                                <InputLabel for="salario_diario" value="Salario Diario:*" />
                                                <TextInput id="salario_diario" type="text" disabled
                                                class="block w-full mt-1"  placeholder="$ 00.0" v-model="form.salario_diario"/>
                                            </div>
                                            <div class="col-span-2">
                                                <InputLabel for="salario_bruto" value="Salario Bruto:*" />
                                                <TextInput id="salario_bruto" type="number" v-model="form.salario_bruto"
                                                class="block w-full mt-1"  placeholder="$ 00.0"
                                                :disabled="editEmpleadoDisable"
                                                @keyup="calculoSalario()" />
                                                <InputError :message="form.errors.salario_bruto" class="mt-2" />
                                            </div>
                                            <div class="col-span-2">
                                                <InputLabel for="salario_imss" value="Salario IMSS:*" />
                                                <TextInput id="salario_imss" type="text" v-model="form.salario_imss" disabled
                                                class="block w-full mt-1"  placeholder="$ 00.0" />
                                                <InputError :message="form.errors.salario_imss" class="mt-2" />
                                            </div>
                                            <div class="col-span-3">
                                                <InputLabel for="fondo_ahorro" value="Fondo de Ahorro:*" />
                                                <TextInput id="fondo_ahorro" type="text" v-model="form.fondo_ahorro"
                                                class="block w-full mt-1"  placeholder="$ 00.0" disabled/>
                                                <InputError :message="form.errors.fondo_ahorro" class="mt-2" />
                                            </div>
                                            <div class="col-span-3">
                                                <InputLabel for="bono_puntualidad" value="Bono de Puntualidad:*" />
                                                <TextInput id="bono_puntualidad" type="text" v-model="form.bono_puntualidad"
                                                class="block w-full mt-1"  placeholder="$ 00.0" disabled />
                                                <InputError :message="form.errors.bono_puntualidad" class="mt-2" />
                                            </div>
                                            <div class="col-span-3">
                                                <InputLabel for="bono_asistencia" value="Bono de Asistencia:*" />
                                                <TextInput id="bono_asistencia" type="text" v-model="form.bono_asistencia"
                                                class="block w-full mt-1"  placeholder="$ 00.0" disabled/>
                                                <InputError :message="form.errors.bono_asistencia" class="mt-2" />
                                            </div>
                                            <div class="col-span-3">
                                                <InputLabel for="despensa" value="Despensa:*" />
                                                <TextInput id="despensa" type="number" v-model="form.despensa"
                                                :disabled="editEmpleadoDisable"
                                                class="block w-full mt-1"  placeholder="$ 00.0" @keyup="calculoSalario()" />
                                                <InputError :message="form.errors.despensa" class="mt-2" />
                                            </div>
                                        </div>
                                    </div>
                               </div>
                            </div>
                        </div>
                       </div>
                   <!-- Fin Datos Monetarios -->
                   <!-- Aspectos Generales de Salud -->
                       <div v-if="buttonSelected == 6">                         
                          <div class="border-b tab">
                              <div class="relative border-l-2 border-transparent">
                                  <input checked class="absolute z-10 w-full h-10 opacity-0 cursor-pointer top-6"
                                  type="checkbox" id="datosPersonales">
                                  <header class="flex items-center justify-between p-5 pl-8 pr-8 cursor-pointer select-none tab-label" for="datosPersonales">
                                      <span class="text-xl font-thin text-grey-darkest">
                                      Aspectos Generales de Salud
                                      </span>
                                      <div class="flex items-center justify-center border rounded-full border-grey w-7 h-7 test">
                                          <svg aria-hidden="true" class="" data-reactid="266" fill="none" height="24" stroke="#606F7B" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewbox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
                                              <polyline points="6 9 12 15 18 9">
                                              </polyline>
                                          </svg>
                                      </div>
                                  </header>
                                 <div class="tab-content">
                                      <div class="pb-5 pl-8 pr-8">
                                          <div class="grid grid-cols-3 gap-4">
                                              <div class="mt-4">
                                                  <InputLabel for="cat_tipos_sangre_id"  value="Tipo de Sangre:*" />
                                                  <Select v-model="form.cat_tipos_sangre_id" class="w-full"  :disabled="editEmpleadoDisable">
                                                      <option v-for="sangre in catalogos.tiposSangres" :key="sangre.id"  :value="sangre.id">
                                                          {{sangre.nombre}}
                                                      </option>
                                                  </Select>
                                                  <InputError :message="form.errors.cat_tipos_sangre_id" class="mt-2" />
                                              </div>
                                              <div class="mt-4">
                                                  <InputLabel for="alergias" value="Alergias:" />
                                                  <TextInput id="alergias" type="text" v-model="form.alergias"
                                                  class="block w-full mt-1"  placeholder="alergia 1, alergia 2"  :disabled="editEmpleadoDisable"/>
                                                  <InputError :message="form.errors.alergias" class="mt-2" />
                                              </div>
                                              <div class="mt-4">
                                                  <InputLabel for="enfermedades_cronicas" value="Enfermedades Crónicas:" />
                                                  <TextInput id="enfermedades_cronicas" type="text" v-model="form.enfermedades_cronicas"
                                                  class="block w-full mt-1"  placeholder="enfermedad 1, enfermedad 2"  :disabled="editEmpleadoDisable"/>
                                                  <InputError :message="form.errors.enfermedades_cronicas" class="mt-2" />
                                              </div>
                                          </div>
                                      </div>
                                 </div>
                              </div>
                          </div>
                       </div>
                   <!--Fin Aspectos Generales de Salud -->
                   <!-- Expediente -->
                       <div v-if="buttonSelected == 7">                      
                          <div class="border-b tab">
                              <div class="relative border-l-2 border-transparent">
                                  <input checked class="absolute z-10 w-full h-10 opacity-0 cursor-pointer top-6" type="checkbox" id="datosPersonales" >
                                  <header class="flex items-center justify-between p-5 pl-8 pr-8 cursor-pointer select-none tab-label" for="datosPersonales">
                                      <span class="text-xl font-thin text-grey-darkest">
                                      Archivos Expedientes
                                      </span>
                                      <div class="flex items-center justify-center border rounded-full border-grey w-7 h-7 test">
                                          <svg aria-hidden="true" class="" data-reactid="266" fill="none" height="24" stroke="#606F7B" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewbox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
                                              <polyline points="6 9 12 15 18 9">
                                              </polyline>
                                          </svg>
                                      </div>
                                  </header>
                                 <div class="tab-content">
                                      <div class="pb-5 pl-8 pr-8">
                                          <div class="grid grid-cols-3 gap-4">
                                              <template v-if="!editEmpleadoDisable">
                                                  <div class="mt-4">
                                                      <InputLabel for="fotografia" value="Foto:" />
                                                      <DropZone id="fotografia" v-model="form.fotografia" ref="fotografia" accept="image/x-png,image/jpeg"/>
                                                      <InputError :message="form.errors.fotografia" class="mt-2" />
                                                  </div>
                                                  <div class="mt-4">
                                                      <InputLabel for="expediente" value="Expediente:" />
                                                      <DropZone id="expediente" v-model="form.expediente" accept="application/pdf"/>
                                                      <InputError :message="form.errors.expediente" class="mt-2" />
                                                  </div>
                                                  <div class="mt-4">
                                                      <InputLabel for="contrato" value="Contrato:" />
                                                      <DropZone id="contrato" v-model="form.contrato" accept="application/pdf"/>
                                                      <InputError :message="form.errors.contrato" class="mt-2" />
                                                  </div>
                                              </template>
                                              <div v-if="typeForm == 'update'" class="mt-4">
                                                  <span v-if="empleado.fotografia">
                                                      <a :data-fancybox="'fotografia'+empleado.id" :data-src="empleado.fotografia+timeImage"
                                                      class="inline-flex items-center w-10 h-10 p-1 m-1 text-xs font-semibold tracking-widest text-white uppercase transition bg-green-800 border border-transparent rounded-full hover:bg-green-700 active:bg-gray-900 focus:ring-green-300 disabled:opacity-25">
                                                          <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-10 h-10" viewBox="0 0 16 16">
                                                          <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                                                          <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
                                                          </svg>
                                                      </a>
                                                  </span>
                                                  <span v-if="empleado.expedienteGeneral">
                                                      <a  :data-fancybox="'expedientes'+empleado.id"
                                                          data-type="pdf"
                                                           data-caption="Expediente General"
                                                          :data-src="empleado.expedienteGeneral.ruta+timeImage"
                                                          class="inline-flex items-center w-10 h-10 p-1 m-1 text-xs font-semibold tracking-widest text-red-500 uppercase transition bg-transparent border border-red-800 rounded-full hover:border-red-700 active:bg-gray-900 focus:ring-green-300 disabled:opacity-25">
                                                          <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-10 h-10" viewBox="0 0 16 16">
                                                          <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/>
                                                          <path d="M4.603 14.087a.81.81 0 0 1-.438-.42c-.195-.388-.13-.776.08-1.102.198-.307.526-.568.897-.787a7.68 7.68 0 0 1 1.482-.645 19.697 19.697 0 0 0 1.062-2.227 7.269 7.269 0 0 1-.43-1.295c-.086-.4-.119-.796-.046-1.136.075-.354.274-.672.65-.823.192-.077.4-.12.602-.077a.7.7 0 0 1 .477.365c.088.164.12.356.127.538.007.188-.012.396-.047.614-.084.51-.27 1.134-.52 1.794a10.954 10.954 0 0 0 .98 1.686 5.753 5.753 0 0 1 1.334.05c.364.066.734.195.96.465.12.144.193.32.2.518.007.192-.047.382-.138.563a1.04 1.04 0 0 1-.354.416.856.856 0 0 1-.51.138c-.331-.014-.654-.196-.933-.417a5.712 5.712 0 0 1-.911-.95 11.651 11.651 0 0 0-1.997.406 11.307 11.307 0 0 1-1.02 1.51c-.292.35-.609.656-.927.787a.793.793 0 0 1-.58.029zm1.379-1.901c-.166.076-.32.156-.459.238-.328.194-.541.383-.647.547-.094.145-.096.25-.04.361.01.022.02.036.026.044a.266.266 0 0 0 .035-.012c.137-.056.355-.235.635-.572a8.18 8.18 0 0 0 .45-.606zm1.64-1.33a12.71 12.71 0 0 1 1.01-.193 11.744 11.744 0 0 1-.51-.858 20.801 20.801 0 0 1-.5 1.05zm2.446.45c.15.163.296.3.435.41.24.19.407.253.498.256a.107.107 0 0 0 .07-.015.307.307 0 0 0 .094-.125.436.436 0 0 0 .059-.2.095.095 0 0 0-.026-.063c-.052-.062-.2-.152-.518-.209a3.876 3.876 0 0 0-.612-.053zM8.078 7.8a6.7 6.7 0 0 0 .2-.828c.031-.188.043-.343.038-.465a.613.613 0 0 0-.032-.198.517.517 0 0 0-.145.04c-.087.035-.158.106-.196.283-.04.192-.03.469.046.822.024.111.054.227.09.346z"/>
                                                          </svg>
                                                      </a>
                                                  </span>
                                                  <span v-if="empleado.contrato">
                                                      <a  :data-fancybox="'contrato'+empleado.id"
                                                          data-type="pdf"
                                                          data-caption="Contrato"
                                                          :data-src="empleado.contrato.ruta+timeImage"
                                                          class="inline-flex items-center w-6 h-6 p-1 m-1 text-xs font-semibold tracking-widest text-blue-400 uppercase transition bg-transparent border border-blue-400 rounded-full hover:border-blue-700 active:bg-gray-900 focus:ring-green-300 disabled:opacity-25">
                                                          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-card-list" viewBox="0 0 16 16">
                                                              <path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h13zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13z"/>
                                                              <path d="M5 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 5 8zm0-2.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm0 5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm-1-5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0zM4 8a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0zm0 2.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0z"/>
                                                          </svg>
                                                      </a>
                                                  </span>
                                              </div>
                                          </div>
                                      </div>
                                 </div>
                              </div>
                          </div>
                       </div>
                   <!--Fin Expediente -->
                   <!-- Finiquitos -->
                       <div v-if="buttonSelected == 8">                              
                           <div v-if="typeForm == 'update'" class="border-b tab" >
                               <div class="relative border-l-2 border-transparent">
                                   <input checked class="absolute z-10 w-full h-10 opacity-0 cursor-pointer top-6" type="checkbox" id="datosPersonales">
                                   <header class="flex items-center justify-between p-5 pl-8 pr-8 cursor-pointer select-none tab-label" for="datosPersonales">
                                       <span class="text-xl font-thin text-grey-darkest">
                                           Finiquitos
                                       </span>
                                       <div class="flex items-center justify-center border rounded-full border-grey w-7 h-7 test">
                                           <svg aria-hidden="true" class="" data-reactid="266" fill="none" height="24" stroke="#606F7B" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewbox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
                                               <polyline points="6 9 12 15 18 9">
                                               </polyline>
                                           </svg>
                                       </div>
                                   </header>
                                  <div class="tab-content">
                                       <div class="pb-5 pl-8 pr-8">
                                           <div class="grid grid-cols-3 gap-4">
                                               <div class="mt-4">
                                                   <component-label for="cat_bajas_empleado_id"  value="Motivo de Baja:*" />
                                                   <component-select v-model="form.cat_bajas_empleado_id" class="w-full"
                                                       :disabled="!$page.props.can['empleados.delete']">
                                                       <option v-for="motivo in catalogos.motivosBajas" :key="motivo.id" :value="motivo.id">
                                                           {{motivo.nombre}}
                                                       </option>
                                                   </component-select>
                                                   <input-error :message="form.errors.cat_bajas_empleado_id" class="mt-2" />
                                               </div>
                                               <div class="mt-4">
                                                   <component-label for="fecha_baja" value="Fecha de Baja:" />
                                                   <component-input id="fecha_baja" type="date"  max="2030-12-31" v-model="form.fecha_baja"
                                                   class="block w-full mt-1"
                                                   :disabled="!$page.props.can['empleados.delete']"/>
                                                   <input-error :message="form.errors.fecha_baja" class="mt-2" />
                                               </div>
                                               <div class="mt-4">
                                                   <component-label for="fecha_finiquito" value="Fecha de Finiquito:*" />
                                                   <component-input id="fecha_finiquito" type="date" max="2030-12-31"
                                                   v-model="form.fecha_finiquito"
                                                     :disabled="!$page.props.can['finiquitos.update']"
                                                   class="block w-full mt-1" />
                                                   <input-error :message="form.errors.fecha_finiquito" class="mt-2" />
                                               </div>
                                               <div class="mt-4">
                                                   <component-label for="monto_finiquito" value="Monto de Finiquito:*" />
                                                   <component-input id="monto_finiquito" type="text" placeholder="$0.0"
                                                     v-model="form.monto_finiquito"
                                                     :disabled="!$page.props.can['finiquitos.update']"
                                                   class="block w-full mt-1" max="99999"/>
                                                   <input-error :message="form.errors.monto_finiquito" class="mt-2" />
                                               </div>
                                               <div class="mt-4">
                                                   <component-label for="finiquito_pagado"  value="Pagado:*" />
                                                   <component-select v-model="form.finiquito_pagado" class="w-full"
                                                     :disabled="!$page.props.can['finiquitos.update']">
                                                       <option value="0">NO</option>
                                                       <option value="1">SI</option>
                                                   </component-select>
                                                   <input-error :message="form.errors.finiquito_pagado" class="mt-2" />
                                               </div>
                                           </div>
                                       </div>
                                  </div>
                               </div>
                           </div>
                       </div>
                   <!-- Fin Finiquitos -->
                   <ButtonAdd @click="createOrUpdate" style="float:right; margin:2rem; justify-content: center;">
                      Guardar
                   </ButtonAdd>
                   <a :href="route('empleado.indexmanual', {activo: 'activo'})">
                    <ButtonInfo style="float:right; margin:2rem; justify-content: center;">
                      Regresar
                   </ButtonInfo>
                  </a>
                  <div class="px-6 py-4 ">
                        <ul v-if="form.hasErrors" class="text-red-500">
                            <li v-for="(error, index) in form.errors" :key="index">
                                *{{error}}
                            </li>
                        </ul>
                    </div>
                  </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>


<script setup>
import { ref, computed, reactive, watch } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import ButtonAdd from '../../Components/ButtonAdd.vue';
import ButtonSeccion from '../../Components/ButtonSeccion.vue';
import Title from '../../Components/Title.vue';
import GraficaSection from './Partials/GraficaSection.vue';
import DocumentosSection from './Partials/DocumentosSection.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import SwitchButton from './Partials/SwitchButton.vue';
import AddProcesoModal from './Modals/AddProcesoModal.vue';
import ShowDocumentoModal from './Modals/ShowDocumentoModal.vue';
import ShowCalificacionesModal from './Modals/ShowCalificacionesModal.vue';
import Graph1 from './Partials/Graph1.vue';
import Graph2 from './Partials/Graph2.vue';

import { Link } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';
let props = defineProps({
    departamentosAuditoria: {
        type: Object,
        required: true,
    },
    filters: {
        type: Object,
        required: true,
    },

    procesos:Object,
    usuarios:Object,
    calificaciones_mes:Object

});
const params = reactive({
    departamento_auditoria_id: props.filters.departamento_auditoria_id
})

const departamento = computed(() => {
    return props.departamentosAuditoria.find((dep) => dep.id == params.departamento_auditoria_id)
})

watch(params, (newParams) => {

    //console.log(newParams);
    Inertia.visit(route("control-interno.departamentos-aditorias.index"),
        {
            data: newParams,
            replace: true,
            only: ['calificaciones','procesos', 'calificaciones_mes'],
            preserveScroll: true,
            preserveState: true,
        });
})

const cambio = ref(false);
const cambioSwitch = () => 
{
    //console.log("gola");
    cambio.value = !cambio.value;
}

const modalAddProceso =  ref(false);
const openModalAddProceso = () => 
{
   modalAddProceso.value = true;
}

const closeModalAddProceso = () => 
{
    modalAddProceso.value = false;
}

const modalAddDocumento = ref(false);
const procesoReactive = ref(-1);
const openModalAddDoc = (proceso_id) => 
{
    procesoReactive.value = proceso_id;
    consultarDocumentos(proceso_id);
    modalAddDocumento.value = true;
}
const closeModalAddDoc = () =>
{
    modalAddDocumento.value = false;
}

const documentos = ref([]);
const consultarDocumentos = (proceso_id) => 
{
    //console.log(proceso_id);
    axios.get('/getDocumentos/'+proceso_id).then((response)=> 
       {
                //console.log(response.data);
                documentos.value = response.data;
               
       });
}

const modalAdCalf = ref(false);
const openModalShowCalif = (proceso_id) =>
{ 
    consultarRubros(proceso_id);
    procesoReactive.value = proceso_id;
    modalAdCalf.value = true;
}

const closeModalShowCalif = () =>
{ 
    calificaciones.value = [];
   modalAdCalf.value = false;

}

const rubros = ref([]);
const calificaciones = ref([]);
const meses = [
  {
    numero: '1',
    mes: 'Ene'
  },
  {
    numero: '2',
    mes: 'Feb'
  },
  {
    numero: '3',
    mes: 'Mar'
  },
  {
    numero: '4',
    mes: 'Abr'
  },
  {
    numero: '5',
    mes: 'May'
  },
  {
    numero: '6',
    mes: 'Jun'
  },
  {
    numero: '7',
    mes: 'Jul'
  },
  {
    numero: '8',
    mes: 'Ago'
  },
  {
    numero: '9',
    mes: 'Sep'
  },
  {
    numero: '10',
    mes: 'Oct'
  },
  {
    numero: '11',
    mes: 'Nov'
  },
  {
    numero: '12',
    mes: 'Dic'
  }
];

const consultarRubros = (proceso_id) =>
{
    axios.get('/getRubros/'+proceso_id).then((response)=> 
       {
          rubros.value = response.data;

          for (let index = 0; index < rubros.value.length; index++) 
          {
            const rubro = rubros.value[index];
           // console.log(rubro);
            for (let index2 = 0; index2 < meses.length; index2++) 
              {
                  const mes = meses[index2];
                  let newInterseccion = {};
                  //console.log(mes);
                  newInterseccion.mes = mes.numero;
                  newInterseccion.rubro = rubro.id;
                  newInterseccion.valor= null;

                  calificaciones.value.push(newInterseccion);
              }  
          }

          for (let index3 = 0; index3 < calificaciones.value.length; index3++) 
          {
            const interseccion = calificaciones.value[index3];
            //console.log(interseccion);
            for (let index4 = 0; index4 < rubros.value.length; index4++) 
            {
                const rubro = rubros.value[index4];
                for (let index5 = 0; index5 < rubro.calificaciones.length; index5++) 
                {
                    const calificaion = rubro.calificaciones[index5];
                    //console.log(calificaion);
                    if(calificaion.mes == interseccion.mes && calificaion.rubro_id == interseccion.rubro)
                    {
                        interseccion.valor = calificaion.valor;
                    } 
                }
            }
          }

       });
}

const arregloCalificaciones = computed(() =>
{
    let arregloAux = [];
    for (let index = 0; index < meses.length; index++) 
    {
        const fecha = new Date();
        const año = fecha.getFullYear();
    
        const mes = meses[index];
        let newObjCalf = {
            numero: mes.numero,
            mes: mes.mes,
            año: año,
            promedio:0
        };
        arregloAux.push(newObjCalf);
    }
    
    for (let index2 = 0; index2 < arregloAux.length; index2++) 
    {
        const objeto = arregloAux[index2];
        //console.log(objeto);
        let conteo = []; 
        let suma = 0;
        let promedio = 0;
        for (let index3 = 0; index3 < props.calificaciones_mes.length; index3++) 
        {
            const calificacion = props.calificaciones_mes[index3];
            if(calificacion.mes == objeto.numero)
            {
               objeto.promedio += calificacion.valor;
               suma += calificacion.valor;
               conteo.push(calificacion);
            }
            else
            {
               
            }
        }
       //console.log(suma/conteo.length)
       promedio = suma/conteo.length;
       //console.log(promedio)
       objeto.promedio = promedio
    }
    //console.log(arregloAux);
    return arregloAux
   
});


const arregloParametros = computed(() => {

});

for (let index = 0; index < meses.length; index++) 
{
    const mes = meses[index];
    for (let index2 = 0; index2 < array.length; index2++) 
    {
        const element = array[index2];
        
    }
}

</script>

<template>
    <AppLayout title="Dashboard">
      <section class="objetivo_auditoria sm:p-8 p-2 pt-8"  >
            <div class="sm:text-center mr-0 sm:mr-96 sm:pt-8" style="font-family: 'Montserrat';">
                <h1 class="sm:text-4xl text-xl font-semibold text-white sm:mr-12 ">Objetivo del área</h1>
                <span  class="w-16 h-1 bg-[#EC2944] mt-4 sm:ml-96" style="display:block;"></span>
            </div>
            <div class="sm:mr-12 mr-0 sm:pl-16">
                <p class="mt-6 mb-16 sm:text-xl text-base sm:ml-72 text-white" style="font-family: 'Montserrat'; line-height: 1.8;">
                       Dentro de Control Interno, nos encargamos de la creación  y seguimiento del cumplimiento de los 
                       procesos, políticas, manuales, normas y métodos estratégicos de la empresa, todo con la finalidad de 
                       llegar al plan estratégico de esta, para poder lograrlo se realizan evaluaciones continuamente a las 
                       distintas áreas que la conforman.
                </p>
            </div>
       </section>
       <section class="documentos">
        <div class="lateral">
            <header class="text-[#1A1A22]" style="font-family: 'Montserrat'; font-size: 0.8rem; margin-bottom:1rem;">DASHBOARD AUDITORIAS</header>
            <ul class="menuVert" v-for="dep in departamentosAuditoria" :key="'dep' + dep.id">
               <span class="absolute w-2 h-8 mt-2" style="float: left;" ></span>
               <li @click="params.departamento_auditoria_id = dep.id">
                 <a  class="font-semibold"  >{{ dep.nombre  }}</a>
               </li>
            </ul> 
        </div>
        <div v-if="params.departamento_auditoria_id !== null" class="documentos_view" style="margin-right:5rem">
             <div class="grid grid-cols-2" style="font-family: 'Montserrat';">
                <div class="flex justify-start">
                    <div v-for="departamento in departamentosAuditoria" :key="departamento.id">
                       <div  v-if="departamento.id == params.departamento_auditoria_id ">
                          <h1 class="text-xl">{{departamento.nombre}}</h1>
                       </div>
                    </div>
                </div>
                <div class="flex justify-end">
                    <SwitchButton  @click="cambioSwitch" :checked="cambio"/>
                </div>
             </div>
             <div class="mt-4">
               <section v-if="!cambio" class="grid grid-cols-3 grid-rows-6 gap-4">
                   <div class="col-start-1 col-end-3"> <!--Graficas-->
                       Graficas
                       <div>
                           <Graph1 :calificaciones = "arregloCalificaciones" />
                       </div>
                       <div>
                          <Graph2 :calificaciones = "arregloCalificaciones" />
                       </div>
                   </div>
                   <div> <!--Contadores-->
                       
                   </div>
               </section>
               <section v-if="cambio">
                   <h1 class="text-sm font-bold">Procesos</h1>
                   <ButtonAdd class="mt-2" @click="openModalAddProceso">
                      Agregar proceso
                   </ButtonAdd>
                   <div class="grid grid-cols-3 mt-4 mb-8">
                      <div class="flex flex-col border rounded-xl items-center drop-shadow-xl" v-for="proceso in  procesos" :key="proceso.id">
                          <div class="flex flex-col items-center mt-2 ">
                            <img class="w-16" alt="logo" :src="proceso.logo" />
                            <h3 class="mt-4 text-xl">{{ proceso.nombre }}</h3>
                          </div>
                          <div class="grid grid-cols-2 mt-4 border-t-2 w-full">
                            <div @click="openModalAddDoc(proceso.id)" class="w-full p-4 text-center border-r-2">
                                Documentos
                            </div>
                            <div @click="openModalShowCalif(proceso.id)" class="w-full p-4 text-center">
                                Calificaciones
                            </div>
                          </div>
                      </div>
                   </div>
               </section>
             </div>
        </div>

       </section >
       <!--Docs responsive-->
       <section class="sm:hidden static" style="font-family: 'Montserrat';">
           <div class="ml-2 mt-8 flex flex-col">
              <h1 class="uppercase font-semibold text-[#1A1A22]">Dashboard Auditorías</h1>
              <div class="flex flex-row p-2">
                 <div class="flex mr-2"
                      :class="{ ' transition': route().current('control-interno*') }">
                  <Dropdown align="right" width="48">
                      <template #trigger>
                          <ButtonAdd type="button"
                              class="mt-0 mb-2 inline-flex items-center py-2 text-sm  transition focus:outline-none">
                              <p class="text-black">VER CATEGORIAS</p>
                          </ButtonAdd>
                      </template>
                      <template #content >
                          <button class="sm:ml-0 ml-2 mr-12" v-for="dep in departamentosAuditoria" :key="'dep' + dep.id">
                              <span @click="params.departamento_auditoria_id = dep.id" class="text-xs">
                                <a  class="font-semibold">{{ dep.nombre  }}</a>
                              </span>
                            </button>
                          <div class="border-t border-gray-100" />
                      </template>
                  </Dropdown>
                </div>

                <ButtonAdd  v-if="$page.props.can['calificacion.create'] && departamento != undefined"
                    @click="showingFormCalificacion = true">
                    <p class="text-xs">
                        AGREGAR DOCUMENTO
                    </p>
                </ButtonAdd>
              </div>
           </div>
           <div class="p-4">
                
           </div>
       </section>
         <!-- Modal Procesos -->
         <AddProcesoModal :departamento="params.departamento_auditoria_id" :show="modalAddProceso" @close="closeModalAddProceso" />
          <!-- End Modal Procesos -->

          <!-- Modal Documentos -->
         <ShowDocumentoModal  :documentos="documentos" :usuarios="usuarios" :procesoId = "procesoReactive"  :show="modalAddDocumento" @close="closeModalAddDoc" />  
          <!--End Modal Documentos -->

          <!-- Modal Calificaciones -->
          <ShowCalificacionesModal :calificaciones="calificaciones" :rubros="rubros" :procesoId="procesoReactive" :show="modalAdCalf" @close="closeModalShowCalif" />
          <!--End Modal Calificaciones -->
    </AppLayout>
</template>

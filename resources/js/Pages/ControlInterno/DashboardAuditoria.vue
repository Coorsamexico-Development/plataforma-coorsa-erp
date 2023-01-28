<script setup>
import { ref, computed, reactive, watch } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import ButtonAdd from '../../Components/ButtonAdd.vue';
import ButtonSeccion from '../../Components/ButtonSeccion.vue';
import Title from '../../Components/Title.vue';
import GraficaSection from './Partials/GraficaSection.vue';
import DocumentosSection from './Partials/DocumentosSection.vue';
import FormCalificacionModal from './Modals/FormCalificacionModal.vue';

import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';

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
    calificaciones: {
        type: Object,
        required: true,
    }
});


const showingFormCalificacion = ref(false);
const params = reactive({
    departamento_auditoria_id: props.filters.departamento_auditoria_id
})


const departamento = computed(() => {
    return props.departamentosAuditoria.find((dep) => dep.id == params.departamento_auditoria_id)
})

watch(params, (newParams) => {

    Inertia.visit(route("control-interno.departamentos-aditorias.index"),
        {
            data: newParams,
            replace: true,
            only: ['calificaciones'],
            preserveScroll: true,
            preserveState: true,
        });
})


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
                 <a  class="font-semibold" :style="hover" @mouseenter="updateHoverState(true)" @mouseleave="updateHoverState(false)" >{{ dep.nombre  }}</a>
               </li>
            </ul> 
        </div>
        <div class="documentos_view" style="margin-right:5rem">
            <GraficaSection :calificaciones="calificaciones" class="sm:px-6" style="align-items:center">
                <ButtonAdd v-if="$page.props.can['calificacion.create'] && departamento != undefined"
                    @click="showingFormCalificacion = true">
                     AGREGAR
                </ButtonAdd>
            </GraficaSection>
            <DocumentosSection :documentos="calificaciones" class="my-3"></DocumentosSection>
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
              <GraficaSection :calificaciones="calificaciones" class="sm:px-6" style="align-items:center">         
              </GraficaSection>
              <DocumentosSection :documentos="calificaciones" class="my-3"></DocumentosSection>
           </div>
       </section>

        <!-- Modal Form Calificaciones -->
        <FormCalificacionModal v-if="departamento != undefined" :departamento="departamento"
            :show="showingFormCalificacion" @close="showingFormCalificacion = false" />

    </AppLayout>
</template>

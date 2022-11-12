<template>
    <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg" 
    style="width:40rem; margin-left: 2rem; height: 5rem;  display: grid; grid-template-columns: repeat(2,1fr); align-content: space-between;">
            <strong>
              <h1 style="float:left; margin-right: 5rem; margin-left: 2rem; margin-top: 1rem; font-size: 1.2rem; color:#26458D;">Departamentos</h1>
            </strong>
             <InputSearch type="search" style="width:10rem; float: left; margin-top: 1rem; "
            v-model="params.search"  aria-label="Search"
            class="block w-full max-w-xs border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"/>
    </div>
    <DataTable style="margin-top:-2rem">
        <template #table-header>
            <th scope="col" class="w-3/12 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">
                <span class="inline-flex justify-between w-full px-6 py-3"
                    @click="sort('id')">#
                    <svg  v-if="params.field === 'id' && params.direction === 'asc'"
                    xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
                    viewBox="0 0 20 20" fill="currentColor">
                        <path d="M3 3a1  0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"/>
                    </svg>
                    <svg  v-if="params.field === 'id' && params.direction === 'desc'"
                    xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20"  fill="currentColor">
                        <path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"/>
                    </svg>
                </span>
            </th>
            <th scope="col" class="w-3/12 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">
                <span class="inline-flex justify-between w-full px-6 py-3"
                    @click="sort('nombre')">Nombre
                    <svg  v-if="params.field === 'nombre' && params.direction === 'asc'"
                    xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
                    viewBox="0 0 20 20" fill="currentColor">
                        <path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"/>
                    </svg>
                    <svg  v-if="params.field === 'nombre' && params.direction === 'desc'"
                    xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20"  fill="currentColor">
                        <path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"/>
                    </svg>
                </span>
            </th>
        </template>
        <template #table-body >
          <tr class="text-gray-500" v-for='departamento in departamentos' :key="departamento.id">
              <td class="px-6 py-4 whitespace-nowrap"   @click="selectedRow(departamento.id-1,$event)"  >{{departamento.id}}</td>
              <td class="px-6 py-4 whitespace-nowrap">{{departamento.nombre}}</td>
          </tr>
        </template>
    </DataTable>
</template>
<script>
import { Link } from '@inertiajs/inertia-vue3';
import { selectElement, removeSelect } from '../../../../utils/index';
import { pickBy, throttle } from "lodash";
import InfoButton from '@/Components/Buttoninfo.vue';
import DataTable from '@/Components/DataTable.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputSearch from '@/Components/InputSearch.vue';

let elementSelect;

export default {
  emits: ['selected', 'update', 'create', 'deletion'],
  props: {
    filters: Object,
    departamentos: Object,
    puestos: Object
  },
  components: {
      Link,
      InfoButton,
      PrimaryButton,
      DataTable,
      InputSearch
  },
  data() {
    return {
      variable: 0,
      params: {
        search: this.filters.search,
        field: this.filters.field,
        direction: this.filters.direction,
      }
    };
  },
  methods: {
    sort(field) {
      this.params.field = field;
      this.params.direction = this.params.direction === "asc" ? "desc" : "asc";
    },
    selectedRow(index, event)
    {
        if(elementSelect){
            removeSelect(elementSelect);
        }
        elementSelect = event.target.closest('tr');
        selectElement(elementSelect)
        this.$emit('selected', index);
    },

  },
  watch: {
    params: {
      handler: throttle(function () {
        let params = pickBy(this.params);
        this.$inertia.get(this.route("dptos.index"), params, {
          replace: true,
          preserveState: true,
        });
      }, 150),
      deep: true,
    },
  },
};
</script>

<script setup>
import { reactive, ref, watch } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import ButtonSeccion from '../../Components/ButtonSeccion.vue';
import Title from '../../Components/Title.vue';
import CardImage from '@/Components/CardImage.vue';
import InputSearch from '@/Components/InputSearch.vue';
import FormPoliticsModal from './Modals/FormPoliticsModal.vue';
import { Inertia } from '@inertiajs/inertia';
import { pickBy } from 'lodash';
import AnimationCard from '../../Components/AnimationCard.vue';
import { Fancybox } from '@fancyapps/ui/src/Fancybox/Fancybox.js';

let props = defineProps({
    tipoPoliticas: {
        type: Object,
        required: true,
    },
    politicas: {
        type: Object,
        required: true,
    },
    filters: {
        type: Object,
        required: true,
    },
});

const params = reactive({
    search: props.filters.search,
    type_politic: props.filters.type_politic,
})


const showingFormPolitics = ref(false);
const typeFormPolitic = ref('create');
const politic = ref({
    id: -1
});

const closeFormPolitics = () => {
    showingFormPolitics.value = false;
    politic.value = { id: -1 };
}

const showFormPolitic = (typeForm, politicSelect = null) => {
    typeFormPolitic.value = typeForm;
    if (politicSelect !== null) {
        politic.value = politicSelect;
    }
    showingFormPolitics.value = true;
}

watch(params, (newParams) => {
    Inertia.visit(route("control-interno.politics.index"),
        {
            data: pickBy(newParams),
            replace: true,
            only: ['politicas'],
            preserveScroll: true,
            preserveState: true,
        });
})


</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <div class="md:flex">
                <div class="lg:w-1/4">
                    <h1 class="text-2xl font-bold text-[#CB4060]">DOCUMENTOS</h1>
                </div>
                <div class="flex flex-col items-center lg:w-2/4 md:pt-6">

                    <Title class="mb-2">¿Cual es nuestro objetivo como área?</Title>
                    <p class="text-center text-gray-500">
                        Dentro de Control Interno, nos encargamos de la creación y seguimiento del cumplimiento de los
                        procesos, políticas, manuales, normas, y métodos estratégicos, de la empresa todo con la
                        finalidad de llegar al plan estratégico de esta
                    </p>
                </div>
            </div>
        </template>
        <div class="">
            <!-- Politics Section -->
            <div class="flex flex-wrap justify-center gap-2 py-6 lg:px-24">
                <ButtonSeccion v-for="tipoPoliticas in tipoPoliticas" :key="'poli' + tipoPoliticas.id" class="h-24 w-36"
                    :class="{ 'active': params.type_politic === tipoPoliticas.id }"
                    @click="params.type_politic = tipoPoliticas.id">
                    <div class="flex flex-col items-center justify-between w-full" style="justify-items:center;">
                        <img :src="tipoPoliticas.logo" class="h-14" />
                        <p class="mt-1">{{ tipoPoliticas.name }}</p>
                    </div>
                </ButtonSeccion>

                <ButtonSeccion v-if="$page.props.can['politics.create']" class="h-24 w-36"
                    @click="showFormPolitic('create')">
                    <div class="flex flex-col items-center justify-between w-full" style="justify-items:center;">

                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="text-gray-400 h-14"
                            viewBox="0 0 16 16">
                            <path fill-rule="evenodd"
                                d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2Z" />
                        </svg>
                        <p class="mt-1">Agregar Politica</p>
                    </div>
                </ButtonSeccion>
            </div>
            <!-- End-Politics Section -->
            <div class="flex justify-center">
                <InputSearch v-model="params.search" class="w-1/2"></InputSearch>
            </div>
            <!-- Files Section -->
            <AnimationCard>
                <CardImage style="margin:1.8rem;" v-for="politica in politicas" :key="politica.id" :file="politica.pdf">
                    <div class="row">
                        <div class="col-lg-3 col-md-4">
                            <img class="" :src="politica.imagePolitic" />
                        </div>
                    </div>

                    <div class="row">
                        <span style="font-size:0.8rem">{{ politica.namepolitica }}</span>
                    </div>

                    <div v-if="$page.props.can['politics.update']" style="white-space: normal;"
                        class="absolute z-10 w-6 h-6 py-1 bg-white rounded-full shadow -bottom-2 -right-1 hover:bg-gray-500 hover:text-white"
                        @click="showFormPolitic('update', politica)">

                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 m-auto" fill="currentColor"
                            viewBox="0 0 16 16">
                            <path
                                d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z" />
                        </svg>

                    </div>
                </CardImage>
            </AnimationCard>
            <!-- End Files Section -->
        </div>
        <FormPoliticsModal :show="showingFormPolitics" :tipoPoliticas="tipoPoliticas" :politic="politic"
            :typeForm="typeFormPolitic" @close="closeFormPolitics()" />
    </AppLayout>
</template>
<style scoped>

</style>

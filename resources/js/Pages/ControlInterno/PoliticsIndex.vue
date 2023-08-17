<script setup>
import { reactive, ref, watch, computed } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import ButtonSeccion from "../../Components/ButtonSeccion.vue";
import Title from "../../Components/Title.vue";
import CardImageResponsive from "@/Components/CardImageResponsive.vue";
import InputSearch from "@/Components/InputSearch.vue";
import FormPoliticsModal from "./Modals/FormPoliticsModal.vue";
import { Inertia } from "@inertiajs/inertia";
import { pickBy } from "lodash";
import AnimationCard from "../../Components/AnimationCard.vue";
import { Fancybox } from "@fancyapps/ui/src/Fancybox/Fancybox.js";
import ButtonAdd from "@/Components/ButtonAdd.vue";

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
    nominas: Object,
});

const params = reactive({
    search: props.filters.search,
    type_politic: props.filters.type_politic,
});

const showingFormPolitics = ref(false);
const typeFormPolitic = ref("create");
const politic = ref({
    id: -1,
});

const closeFormPolitics = () => {
    showingFormPolitics.value = false;
    politic.value = { id: -1 };
};

const showFormPolitic = (typeForm, politicSelect) => {
    typeFormPolitic.value = typeForm;
    politic.value = politicSelect;
    showingFormPolitics.value = true;
};

watch(params, (newParams) => {
    Inertia.visit(route("control-interno.politics.index"), {
        data: pickBy(newParams),
        replace: true,
        only: ["politicas"],
        preserveScroll: true,
        preserveState: true,
    });
});

/*Funcion Hover*/

let hoverState = ref(false);

const hover = computed(() => {
    let modifier = "";
    if (hoverState.value) {
        return {
            color: "red",
        };
    }
});

const updateHoverState = (isHover) => {
    hoverState.value = isHover;
    /* console.log(hoverState.value); */
};

const isDownload = computed((file) => {
    console.log(file);
});
</script>

<template>
    <AppLayout title="Dashboard" :nominas="nominas">
        <section class="p-4 objetivo">
            <div
                class="text-center pt-14 objetivos"
                style="font-family: 'Montserrat'"
            >
                <h1
                    class="mr-12 text-xl font-semibold text-white sm:mr-16 sm:text-4xl"
                >
                    Objetivo de documentos
                </h1>
                <span
                    class="w-16 h-1 bg-[#EC2944] mt-4 sm:mr-96 ml-2"
                    style="display: block"
                ></span>
            </div>
            <p
                class="mt-4 mb-4 text-sm text-white sm:mt-6 sm:mb-16 sm:text-xl sm:ml-96"
                style="font-family: 'Montserrat'; line-height: 1.8"
            >
                En esta sección tendrás acceso a toda la documentación normativa
                de la empresa de manera organizada para su revisión, lo cual te
                permitirá cumplir con los procesos y optimizar tus tiempos.
            </p>
        </section>
        <!-- Politics Section -->

        <section class="documentos">
            <div class="lateral">
                <header
                    class="text-[#1A1A22]"
                    style="font-family: 'Montserrat'"
                >
                    Documentos
                </header>
                <ul
                    class="menuVert"
                    v-for="tipoPoliticas in tipoPoliticas"
                    :key="'poli' + tipoPoliticas.id"
                >
                    <div v-if="tipoPoliticas.id == 14">
                        <div v-if="$page.props.can['minutas.watch']">
                            <span
                                v-if="hoverState"
                                class="absolute w-2 h-8 mt-2"
                                style="float: left; margin-left: -1rem"
                                :style="{
                                    backgroundColor: '#' + tipoPoliticas.color,
                                }"
                            ></span>
                            <span
                                v-if="!hoverState"
                                class="absolute w-2 h-8 mt-2"
                                style="float: left"
                            ></span>
                            <li @click="params.type_politic = tipoPoliticas.id">
                                <a
                                    class="font-semibold"
                                    @mouseenter="updateHoverState(true)"
                                    @mouseleave="updateHoverState(false)"
                                >
                                    <p
                                        v-if="
                                            params.type_politic ==
                                            tipoPoliticas.id
                                        "
                                        :style="{
                                            color: '#' + tipoPoliticas.color,
                                        }"
                                    >
                                        <span
                                            class="absolute w-2 h-8 mt-0"
                                            style="
                                                float: left;
                                                margin-left: -1rem;
                                            "
                                            :style="{
                                                backgroundColor:
                                                    '#' + tipoPoliticas.color,
                                            }"
                                        ></span>
                                        <span
                                            v-if="hoverState"
                                            :style="{
                                                color:
                                                    '#' + tipoPoliticas.color,
                                            }"
                                            >{{ tipoPoliticas.name }}</span
                                        >
                                        <span v-if="!hoverState">{{
                                            tipoPoliticas.name
                                        }}</span>
                                    </p>
                                    <p v-else>
                                        <span
                                            v-if="hoverState"
                                            :style="{
                                                color:
                                                    '#' + tipoPoliticas.color,
                                            }"
                                            >{{ tipoPoliticas.name }}</span
                                        >
                                        <span v-if="!hoverState">{{
                                            tipoPoliticas.name
                                        }}</span>
                                    </p>
                                </a>
                            </li>
                        </div>
                    </div>
                    <div v-else>
                        <span
                            v-if="hoverState"
                            class="absolute w-2 h-8 mt-2"
                            style="float: left; margin-left: -1rem"
                            :style="{
                                backgroundColor: '#' + tipoPoliticas.color,
                            }"
                        ></span>
                        <span
                            v-if="!hoverState"
                            class="absolute w-2 h-8 mt-2"
                            style="float: left"
                        ></span>
                        <li @click="params.type_politic = tipoPoliticas.id">
                            <a
                                class="font-semibold"
                                @mouseenter="updateHoverState(true)"
                                @mouseleave="updateHoverState(false)"
                            >
                                <p
                                    v-if="
                                        params.type_politic == tipoPoliticas.id
                                    "
                                    :style="{
                                        color: '#' + tipoPoliticas.color,
                                    }"
                                >
                                    <span
                                        class="absolute w-2 h-8 mt-0"
                                        style="float: left; margin-left: -1rem"
                                        :style="{
                                            backgroundColor:
                                                '#' + tipoPoliticas.color,
                                        }"
                                    ></span>
                                    <span
                                        v-if="hoverState"
                                        :style="{
                                            color: '#' + tipoPoliticas.color,
                                        }"
                                        >{{ tipoPoliticas.name }}</span
                                    >
                                    <span v-if="!hoverState">{{
                                        tipoPoliticas.name
                                    }}</span>
                                </p>
                                <p v-else>
                                    <span
                                        v-if="hoverState"
                                        :style="{
                                            color: '#' + tipoPoliticas.color,
                                        }"
                                        >{{ tipoPoliticas.name }}</span
                                    >
                                    <span v-if="!hoverState">{{
                                        tipoPoliticas.name
                                    }}</span>
                                </p>
                            </a>
                        </li>
                    </div>
                </ul>
                <ButtonAdd
                    v-if="$page.props.can['politics.create']"
                    @click="showFormPolitic('create')"
                    style="
                        margin-top: 2rem;
                        font-family: 'Montserrat';
                        margin-left: 2.2rem;
                    "
                    >AGREGAR DOCUMENTO</ButtonAdd
                >
            </div>
            <div class="documentos_view">
                <!-- Files Section -->
                <AnimationCard>
                    <CardImage
                        style="
                            margin: 1.8rem;
                            height: 20rem;
                            width: 16rem;
                            margin-top: 4rem;
                        "
                        v-for="politica in politicas"
                        :key="politica.id"
                        :file="politica.pdf"
                    >
                        <div class="row">
                            <div class="col-lg-3 col-md-4">
                                <a
                                    v-if="!politica.pdf.endsWith('.pdf') > 0"
                                    target="_blank"
                                    :href="politica.pdf"
                                    download
                                >
                                    <img
                                        class="h-48"
                                        style="width: 50rem"
                                        :src="politica.imagePolitic"
                                    />
                                </a>
                                <a v-else>
                                    <img
                                        class="h-48"
                                        :href="politica.pdf"
                                        data-fancybox
                                        data-type="pdf"
                                        style="width: 50rem"
                                        :src="politica.imagePolitic"
                                    />
                                </a>
                            </div>
                        </div>

                        <div class="pt-6 row">
                            <span
                                class="absolute w-2 h-12"
                                style="float: left"
                                :style="{
                                    backgroundColor: '#' + politica.color,
                                }"
                            ></span>
                            <span
                                class="text-[#1D2B4E] ;"
                                style="
                                    font-size: 0.9rem;
                                    font-weight: 600;
                                    margin-left: 2rem;
                                    font-family: 'Montserrat';
                                    display: block;
                                "
                                >{{ politica.namepolitica }}</span
                            >
                        </div>

                        <div class="pb-12 row">
                            <span
                                class="font-light text-[#1D2B4E]"
                                style="
                                    font-size: 0.8rem;
                                    margin-left: 2rem;
                                    font-family: 'Montserrat';
                                "
                                >{{
                                    politica.nombre +
                                    " " +
                                    politica.apellido_paterno +
                                    " " +
                                    politica.apellido_materno
                                }}</span
                            >
                        </div>

                        <div
                            v-if="$page.props.can['politics.update']"
                            style="white-space: normal"
                            class="z-10 w-6 h-6 py-1 -mt-20 bg-white rounded-full shadow -right-1 hover:bg-gray-500 hover:text-white"
                            @click="showFormPolitic('update', politica)"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="w-4 h-4 m-auto"
                                fill="currentColor"
                                viewBox="0 0 16 16"
                            >
                                <path
                                    d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"
                                />
                            </svg>
                        </div>
                    </CardImage>
                </AnimationCard>
                <!-- End Files Section -->
            </div>
        </section>
        <!--Files Mobiles-->
        <section class="block sm:hidden">
            <div class="pl-8 pr-8">
                <InputSearch class="mt-8" v-model="params.search"></InputSearch>
            </div>
            <div class="mt-6">
                <div
                    class=""
                    v-for="tipoPolitica in tipoPoliticas"
                    :key="tipoPolitica.id"
                >
                    <div class="ml-2">
                        <span
                            class="absolute w-2 h-8 mt-0"
                            style="float: left"
                            :style="{
                                backgroundColor: '#' + tipoPolitica.color,
                            }"
                        ></span>
                        <h1
                            class="ml-4 text-xl font-bold"
                            :style="{ color: '#' + tipoPolitica.color }"
                        >
                            {{ tipoPolitica.name }}
                        </h1>
                    </div>
                    <!--Politics-->
                    <div style="overflow-x: scroll; display: -webkit-box">
                        <div v-for="politica in politicas" :key="politica.id">
                            <AnimationCard
                                v-if="politica.type_politic == tipoPolitica.id"
                            >
                                <CardImageResponsive
                                    class="ml-3"
                                    style="height: 20rem; width: 16rem"
                                >
                                    <div class="row">
                                        <div class="col-lg-3 col-md-4">
                                            <a
                                                v-if="
                                                    !politica.pdf.endsWith(
                                                        '.pdf'
                                                    ) > 0
                                                "
                                                target="_blank"
                                                :href="politica.pdf"
                                                download
                                            >
                                                <img
                                                    class="h-48"
                                                    style="width: 50rem"
                                                    :src="politica.imagePolitic"
                                                />
                                            </a>
                                            <a v-else>
                                                <img
                                                    class="h-48"
                                                    :href="politica.pdf"
                                                    data-fancybox
                                                    data-type="pdf"
                                                    style="width: 50rem"
                                                    :src="politica.imagePolitic"
                                                />
                                            </a>
                                        </div>
                                    </div>

                                    <div class="pt-6 row">
                                        <span
                                            class="absolute w-2 h-12"
                                            style="float: left"
                                            :style="{
                                                backgroundColor:
                                                    '#' + politica.color,
                                            }"
                                        ></span>
                                        <span
                                            class="text-[#1D2B4E] ;"
                                            style="
                                                font-size: 0.9rem;
                                                font-weight: 600;
                                                margin-left: 2rem;
                                                font-family: 'Montserrat';
                                                display: block;
                                            "
                                            >{{ politica.namepolitica }}</span
                                        >
                                    </div>

                                    <div class="pb-12 row">
                                        <span
                                            class="font-light text-[#1D2B4E]"
                                            style="
                                                font-size: 0.8rem;
                                                margin-left: 2rem;
                                                font-family: 'Montserrat';
                                            "
                                            >{{
                                                politica.nombre +
                                                " " +
                                                politica.apellido_paterno +
                                                " " +
                                                politica.apellido_materno
                                            }}</span
                                        >
                                    </div>

                                    <div
                                        v-if="
                                            $page.props.can['politics.update']
                                        "
                                        style="white-space: normal"
                                        class="absolute z-10 w-6 h-6 py-1 bg-white rounded-full shadow -bottom-2 -right-1 hover:bg-gray-500 hover:text-white"
                                        @click="
                                            showFormPolitic('update', politica)
                                        "
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="w-4 h-4 m-auto"
                                            fill="currentColor"
                                            viewBox="0 0 16 16"
                                        >
                                            <path
                                                d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"
                                            />
                                        </svg>
                                    </div>
                                </CardImageResponsive>
                            </AnimationCard>
                            <div v-else style="display: none"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--END FILES Mobiles-->
        <FormPoliticsModal
            :show="showingFormPolitics"
            :tipoPoliticas="tipoPoliticas"
            :politic="politic"
            :typeForm="typeFormPolitic"
            @close="closeFormPolitics()"
        />
    </AppLayout>
</template>
<style scoped></style>

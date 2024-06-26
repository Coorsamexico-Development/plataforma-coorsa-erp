<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Inertia } from "@inertiajs/inertia";
import CardMenu from "./PartialsDashboard/CardMenu.vue";
import CardNoticias from "./PartialsDashboard/CardNoticias.vue";
import CardVideos from "./PartialsDashboard/CardVideos.vue";
import CardNominas from "./PartialsDashboard/CardNominas.vue";
import CardMV from "./PartialsDashboard/CardMV.vue";
import CarruselNoticias from "./PartialsDashboard/CarruselNoticias.vue";
import CarruselEllosHablan from "./PartialsDashboard/CarruselEllosHablan.vue";
import MenuDesplegable from "./PartialsDashboard/MenuDesplegable.vue";
import ButtonAdd from "@/Components/ButtonAdd.vue";
import { onMounted, reactive, ref, watch } from "vue";
import ModalAddNoticia from "./PartialsDashboard/ModalAddNoticia.vue";
import { Fancybox } from "@fancyapps/ui/src/Fancybox/Fancybox.js";
import Carrusel from "./PartialsDashboard/Carrusel.vue";

var props = defineProps({
    menus: Object,
    noticias: Object,
    videos: Object,
    nominas: Object,
});

let modalAddNoticia = ref(false);

const openModalAgregarNoticia = () => {
    modalAddNoticia.value = true;
};

const closeModalAgregarNoticia = () => {
    modalAddNoticia.value = false;
};
</script>

<template>
    <AppLayout title="Dashboard" :nominas="nominas">
        <section class="inicio">
            <div class="inicio_info">
                <div class="sm:ml-16 sm:mt-0 inicio_info_text">
                    <div class="text1-inicio">
                        <h1
                            class="ml-6 text-2xl font-semibold text-white sm:m-5 sm:text-6xl sm:font-bold"
                            style="font-family: 'Montserrat'"
                        >
                            Somos más que <br />
                            un TEAM.
                        </h1>
                        <span
                            style="position: absolute"
                            class="sm:hidden w-32 h-1 ml-6 bg-[#EC2944]"
                        ></span>
                    </div>
                    <div class="text2-inicio">
                        <h3
                            class="m-5 mt-10 text-xl text-white sm:text-4xl"
                            style="font-family: 'Montserrat'"
                        >
                            <span>Conoce </span> más sobre COORSA
                        </h3>
                        <span
                            style="position: absolute"
                            class="hidden sm:block w-16 h-1 ml-6 bg-[#EC2944]"
                        ></span>
                    </div>
                </div>
            </div>
            <div class="inicio_quienes_somos">
                <div class="inicio_quienes_somos_title">
                    <div>
                        <img
                            class="img_somos"
                            src="../../img/icono-lineas.png"
                        />
                    </div>
                    <div>
                        <h3
                            class="text-[rgb(236,41,68)] font-bold text-3xl sm:text-5xl"
                            style="font-family: 'Montserrat'; font-weight: 500"
                        >
                            QUIENES
                        </h3>
                        <h3
                            class="text-[#1D2B4E] font-bold text-4xl sm:text-9xl"
                            style="font-family: 'Montserrat'; font-weight: 900"
                        >
                            <strong>SOMOS</strong>
                        </h3>
                    </div>
                </div>
                <div class="inicio_quienes_somos_text">
                    <p
                        class="text-base sm:text-2xl"
                        style="font-family: 'Montserrat'; line-height: 1.8"
                    >
                        <span style="font-weight: 600">COORSA</span> es una
                        empresa dedicada a innovar y mejorar procesos de la
                        cadena de suministros con más de 10 servicios dentro del
                        rango del ramo logístico ubicados en más de 9 estados de
                        la república mexicana.
                    </p>
                </div>
            </div>
        </section>

        <section class="w-full h-[30rem] mt-[20rem] grid bg-black">
            <Carrusel />
        </section>

        <section class="section_video">
            <div class="video_section">
                <div class="pt-40 ml-48 sm:pt-20 sm:pb-48">
                    <img
                        class="float-right mt-16 mr-16"
                        src="../../img/VIDEO_APARTADO_icon.png"
                    />
                    <h2
                        class="text-[#EC2944] font-500 text-5xl mt-16"
                        style="font-family: 'Montserrat'"
                    >
                        NUESTRA
                    </h2>
                    <h1
                        class="font-bold text-white text-8xl"
                        style="font-family: 'Montserrat'"
                    >
                        COMUNIDAD
                    </h1>
                </div>
            </div>
            <div
                class="flex justify-center justify-items-center"
                style="margin-top: -7rem"
            >
                <iframe
                    width="1320"
                    height="550"
                    src="https://www.youtube.com/embed/Vrp248tXNb0"
                    title="YouTube video player"
                    frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen
                ></iframe>
            </div>
            <div class="p-16 bg-white"></div>
        </section>
        <section class="section_noti" v-if="noticias.length > 0">
            <div class="flex flex-col items-center mb-8">
                <h1
                    class="text-xl font-bold tracking-widest uppercase sm:text-4xl"
                    style="font-family: 'Montserrat'"
                >
                    <span class="text-[#1A1A22]">Sólo, </span>
                    <span class="text-[#EC2944]"> lo más reciente</span>
                </h1>
            </div>
            <div class="noticias_desp">
                <CardNoticias
                    v-for="noticia in noticias"
                    :key="noticia.id"
                    :noticia="noticia"
                ></CardNoticias>
            </div>
        </section>
        <ButtonAdd
            v-if="$page.props.can['noticias.create']"
            style="float: right"
            class="mr-8"
            @click="openModalAgregarNoticia"
            >Agregar</ButtonAdd
        >
        <ModalAddNoticia
            :show="modalAddNoticia"
            @close="closeModalAgregarNoticia"
        ></ModalAddNoticia>

        <section class="grid grid-rows-3 mt-28 quienes_somos">
            <div class="ml-16 sm:mb-32">
                <h2
                    class="text-[#EC2944] text-5xl font-semibold"
                    style="font-family: 'Montserrat'"
                >
                    ELLOS HABLAN
                </h2>
                <h1
                    class="text-[#1A1A22] font-extrabold text-8xl"
                    style="font-family: 'Montserrat'"
                >
                    POR NOSOTROS
                </h1>
            </div>
            <div class="grid grid-cols-3" style="margin-top: -6rem">
                <div class="flex flex-col items-center pt-9 pb-9">
                    <img
                        class="pt-2 rounded-full h-28 w-18"
                        src="../../img/FOTO_PAOLA.png"
                    />
                    <h2
                        class="text-[#1A1A22] mt-12 font-bold text-center"
                        style="
                            font-family: 'Montserrat';
                            letter-spacing: 0.2rem;
                        "
                    >
                        Paola Granados
                    </h2>
                    <h4
                        class="text-[#1A1A22] text-center font-semibold"
                        style="
                            font-family: 'Montserrat';
                            letter-spacing: 0.3rem;
                        "
                    >
                        ANALISTA DE INSUMOS
                    </h4>
                    <span class="w-16 h-1 bg-[#EC2944]"></span>
                    <p
                        class="text-[#1A1A22] pb-3 mt-3 text-center"
                        style="font-family: 'Montserrat'; line-height: 1.8"
                    >
                        "Durante este tiempo me he<br />
                        sentido muy agusto, son una<br />
                        empresa joven y tienen facilidad de<br />
                        crecimiento laboral y personal."
                    </p>
                </div>
                <div class="bg-[#1A1A22] flex flex-col items-center pt-9 pb-9">
                    <img
                        class="pt-2 rounded-full h-28 w-18"
                        src="../../img/FOTO_LESLYE.png"
                    />
                    <h2
                        class="mt-12 font-bold text-center text-white"
                        style="
                            font-family: 'Montserrat';
                            letter-spacing: 0.2rem;
                        "
                    >
                        Leslye Gallardo
                    </h2>
                    <h4
                        class="font-semibold text-center text-white"
                        style="
                            font-family: 'Montserrat';
                            letter-spacing: 0.3rem;
                        "
                    >
                        JEFA DE PATIO
                    </h4>
                    <span class="w-16 h-1 bg-[#EC2944]"></span>
                    <p
                        class="pb-3 mt-3 text-center text-white"
                        style="font-family: 'Montserrat'; line-height: 1.8"
                    >
                        "Siempre voy a estar muy<br />
                        agradecida por la oportunidad, por<br />
                        creer y confiar en mi trabajo y en<br />
                        mí, somos como una familia."
                    </p>
                </div>
                <div class="flex flex-col items-center pt-9 pb-9">
                    <img
                        class="pt-2 rounded-full h-28 w-18"
                        src="../../img/FOTO_IVONNE.png"
                    />
                    <h2
                        class="text-[#1A1A22] mt-12 font-bold text-center"
                        style="
                            font-family: 'Montserrat';
                            letter-spacing: 0.2rem;
                        "
                    >
                        Ivonne Torres
                    </h2>
                    <h4
                        class="text-[#1A1A22] text-center font-semibold"
                        style="
                            font-family: 'Montserrat';
                            letter-spacing: 0.3rem;
                        "
                    >
                        PROCESS MANAGER JR
                    </h4>
                    <span class="w-16 h-1 bg-[#EC2944]"></span>
                    <p
                        class="text-[#1A1A22] text-center mt-3 pb-10"
                        style="font-family: 'Montserrat'; line-height: 1.8"
                    >
                        "Buscamos que nuestro equipo de<br />
                        trabajo tanto coorporativo como<br />
                        operativo se sientan cómodos y<br />
                        tengan un crecimiento."
                    </p>
                </div>
            </div>
            <div class="flex flex-col items-center justify-center mt-8 mb-4">
                <h3
                    class="text-5xl font-bold"
                    style="font-family: 'Montserrat'"
                >
                    <span class="text-[#1A1A22]">HACIENDO DE LO BUENO, </span>
                    <span class="text-[#EC2944]">ALGO MEJOR</span>
                </h3>
                <h2
                    style="
                        font-family: 'Montserrat';
                        letter-spacing: 2rem;
                        margin-top: 3rem;
                    "
                    class="text-[#1A1A22] text-2xl mt-4"
                >
                    <a href="https://coorsamexico.com/">WWW.COORSAMEXICO.COM</a>
                </h2>
            </div>
        </section>
        <section class="ellos_hablan_carrusel">
            <CarruselEllosHablan></CarruselEllosHablan>
        </section>
    </AppLayout>
</template>

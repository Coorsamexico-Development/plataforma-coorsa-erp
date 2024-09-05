<script setup>
import SwitchButton from "./Partials/SwitchButton.vue";
import Paloma from "@/Iconos/Paloma.vue";
import RedesSociales from "./Partials/RedesSociales.vue";
import { computed, ref, watchEffect } from "vue";

const props = defineProps({
    user: {
        type: Object,
        required: true,
    },
});

const foto = computed(() => props.user.foto);
const switchShow = ref(true);
const widthViewPort = ref(window.innerWidth);
const lenguage = ref("es");

function changeLenguage() {
    switch (lenguage.value) {
        case "es":
            lenguage.value = "eng";
            break;
        case "eng":
            lenguage.value = "es";
            break;
    }
}
</script>
<template>
    <div
        class="w-screen min-h-screen bg-[#f2f2f2] bg-[url('/assets/img/vCardFondo.svg')] bg-cover bg-no-repeat bg-center max-md:px-4 max-md:py-4 max-md:grid md:grid md:place-content-center lg:grid-rows-2 md:px-[3rem] md:py-[3rem] gap-[1rem]"
    >
        <div
            class="flex items-center justify-end h-fit gap-[6px] absolute right-4 top-4"
        >
            <span class="uppercase max-md:text-[10px] font-medium">es</span>
            <div class="p-1 border border-black rounded-full">
                <SwitchButton @click="changeLenguage()" />
            </div>
            <span class="uppercase max-md:text-[10px] font-medium">eng</span>
        </div>
        <div class="grid">
            <div class="grid place-content-center max-md:mt-[10vw]">
                <div class="grid text-center place-content-center">
                    <div class="flex justify-center">
                        <div
                            class="max-md:h-[175px] max-md:w-[175px] md:h-[261px] md:w-[261px] rounded-full border-4 border-black overflow-hidden"
                        >
                            <img :src="foto" alt="profilePhoto" />
                        </div>
                    </div>
                    <div class="flex items-center justify-center gap-[11px]">
                        <span
                            class="max-md:text-[28px] font-bold md:text-[41px] capitalize"
                        >
                            {{ user.name }}
                        </span>
                        <Paloma
                            class="text-[#3F8EFC] max-md:h-[14.5px] max-md:2-[14.5px] md:h-[22px] md:w-[22px]"
                        />
                    </div>
                    <span class="max-md:text-[23px] font-normal md:text-[28px]">
                        COORSA México
                    </span>
                    <span
                        class="max-md:text-[23px] font-bold uppercase md:text-[28px]"
                    >
                        {{ user.puesto[lenguage] }}
                    </span>
                </div>
            </div>
        </div>
        <div class="grid gap-[2rem] max-md:content-end md:grid-cols-2 w-full">
            <RedesSociales
                :user="user"
                @switch-show="
                    (e) =>
                        (switchShow = widthViewPort <= 768 ? !switchShow : true)
                "
                :lenguage="lenguage"
            />
            <div
                class="flex flex-col h-full gap-[25px] w-full"
                v-if="switchShow"
            >
                <div
                    class="flex flex-col max-md:p-[28px] bg-white rounded-2xl drop-shadow-lg max-md:gap-[27px] h-full md:gap-[15px] md:p-[20px]"
                >
                    <span
                        class="max-md:text-[20px] font-bold uppercase md:text-[29px] md:text-center"
                    >
                        {{
                            lenguage === "es"
                                ? "Ubicanos"
                                : lenguage === "eng"
                                ? "Locate Us"
                                : ""
                        }}
                    </span>
                    <div
                        class="max-md:h-[50px] max-md:rounded-full overflow-hidden border border-black md:h-full md:rounded-2xl"
                    >
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d933.3141113668033!2d-100.33468707146899!3d20.659143298801254!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x85cd891af2432995%3A0xa4597b878ffe23a8!2sCOORSA%20M%C3%A9xico%20Soluciones%20Log%C3%ADsticas!5e0!3m2!1ses-419!2smx!4v1725388495986!5m2!1ses-419!2smx"
                            style="border: 0"
                            allowfullscreen="false"
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            class="w-full h-full"
                        ></iframe>
                    </div>
                </div>
                <div class="gird" v-if="user.vCard">
                    <a
                        class="w-full bg-black text-[23px] text-white uppercase py-[11px] rounded-full grid place-content-center"
                        :href="user.vCard"
                        download
                    >
                        {{
                            lenguage === "es"
                                ? "Guardar contacto"
                                : lenguage === "eng"
                                ? "Save Contact"
                                : ""
                        }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>
<style scoped>
::-webkit-scrollbar {
    display: none;
}
</style>

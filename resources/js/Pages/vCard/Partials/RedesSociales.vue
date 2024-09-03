<script setup>
import Mail from "@/Iconos/Mail.vue";
import RedSocialCirculo from "../Partials/RedSocialCirculo.vue";
import LinkedIn from "@/Iconos/LinkedIn.vue";
import Message from "@/Iconos/Message.vue";
import WhatsApp from "@/Iconos/WhatsApp.vue";
import Web from "@/Iconos/Web.vue";
import Twitter from "@/Iconos/Twitter.vue";
import Imagotipo from "@/Iconos/Imagotipo.vue";
import Isotipo from "@/Iconos/Isotipo.vue";
import User from "@/Iconos/User.vue";
import CanadaBandera from "@/Iconos/CanadaBandera.vue";
import MexicoBandera from "@/Iconos/MexicoBandera.vue";
import Close from "@/Iconos/Close.vue";
import Call from "@/Iconos/Call.vue";
import { computed, reactive, ref } from "vue";
import { formatPhoneNumber } from "@/utils/formatCellPhone";

defineProps({
    user: {
        type: Object,
        required: true,
    },
    widthViewPort: {
        type: Number,
        default: window.innerWidth,
    },
    lenguage: {
        type: Text,
    },
});
const emit = defineEmits(["switchShow"]);

const switchView = ref(false);
const socialNetwork = reactive({
    title: "",
    color: "",
    network: "",
});

function clickRedSocial(red = null, color = null, title = null) {
    switchView.value = !switchView.value;
    socialNetwork.color = color;
    socialNetwork.network = red;
    socialNetwork.title = title;
    emit("switchShow");
}

const position = computed(() =>
    switchView.value ? "-translate-y-[55%] font-bold text-[35px] gap-[0px]" : ""
);
</script>
<template>
    <div
        class="max-md:grid max-md:p-[28px] bg-white rounded-2xl drop-shadow-lg max-md:gap-[27px] transition-all duration-200 md:gap-[20px] md:flex md:flex-col md:p-[20px] md:w-[45vw] overf"
        v-if="!switchView"
    >
        <span
            class="font-bold uppercase max-md:text-[20px] md:text-[28px] md:text-center"
        >
            {{
                lenguage === "es"
                    ? "Encuentrame"
                    : lenguage === "eng"
                    ? "Find Me"
                    : ""
            }}
        </span>

        <div
            class="flex max-md:justify-between max-md:overflow-x-auto overflow-y-visible gap-[30px] ScrollBar md:flex-wrap md:justify-center"
        >
            <RedSocialCirculo
                name="Email"
                bgColor="bg-[#D52B1D]"
                @click="clickRedSocial('email', 'bg-[#D52B1D]', 'Email')"
            >
                <Mail class="w-[2rem] h-[2rem] text-white" />
            </RedSocialCirculo>
            <RedSocialCirculo
                name="LinkedIn"
                bgColor="bg-[#0266c8]"
                @click="clickRedSocial('linkedin', 'bg-[#0266c8]', 'LinkedIn')"
            >
                <LinkedIn class="w-[2rem] h-[2rem] text-white" />
            </RedSocialCirculo>
            <RedSocialCirculo
                :name="
                    lenguage === 'es'
                        ? 'Mensajes'
                        : lenguage === 'eng'
                        ? 'Message'
                        : ''
                "
                bgColor="bg-[#00bae6]"
                @click="clickRedSocial('message', 'bg-[#00bae6]', 'Message')"
            >
                <Message class="w-[2rem] h-[2rem] text-white" />
            </RedSocialCirculo>
            <RedSocialCirculo
                name="WhatsApp"
                bgColor="bg-[#00c309]"
                @click="clickRedSocial('whats', 'bg-[#00c309]', 'WhatsApp')"
            >
                <WhatsApp class="w-[2rem] h-[2rem] text-white" />
            </RedSocialCirculo>
            <RedSocialCirculo
                name="Coorsamexico.com"
                bgColor="bg-[#d52b1d]"
                @click="clickRedSocial('web', 'bg-[#d52b1d]', 'Bear Logist')"
            >
                <Web class="w-[2rem] h-[2rem] text-white" />
            </RedSocialCirculo>
            <RedSocialCirculo
                name="Twitter"
                bgColor="bg-[#0b0b0b]"
                @click="clickRedSocial('twitter', 'bg-[#0b0b0b]', 'X')"
                v-if="false"
            >
                <Twitter class="w-[2rem] h-[2rem] text-white" />
            </RedSocialCirculo>
            <RedSocialCirculo
                :name="
                    lenguage === 'es'
                        ? 'Llamar'
                        : lenguage === 'eng'
                        ? 'Call'
                        : ''
                "
                bgColor="bg-[#5dc146]"
                @click="clickRedSocial('call', 'bg-[#5dc146]', 'Call')"
            >
                <Call class="w-[2rem] h-[2rem] text-white" />
            </RedSocialCirculo>
            <RedSocialCirculo
                :name="
                    lenguage === 'es'
                        ? 'Servicio'
                        : lenguage === 'eng'
                        ? 'Service'
                        : ''
                "
                bgColor="bg-[#D52B1D]"
                @click="clickRedSocial('service', 'bg-[#D52B1D]', 'Services')"
            >
                <Isotipo class="w-[2rem] h-[2rem] text-white" />
            </RedSocialCirculo>
        </div>
    </div>
    <div
        class="bg-white relative rounded-2xl drop-shadow-lg transition-all duration-200 max-md:grid max-md:p-[28px] max-md:gap-y-[27px] md:gap-y-[20px] md:flex md:flex-col md:p-[20px] md:w-[45vw]"
        v-else
    >
        <div
            class="absolute grid transition-all duration-300 border border-black rounded-full place-content-center hover:cursor-pointer active:scale-90 max-md:right-[15px] max-md:top-[15px] md:right-[10px] md:top-[10px]"
            @click="clickRedSocial()"
        >
            <Close class="text-[#707070] w-[2rem] h-[2rem]" />
        </div>
        <div class="flex justify-center w-full">
            <RedSocialCirculo
                :name="socialNetwork.title"
                :bgColor="socialNetwork.color"
                @click="clickRedSocial()"
                class="relative transition-all duration-1000"
                :class="position"
            >
                <Mail
                    class="w-[2rem] h-[2rem] text-white"
                    v-if="socialNetwork.network === 'email'"
                />
                <LinkedIn
                    class="w-[2rem] h-[2rem] text-white"
                    v-if="socialNetwork.network === 'linkedin'"
                />
                <Message
                    class="w-[2rem] h-[2rem] text-white"
                    v-if="socialNetwork.network === 'message'"
                />
                <WhatsApp
                    class="w-[2rem] h-[2rem] text-white"
                    v-if="socialNetwork.network === 'whats'"
                />
                <Web
                    class="w-[2rem] h-[2rem] text-white"
                    v-if="socialNetwork.network === 'web'"
                />
                <Twitter
                    class="w-[2rem] h-[2rem] text-white"
                    v-if="socialNetwork.network === 'twitter'"
                />
                <Call
                    class="w-[2rem] h-[2rem] text-white"
                    v-if="socialNetwork.network === 'call'"
                />
                <Isotipo
                    class="w-[2rem] h-[2rem] text-white"
                    v-if="socialNetwork.network === 'service'"
                />
            </RedSocialCirculo>
        </div>

        <!-- Email -->
        <div
            class="grid gap-[15px] place-content-center"
            v-if="socialNetwork.network === 'email'"
        >
            <div
                class="flex items-center gap-[14px] px-[18px] py-[13px] border-2 border-black rounded-full"
            >
                <Mail class="h-[20px]" />
                <span>{{ user.email }}</span>
            </div>
            <a
                class="w-full bg-black text-white py-[11px] rounded-full text-[23px] grid place-content-center px-[2rem]"
                :href="`mailto:${user.email}`"
            >
                <span class="text-[23px]">
                    {{
                        lenguage === "es"
                            ? "Enviar"
                            : lenguage === "eng"
                            ? "Send"
                            : ""
                    }}
                </span>
            </a>
        </div>

        <!-- Twitter y LinkedIn -->
        <div
            class="grid gap-[15px] place-content-center"
            v-if="['linkedin', 'twitter'].includes(socialNetwork.network)"
        >
            <div
                class="flex items-center gap-[14px] px-[18px] py-[13px] border-2 border-black rounded-full"
            >
                <User class="h-[20px]" />
                <span>{{ user.name }}</span>
            </div>
            <a
                class="w-full bg-black text-white py-[11px] rounded-full text-[23px] grid place-content-center px-[2rem]"
                target="_blank"
                :href="user.socialNet[socialNetwork.network]"
            >
                <span class="text-[16px]">
                    {{
                        lenguage === "es"
                            ? "Conecta conmigo"
                            : lenguage === "eng"
                            ? "Connect whit me"
                            : ""
                    }}
                </span>
            </a>
        </div>

        <!-- Message and Call -->
        <div
            v-if="['message', 'call'].includes(socialNetwork.network)"
            class="grid place-content-center gap-[15px]"
        >
            <template v-if="socialNetwork.network === 'call'">
                <a
                    class="flex items-center gap-[14px] px-[18px] py-[13px] border-2 border-black rounded-full"
                    :href="`tel:${user.numbers.mex}`"
                >
                    <MexicoBandera class="w-[1.25rem] h-[1.25rem]" />
                    <span>{{ formatPhoneNumber(user.numbers.mex) }}</span>
                </a>
            </template>
            <template v-else>
                <a
                    class="flex items-center gap-[14px] px-[18px] py-[13px] border-2 border-black rounded-full"
                    :href="`sms:${user.numbers.mex}`"
                >
                    <MexicoBandera class="w-[1.25rem] h-[1.25rem]" />
                    <span>{{ formatPhoneNumber(user.numbers.mex) }}</span>
                </a>
            </template>
        </div>
        <!-- WhatsApp -->
        <div
            class="grid place-content-center gap-[15px]"
            v-if="socialNetwork.network === 'whats'"
        >
            <a
                :href="`https://wa.me/${user.numbers.mex}`"
                class="flex items-center gap-[14px] px-[18px] py-[13px] border-2 border-black rounded-full"
                target="_blank"
            >
                <MexicoBandera class="w-[1.25rem] h-[1.25rem]" />
                <span>{{ formatPhoneNumber(user.numbers.mex) }}</span>
            </a>
        </div>

        <!-- Website -->
        <div
            class="grid place-content-center gap-[15px]"
            v-if="socialNetwork.network === 'web'"
        >
            <a
                href="https://coorsamexico.com"
                class="flex items-center gap-[14px] px-[18px] py-[13px] border-2 border-black rounded-full"
                target="_blanck"
            >
                <Web class="h-[20px]" />
                <span>coorsamexico.com</span>
            </a>
        </div>

        <div class="flex justify-center">
            <Imagotipo class="h-[7rem] w-[7rem]" />
        </div>
    </div>
</template>
<style scoped>
.Scrollbar::-webkit-scrollbar {
    display: none;
}
</style>

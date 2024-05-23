<script setup>
import { computed, onMounted, onUnmounted, watch, watchEffect } from "vue";

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    maxWidth: {
        type: String,
        default: "2xl",
    },
    closeable: {
        type: Boolean,
        default: true,
    },
    position: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(["close"]);

watch(
    () => props.show,
    () => {
        if (props.show) {
            document.body.style.overflow = "hidden";
        } else {
            document.body.style.overflow = null;
        }
    }
);

const close = () => {
    if (props.closeable) {
        emit("close");
    }
};

const closeOnEscape = (e) => {
    if (e.key === "Escape" && props.show) {
        close();
    }
};

onMounted(() => document.addEventListener("keydown", closeOnEscape));

onUnmounted(() => {
    document.removeEventListener("keydown", closeOnEscape);
    document.body.style.overflow = null;
});

const maxWidthClass = computed(() => {
    return {
        xs: "sm:max-w-xs",
        sm: "sm:max-w-sm",
        md: "sm:max-w-md",
        lg: "sm:max-w-lg",
        xl: "sm:max-w-xl",
        "2xl": "sm:max-w-2xl",
        "3xl": "sm:max-w-3xl",
        "4xl": "sm:max-w-4xl",
        "5xl": "sm:max-w-5xl",
        "6xl": "sm:max-w-6xl",
        "80%": "sm:max-w-[90vw]",
        full: "sm:max-w-full",
    }[props.maxWidth];
});

const bodyPosition = computed(() => {
    if (props.show) {
        let position = props.position;
        let coordX = ""; //Coordenada X
        let coordY = ""; //Coordenada Y
        let rounded = ""; //Cual no redondear
        let fullScreenH = window.innerHeight; //Obtiene el alto del viewport
        let fullScreenW = window.innerWidth; //Obtiene el ancho del viewport

        //Revisamos en que cuadrante se esta dando click
        switch (true) {
            //Primer cuadrante Top-Left
            case position.top <= fullScreenH / 2 &&
                position.left <= fullScreenW / 2:
                coordY = `top:${position.bottom.toFixed(0)}px`;
                coordX = `left:${position.right.toFixed(0)}px`;
                rounded = "border-top-left-radius: 0;";
                break;
            //Segundo cudrante Top-Right
            case position.top <= fullScreenH / 2 &&
                position.left >= fullScreenW / 2:
                coordY = `top:${position.bottom.toFixed(0)}px`;
                coordX = `right:${(fullScreenW - position.left).toFixed(0)}px`;
                rounded = "border-top-right-radius: 0;";
                break;
            //Tercer Cuadrante Bottom-Left
            case position.top >= fullScreenH / 2 &&
                position.left <= fullScreenW / 2:
                coordY = `bottom:${(fullScreenH - position.top).toFixed(0)}px`;
                coordX = `left:${position.right.toFixed(0)}px`;
                rounded = "border-bottom-left-radius: 0;";
                break;
            //Cuarto Cuadrante Bottom-Right
            case position.top >= fullScreenH / 2 &&
                position.left >= fullScreenW / 2:
                coordY = `bottom:${(fullScreenH - position.top).toFixed(0)}px`;
                coordX = `right:${(fullScreenW - position.left).toFixed(0)}px`;
                rounded = "border-bottom-right-radius: 0;";
                break;
        }

        let pos = `${coordX}; ${coordY}; ${rounded}`;

        return pos;
    }
});
</script>

<template>
    <teleport to="body">
        <transition leave-active-class="duration-200">
            <div
                v-show="show"
                class="fixed inset-0 z-50 w-screen overflow-y-auto sm:px-0"
                scroll-region
            >
                <transition
                    enter-active-class="duration-300 ease-out"
                    enter-from-class="opacity-0"
                    enter-to-class="opacity-100"
                    leave-active-class="duration-200 ease-in"
                    leave-from-class="opacity-100"
                    leave-to-class="opacity-0"
                >
                    <div
                        v-show="show"
                        class="fixed inset-0 transition-all transform"
                        @click="close"
                    >
                        <div class="absolute inset-0" />
                    </div>
                </transition>
                <transition
                    enter-active-class="duration-300 ease-out"
                    enter-from-class="translate-y-4 opacity-0 sm:translate-y-0 sm:scale-95"
                    enter-to-class="translate-y-0 opacity-100 sm:scale-100"
                    leave-active-class="duration-200 ease-in"
                    leave-from-class="translate-y-0 opacity-100 sm:scale-100"
                    leave-to-class="translate-y-4 opacity-0 sm:translate-y-0 sm:scale-95"
                >
                    <div
                        v-show="show"
                        class="transition-all transform bg-[#FFFFFF] text-[#05173B] rounded-3xl drop-shadow-[0px_0px_6px_#9F9F9F] sm:w-full absolute"
                        :class="[maxWidthClass]"
                        :style="bodyPosition"
                    >
                        <slot v-if="show" />
                    </div>
                </transition>
            </div>
        </transition>
    </teleport>
</template>

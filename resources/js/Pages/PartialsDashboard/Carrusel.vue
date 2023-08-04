<script setup>
import { onMounted } from "vue";
import { initFlowbite } from "flowbite";

// initialize components based on data attribute selectors
onMounted(() => {
    initFlowbite();
    const items = [
        {
            position: 0,
            el: document.getElementById("carousel-item-1"),
        },
        {
            position: 1,
            el: document.getElementById("carousel-item-2"),
        },
        {
            position: 2,
            el: document.getElementById("carousel-item-3"),
        },
        {
            position: 3,
            el: document.getElementById("carousel-item-4"),
        },
        {
            position: 4,
            el: document.getElementById("carousel-item-5"),
        },
        {
            position: 5,
            el: document.getElementById("carousel-item-6"),
        },
        {
            position: 6,
            el: document.getElementById("carousel-item-7"),
        },
        {
            position: 7,
            el: document.getElementById("carousel-item-8"),
        },
    ];

    const options = {
        defaultPosition: 0,
        interval: 10000,

        indicators: {
            activeClasses: "bg-white dark:bg-gray-800",
            inactiveClasses:
                "bg-white/50 dark:bg-gray-800/50 hover:bg-white dark:hover:bg-gray-800",
            items: [
                {
                    position: 0,
                    el: document.getElementById("carousel-indicator-1"),
                },
                {
                    position: 1,
                    el: document.getElementById("carousel-indicator-2"),
                },
                {
                    position: 2,
                    el: document.getElementById("carousel-indicator-3"),
                },
                {
                    position: 3,
                    el: document.getElementById("carousel-indicator-4"),
                },
                {
                    position: 4,
                    el: document.getElementById("carousel-indicator-5"),
                },
                {
                    position: 5,
                    el: document.getElementById("carousel-indicator-6"),
                },
                {
                    position: 6,
                    el: document.getElementById("carousel-indicator-7"),
                },
                {
                    position: 7,
                    el: document.getElementById("carousel-indicator-8"),
                },
            ],
        },

        // callback functions
        onNext: () => {},
        onPrev: () => {},
        onChange: () => {},
    };

    const carousel = new Carousel(items, options);
    carousel.cycle();
});
</script>

<template>
    <div id="default-carousel" class="relative w-full">
        <!-- Carousel wrapper -->
        <div class="relative h-full overflow-hidden">
            <!-- Item 1 -->
            <div id="carousel-item-1" class="hidden duration-700 ease-in-out">
                <img
                    src="/assets/img/MisionQuinta.png"
                    class="absolute block w-full h-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2"
                    alt="..."
                />
            </div>
            <!-- Item 2 -->
            <div id="carousel-item-2" class="hidden duration-700 ease-in-out">
                <img
                    src="/assets/img/VisionQuinta.png"
                    class="absolute block w-full h-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2"
                    alt="..."
                />
            </div>
            <!-- Item 3 -->
            <div id="carousel-item-3" class="hidden duration-700 ease-in-out">
                <img
                    src="/assets/img/MisionCoord.png"
                    class="absolute block w-full h-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2"
                    alt="..."
                />
            </div>
            <!-- Item 4 -->
            <div id="carousel-item-4" class="hidden duration-700 ease-in-out">
                <img
                    src="/assets/img/VisionCoord.png"
                    class="absolute block w-full h-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2"
                    alt="..."
                />
            </div>
            <!-- Item 5 -->
            <div id="carousel-item-5" class="hidden duration-700 ease-in-out">
                <img
                    src="/assets/img/MisionMani.png"
                    class="absolute block w-full h-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2"
                    alt="..."
                />
            </div>
            <!-- Item 6 -->
            <div id="carousel-item-6" class="hidden duration-700 ease-in-out">
                <img
                    src="/assets/img/VisionMani.png"
                    class="absolute block w-full h-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2"
                    alt="..."
                />
            </div>
            <!-- Item 7 -->
            <div id="carousel-item-7" class="hidden duration-700 ease-in-out">
                <img
                    src="/assets/img/MisionWhare.png"
                    class="absolute block w-full h-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2"
                    alt="..."
                />
            </div>
            <!-- Item 8 -->
            <div id="carousel-item-8" class="hidden duration-700 ease-in-out">
                <img
                    src="/assets/img/VisionWhare.png"
                    class="absolute block w-full h-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2"
                    alt="..."
                />
            </div>
        </div>
        <!-- Slider indicators -->
        <div
            class="absolute z-30 flex space-x-3 -translate-x-1/2 bottom-5 left-1/2"
        >
            <button
                type="button"
                id="carousel-indicator-1"
                class="w-3 h-3 rounded-full"
                aria-current="true"
                aria-label="Slide 1"
            ></button>
            <button
                type="button"
                id="carousel-indicator-2"
                class="w-3 h-3 rounded-full"
                aria-current="false"
                aria-label="Slide 2"
            ></button>
            <button
                type="button"
                id="carousel-indicator-3"
                class="w-3 h-3 rounded-full"
                aria-current="false"
                aria-label="Slide 3"
            ></button>
            <button
                type="button"
                id="carousel-indicator-4"
                class="w-3 h-3 rounded-full"
                aria-current="false"
                aria-label="Slide 4"
            ></button>
            <button
                type="button"
                id="carousel-indicator-5"
                class="w-3 h-3 rounded-full"
                aria-current="false"
                aria-label="Slide 5"
            ></button>
            <button
                type="button"
                id="carousel-indicator-6"
                class="w-3 h-3 rounded-full"
                aria-current="false"
                aria-label="Slide 6"
            ></button>
            <button
                type="button"
                id="carousel-indicator-7"
                class="w-3 h-3 rounded-full"
                aria-current="false"
                aria-label="Slide 7"
            ></button>
            <button
                type="button"
                id="carousel-indicator-8"
                class="w-3 h-3 rounded-full"
                aria-current="false"
                aria-label="Slide 8"
            ></button>
        </div>
    </div>
</template>

<script setup>
import { Inertia } from "@inertiajs/inertia";
import { usePage } from "@inertiajs/inertia-vue3";
import { computed, ref, watchEffect } from "vue";
const props = defineProps({
    pagination: Object,
});

const loadPage = (newPage) => {
    Inertia.get(
        usePage().url.value,
        { page: newPage },
        {
            replace: true,
            preserveScroll: true,
            preserveState: true,
        }
    );
};

const noPreviousPage = computed(() => {
    return props.pagination.current_page - 1 <= 0;
});
const noNextPage = computed(() => {
    return props.pagination.current_page + 1 > props.pagination.last_page;
});

const page = computed(() => {
    return props.pagination.current_page;
});
</script>

<template>
    <div class="inline-flex items-center justify-center px-2 text-xs w-full">
        <div
            class="flex space-x-1 items-center w-full justify-between"
            v-if="pagination.last_page > 1"
        >
            <button
                :disabled="noPreviousPage"
                :class="{ 'opacity-50': noPreviousPage }"
                @click="loadPage(1)"
                class="inline-flex items-center justify-center text-gray-500 bg-white border border-gray-200 rounded shadow-sm outline-none w-4 h-4 hover:bg-gray-50 lg:text-xs focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="w-4 h-4 lg:h-3 lg:w-3"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M11 19l-7-7 7-7m8 14l-7-7 7-7"
                    />
                </svg>
            </button>
            <button
                :disabled="noPreviousPage"
                :class="{ 'opacity-50': noPreviousPage }"
                @click="loadPage(pagination.current_page - 1)"
                class="inline-flex items-center justify-center text-gray-500 bg-white border border-gray-200 rounded shadow-sm outline-none w-4 h-4 hover:bg-gray-50 focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="w-4 h-4 lg:h-3 lg:w-3"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M15 19l-7-7 7-7"
                    />
                </svg>
            </button>

            <div
                class="flex flex-col space-y-2 md:flex-row md:space-y-0 md:items-center md:space-x-1"
            >
                <input
                    type="number"
                    @keydown.enter="loadPage(page)"
                    v-model="page"
                    class="p-0 text-center border border-gray-400 rounded shadow-sm w-6 h-4 lg:text-xs focus:ring-blue-500 focus:border-blue-500"
                />
                <div class="px-2 text-gray-600 lg:text-xs">
                    de {{ pagination.last_page }}
                </div>
            </div>

            <button
                :disabled="noNextPage"
                :class="{ 'opacity-50': noNextPage }"
                @click="loadPage(pagination.current_page + 1)"
                class="inline-flex items-center justify-center text-gray-500 bg-white border border-gray-300 rounded shadow-sm outline-none w-4 h-4 hover:bg-gray-50 focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="w-4 h-4 lg:h-3 lg:w-3"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 5l7 7-7 7"
                    />
                </svg>
            </button>

            <button
                :disabled="noNextPage"
                :class="{ 'opacity-50': noNextPage }"
                @click="loadPage(pagination.last_page)"
                class="inline-flex items-center justify-center text-gray-500 bg-white border border-gray-300 rounded shadow-sm outline-none w-4 h-4 hover:bg-gray-50 focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="w-4 h-4 lg:h-3 lg:w-3"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M13 5l7 7-7 7M5 5l7 7-7 7"
                    />
                </svg>
            </button>
        </div>
    </div>
</template>

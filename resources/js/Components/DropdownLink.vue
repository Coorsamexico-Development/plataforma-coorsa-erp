<script setup>
import { Link } from "@inertiajs/inertia-vue3";

defineProps({
    href: String,
    as: String,
    target: String,
    rel: {
        type: String,
        default: "noopener noreferrer",
    },
    method: String,
    data: {},
});
</script>

<template>
    <div>
        <button
            v-if="as == 'button'"
            type="submit"
            class="block w-full px-4 py-2 text-sm leading-5 text-left text-gray-700 transition hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
        >
            <slot />
        </button>

        <a
            v-else-if="as == 'a'"
            :href="href"
            :target="target"
            :rel="rel"
            class="block px-4 py-2 text-sm leading-5 text-gray-700 transition hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
        >
            <slot />
        </a>

        <Link
            v-else
            :href="href"
            :method="method"
            :data="data"
            class="block px-4 py-2 text-sm leading-5 text-gray-700 transition hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
        >
            <slot />
        </Link>
    </div>
</template>

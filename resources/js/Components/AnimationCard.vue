<template>
    <TransitionGroup name="list" mode="out-in" tag="div" class="flex flex-wrap gap-4 py-6">
        <slot />
    </TransitionGroup>
</template>

<style>
.list-enter-active,
.list-leave-active {
    transition: all 0.3s ease;
}

.list-enter-from,
.list-leave-to {
    opacity: 0;
    transform: translateY(30px);
}
</style>

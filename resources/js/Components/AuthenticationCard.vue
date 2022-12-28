<template>
    <div class="flex flex-col items-center min-h-screen pt-6 sm:justify-center sm:pt-0 ">
        <div class="texts_login" style="margin-right:50rem;margin-top:-10rem; margin-bottom:2rem; ">
            <h4 class="text-5xl font-light text-white" style="font-family: 'Montserrat';">Plataforma</h4>
            <h2 class="text-5xl font-bold text-white" style="font-family: 'Montserrat';">CONTROL INTERNO</h2>
        </div>
        <div>
            <slot name="logo" />
        </div>
        <div class="w-full px-6 py-4 mt-6 overflow-hidden shadow-md sm:max-w-md opacity-90 sm:rounded-lg">
            <slot />
        </div>
    </div>
</template>

<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 ">
        <div class="texts_login" style="margin-right:40rem;margin-top:0rem; margin-bottom:4rem">
            <h1 class="text-white font-bold text-3xl">BIENVENIDO A</h1>
            <h1 class="text-white  text-6xl" style="letter-spacing:0.3rem; font-weight:950;">Plataforma COORSA</h1>
            <p class="text-white font-bold">"Una gran visión sin grandes personas es irrelevante"<br>
            -Jim Collins</p>
        </div>
        <div>
            <slot name="logo" />
        </div>
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-[#151e33] opacity-90 shadow-md overflow-hidden sm:rounded-lg">
            <slot />
        </div>
    </div>
</template>

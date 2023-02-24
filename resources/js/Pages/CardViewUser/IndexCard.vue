<script setup>


const share = () => 
{
   navigator.share({ 
    title: 'Título para compartir', 
    text: 'Texto para compartir', 
    url: 'https://erp.coorsamexico.com/card/user'
  })
}
</script>
<template>
  <span class="absolute mt-8 ml-8">
    <svg xmlns="http://www.w3.org/2000/svg" width="15.786" height="25.659" viewBox="0 0 15.786 25.659">
      <path id="Trazado_3" data-name="Trazado 3" d="M18.949,76.591l-2.671-2.828,10.182-9.62L16.219,53.651,19,50.932l13,13.324Z" transform="translate(-16.219 -50.932)" fill="#e84a49"/>
    </svg>
  </span>
  <span class="absolute h-12 bg-[#E84A49] ml-72" style="width:2px;"></span>
  <div>
     <img src="../../../img/renato.jpg" />
  </div>
  <div class="w-full h-32 bg-white"  style="transform: skewY(-11deg); margin-top: -3rem; ">
  </div>
  <span class="absolute h-14 bg-[#E84A49] ml-8" style="width:2px; margin-top:-8rem"></span>
  <div class="relative flex flex-col" style="font-family: 'Montserrat'; z-index: 5; margin-top: -6rem;">
    <div class="flex flex-col items-center justify-center">
        <h1 class="font-semibold uppercase" style="letter-spacing: 4px; font-size: 1.5rem;">RENATO ORTIZ</h1>
        <h2 class="uppercase text-l" style="letter-spacing: 6px;">CEO</h2>
    </div>
    <div class="">
          <div class="m-8">
          <div class="flex">
              <img class="mr-2" src="../../../img/telefono.svg" />
              <a class="text-sm" href="tel: +52 55 8025 5972">55 8025 5972</a>
          </div>
      </div>
      <div class="m-8 mt-2">
          <div class="flex">
              <img class="mr-2" src="../../../img/gmail.svg" />
              <a class="text-sm" href="mailto: renato.ortiz@coorsamexico.com">renato.ortiz@coorsamexico.com</a>
          </div>
      </div>
      <div class="m-8 mt-2">
            <div class="flex">
                <img class="mr-2" src="../../../img/ubicacion.svg" />
                <a class="text-sm" href="#">Querétaro Corporativo</a>
            </div>

          </div>
    </div>
    <div class="flex justify-center">
        <span class="w-10/12  absolute bg-[#E84A49]" style="height: 2px;"></span>
    </div>
  </div>
  <div class="flex flex-row justify-center mt-8 ">
     <div class="m-2">
        <a href="tel: +52 55 8025 5972">
            <button class="border rounded-full p-3 bg-[#F7F7F7] drop-shadow-xl">
              <img src="../../../img/telefono.svg" />
            </button>
        </a>
     </div>
     <div class="m-2">
        <a href="https://api.whatsapp.com/send?phone=5580255972">
            <button class="border rounded-full p-3 bg-[#F7F7F7] drop-shadow-xl">
                <img src="../../../img/whatsapp.svg" />
            </button>
        </a>
     </div>
     <div class="m-2">
        <a href="mailto: renato.ortiz@coorsamexico.com">
          <button class="border rounded-full p-3 bg-[#F7F7F7] drop-shadow-xl">
              <img class="" src="../../../img/gmail.svg" />
          </button>
        </a>
     </div>
     <div class="m-2">
       <a href="https://goo.gl/maps/jn9h3GzbkUrTjT168">
         <button class="border rounded-full p-2 pl-3 pr-3 bg-[#F7F7F7] drop-shadow-xl">
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="21.393" height="27.112" viewBox="0 0 21.393 27.112">
               <defs>
                 <clipPath id="clip-path">
                   <rect id="Rectángulo_47" data-name="Rectángulo 47" width="21.393" height="27.112" fill="none"/>
                 </clipPath>
               </defs>
               <g id="Grupo_122" data-name="Grupo 122" transform="translate(-15.832 -12.221)">
                 <g id="Grupo_108" data-name="Grupo 108" transform="translate(15.832 12.221)" opacity="0.35">
                   <g id="Grupo_107" data-name="Grupo 107">
                     <g id="Grupo_106" data-name="Grupo 106" clip-path="url(#clip-path)">
                       <path id="Trazado_30" data-name="Trazado 30" d="M169.951,338.607a10.537,10.537,0,0,0-14.86,0c-3.677,3.679-4.253,8.095-1.711,13.128,2.087,4.128,5.841,7.882,8.584,10.625l.108.108a.637.637,0,0,0,.9,0l.108-.108c2.743-2.743,6.5-6.5,8.584-10.625C174.2,346.7,173.629,342.286,169.951,338.607Zm.577,12.555c-1.911,3.782-5.363,7.311-8.007,9.958-2.644-2.648-6.1-6.177-8.007-9.958-2.3-4.551-1.816-8.364,1.477-11.655a9.245,9.245,0,0,1,13.06,0C172.344,342.8,172.826,346.611,170.528,351.162Zm-8.007-9.17a4.669,4.669,0,1,0,4.669,4.668A4.674,4.674,0,0,0,162.521,341.992Zm0,8.065a3.4,3.4,0,1,1,3.4-3.4,3.4,3.4,0,0,1-3.4,3.4" transform="translate(-151.825 -335.542)"/>
                     </g>
                   </g>
                 </g>
               </g>
             </svg>
         </button>
       </a>  
     </div>
     <div class="m-2">
          <button @click="share()" class="border rounded-full p-3 bg-[#F7F7F7] drop-shadow-xl">
              <img class="" src="../../../img/Share.png" />
          </button>

     </div>
  </div>
</template>

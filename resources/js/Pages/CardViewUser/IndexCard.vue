<script setup>

var props = defineProps(
    {
       datos_usuario:Object
    }
);



const share = () => 
{
   navigator.share({ 
    title: '¡ Te comparto mi tarjeta de presentación digital !', 
    text: ' ¡ Te comparto mi tarjeta de presentación digital !', 
    url: 'https://erp.coorsamexico.com/card/user/'+props.datos_usuario[0].numero_empleado
  })
}
</script>
<template>
  <div v-if="datos_usuario.length > 0">
    <div v-for="(dato, index) in datos_usuario" :key="index">
      <span class="absolute mt-8 ml-8">
       <svg xmlns="http://www.w3.org/2000/svg" width="15.786" height="25.659" viewBox="0 0 15.786 25.659">
         <path id="Trazado_3" data-name="Trazado 3" d="M18.949,76.591l-2.671-2.828,10.182-9.62L16.219,53.651,19,50.932l13,13.324Z" transform="translate(-16.219 -50.932)" fill="#e84a49"/>
       </svg>
     </span>
     <span class="absolute h-12 bg-[#E84A49] ml-80" style="width:2px;"></span>
     <div>
        <img v-if="dato.foto_empresarial" class="w-full h-66" :src="dato.foto_empresarial" alt="fotografia"  />
        <img v-else-if="dato.fotografia" class="w-full h-66" :src="dato.fotografia"  alt="fotografia" />
        <img v-else class="w-full h-66" src="data:image/png;base64,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" alt="fotografia" />
     </div>
     <div class="w-full h-32 bg-white"  style="transform: skewY(-11deg); margin-top: -3rem; ">
     </div>
     <span class="absolute h-14 bg-[#E84A49] ml-8" style="width:2px; margin-top:-9rem"></span>
     <div class="relative flex flex-col" style="font-family: 'Montserrat'; z-index: 5; margin-top: -6rem;">
       <div class="flex flex-col items-center justify-center mt-8">
           <h1 class="font-semibold text-center uppercase" style="letter-spacing: 1.98px; font-size: 23px;">{{dato.name + ' ' + dato.apellido_paterno}}</h1>
           <h2 class="mt-2 text-center uppercase " style="letter-spacing: 7.2px; font-size: 14px;">{{dato.puesto_name}}</h2>
       </div>
       <div class="">
             <div class="m-8">
             <div class="flex">
                 <img class="mr-2" src="../../../img/telefono.svg" />
                 <a v-if="dato.telefono_empresarial" class="text-sm" :href="'tel:+52'+ dato.telefono_empresarial">{{dato.telefono_empresarial}}</a>
                 <a v-else class="text-sm" :href="'tel:+52'+ dato.telefono">{{dato.telefono}}</a>
             </div>
         </div>
         <div class="m-8 mt-2">
             <div class="flex">
                 <img class="mr-2" src="../../../img/gmail.svg" />
                 <a v-if="dato.correo_empresarial" class="text-sm" :href="'mailto:'+dato.correo_empresarial">{{ dato.correo_empresarial }}</a>
                 <a v-else class="text-sm" :href="'mailto:'+dato.email">{{ dato.email }}</a>
             </div>
         </div>
         <div class="m-8 mt-2">
               <div class="flex">
                   <img class="mr-2" src="../../../img/ubicacion.svg" />
                   <a v-if="dato.ubicacion_name" class="text-sm" href="#">{{ dato.ubicacion_name }}</a>
                   <a class="text-sm" v-else>Querétaro Corporativo.</a>
               </div>
    
             </div>
       </div>
       <div class="flex justify-center">
           <span class="w-10/12  absolute bg-[#E84A49]" style="height: 2px;"></span>
       </div>
     </div>
     <div class="flex flex-row justify-center mt-8 ">
        <div class="m-2">
           <a v-if="dato.telefono_empresarial"  :href="'tel: +52' + dato.telefono_empresarial">
               <button class="rounded-full p-3 bg-[#F7F7F7] drop-shadow-xl">
                 <img src="../../../img/telefono.svg" />
               </button>
           </a>
           <a v-else :href="'tel: +52' + dato.telefono">
               <button class="rounded-full p-3 bg-[#F7F7F7] drop-shadow-xl">
                 <img src="../../../img/telefono.svg" />
               </button>
           </a>
        </div>
        <div class="m-2">
           <a v-if="dato.telefono_empresarial"  :href="'https://api.whatsapp.com/send?phone='+dato.telefono_empresarial">
               <button class="rounded-full p-3 bg-[#F7F7F7] drop-shadow-xl">
                   <img src="../../../img/whatsapp.svg" />
               </button>
           </a>
           <a v-else :href="'https://api.whatsapp.com/send?phone='+dato.telefono">
               <button class="rounded-full p-3 bg-[#F7F7F7] drop-shadow-xl">
                   <img src="../../../img/whatsapp.svg" />
               </button>
           </a>
        </div>
        <div class="m-2">
           <a v-if="dato.correo_empresarial" :href="'mailto:'+dato.correo_empresarial">
             <button class="rounded-full p-3 bg-[#F7F7F7] drop-shadow-xl">
                 <img class="" src="../../../img/gmail.svg" />
             </button>
           </a>
           <a v-else :href="'mailto:'+dato.email">
             <button class="rounded-full p-3 bg-[#F7F7F7] drop-shadow-xl">
                 <img class="" src="../../../img/gmail.svg" />
             </button>
           </a>
        </div>
        <div class="m-2">
          <a v-if="dato.ubicacion_google" :href=" dato.ubicacion_google">
            <button class="rounded-full p-2 pl-3 pr-3 bg-[#F7F7F7] drop-shadow-xl">
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
          <a v-else href="https://goo.gl/maps/jn9h3GzbkUrTjT168">
            <button class="rounded-full p-2 pl-3 pr-3 bg-[#F7F7F7] drop-shadow-xl">
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
             <button @click="share()" class="rounded-full p-3 bg-[#F7F7F7] drop-shadow-xl">
                 <img class="" src="../../../img/Share.png" />
             </button>
        </div>
      </div>
    </div>
  </div>
  <div v-else>
      NO HAY INFORMACIÓN.
  </div>
</template>

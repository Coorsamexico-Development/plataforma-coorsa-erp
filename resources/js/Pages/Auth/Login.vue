<script setup>
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import { ref, onMounted } from 'vue'
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import ButtonAdd from '@/Components/ButtonAdd.vue';
import $ from 'jquery';

onMounted(() => {
  $(".info-item .btn").click(function(){
    $(".container").toggleClass("log-in");
  });

   $(".container-form .btn").click(function(){
     $(".container").addClass("active");
   });
})

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
    status: String,
});

const submit = () => {
    form.transform(data => ({
        ...data,
        remember: form.remember ? 'on' : '',
    })).post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};


/*Form reset password*/
const formReset = useForm({
    email: '',
});

const submitReset = () => 
{
    formReset.post(route('password.email'));
};
</script>

<template>
    <Head title="Log in" />
    <div class="login">
      <div style="border:0.1rem white solid;grid-column: 2/5;grid-row: 2/3; border-radius: 2rem; width:50rem; height:20rem; display: flex; justify-content: center;">
          <div class="container">
          <div class="box"></div>
          <div class="container-forms">
            <div class="container-info">
              <div class="info-item">
                <div class="table">
                  <div class="table-cell">
                    <p class="text-white" style="font-family: 'Montserrat'; font-size: 1rem;">
                      ¿Ya tienes una cuenta?
                    </p>
                    <div class="btn" style="display:flex; justify-content:center; font-size: 1rem; width: 0rem;">
                      Iniciar sesión
                    </div>
                  </div>
                </div>
              </div>
              <div class="info-item">
                <div class="table" style="margin-left: 2rem;">
                  <div class="table-cell">
                    <p style="color:white; margin:0;font-size: 1rem;">
                      ¿Olvidaste tu <br>contraseña?
                    </p>
                    <div class="btn" style="display:flex; align-items: center;justify-content: center; margin-top: 1rem;">
                       <p style="font-size:0.8rem">Reestablecer</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="container-form">
              <div class="form-item log-in">
                <div class="table">
                  <div class="table-cell">
                     <div class="mt-4" style="text-align:justify; margin-left:2rem;">
                      <span class="w-1 h-12 bg-[#EC2944] mt-1" style="position:absolute;"></span>
                        <h1 class="ml-2 text-xl" style="font-family: 'Montserrat';">Bienvenido a<br>
                           <span class="font-extrabold uppercase" >Intranet Coorsa</span>
                        </h1>
                     </div>
                     <div class="m-8">
                       <form >
                           <div class="mt-10">
                             <TextInput
                                   id="email"
                                   v-model="form.email"
                                   type="email"
                                   class="block w-full mt-1 text-gray-500 opacity-80"
                                   required
                                   autofocus
                                   placeholder="Usuario"
                                   style="border-radius:5rem; font-family: 'Montserrat'; padding-left: 3rem; font-size: 0.9rem;"
                               />
                               <span class="ml-2" style="position:absolute; margin-top:-3.5rem">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="39" height="39" viewBox="0 0 39 39">
                                   <defs>
                                     <clipPath id="clip-path">
                                       <rect id="Rectángulo_3" data-name="Rectángulo 3" width="32.584" height="32.647" fill="#b7b7b7"/>
                                     </clipPath>
                                     <clipPath id="clip-Icono-usuario">
                                       <rect width="39" height="39"/>
                                     </clipPath>
                                   </defs>
                                   <g id="Icono-usuario" clip-path="url(#clip-Icono-usuario)">
                                     <rect width="39" height="39" fill="rgba(255,255,255,0)"/>
                                     <g id="Grupo_20" data-name="Grupo 20" transform="translate(3 3)">
                                       <g id="Grupo_1" data-name="Grupo 1" clip-path="url(#clip-path)">
                                         <path id="Trazado_1" data-name="Trazado 1" d="M21.567,15.824a37.994,37.994,0,0,1,3.623,1.939,16.026,16.026,0,0,1,7.393,13.445,1.262,1.262,0,0,1-1.41,1.438H1.408A1.27,1.27,0,0,1,0,31.241,16.337,16.337,0,0,1,10.738,16.065c.122-.043.24-.1.4-.16A8.734,8.734,0,0,1,7.541,8.273a8.393,8.393,0,0,1,2.841-5.965A8.782,8.782,0,1,1,21.567,15.824M30,30.118a13.743,13.743,0,0,0-27.379,0ZM10.026,8.759a6.267,6.267,0,1,0,6.315-6.248,6.285,6.285,0,0,0-6.315,6.248" transform="translate(0 0)" fill="#b7b7b7"/>
                                       </g>
                                     </g>
                                   </g>
                                </svg>
                               </span>
                               <InputError class="" :message="form.errors.email" />
                           </div>
                            <div class="mt-10">
                              <TextInput
                                  id="password"
                                  v-model="form.password"
                                  type="password"
                                  class="block w-full mt-1 text-gray-500 opacity-80 "
                                  required
                                  autocomplete="current-password"
                                  placeholder="Contraseña"
                                  style="border-radius:5rem; padding-left: 3.5rem;"
                              />
                              <span class="ml-2" style="position:absolute; margin-top:-3.5rem;">
                                <svg  style="" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="39" height="39" viewBox="0 0 39 39">
                                  <defs>
                                    <clipPath id="clip-path">
                                      <rect id="Rectángulo_4" data-name="Rectángulo 4" width="27.938" height="37.302" fill="#b7b7b7"/>
                                    </clipPath>
                                    <clipPath id="clip-Icono-candado">
                                      <rect width="39" height="39"/>
                                    </clipPath>
                                  </defs>
                                  <g id="Icono-candado" clip-path="url(#clip-Icono-candado)">
                                    <rect width="39" height="39" fill="rgba(255,255,255,0)"/>
                                    <g id="Grupo_21" data-name="Grupo 21" transform="translate(6 1)">
                                      <g id="Grupo_3" data-name="Grupo 3" clip-path="url(#clip-path)">
                                        <path id="Trazado_2" data-name="Trazado 2" d="M3.5,15.063c0-.1,0-.248,0-.4.009-1.6-.04-3.2.038-4.8A10.456,10.456,0,0,1,24.3,8.791a13.673,13.673,0,0,1,.136,1.965c.02,1.315.006,2.631.006,3.946v.387a11.349,11.349,0,0,1,1.275.3,3.4,3.4,0,0,1,2.217,3.346c.012,1.728,0,3.456,0,5.185q0,4.856,0,9.711a3.36,3.36,0,0,1-1.974,3.311,3.751,3.751,0,0,1-1.525.339q-10.465.033-20.929.01a3.424,3.424,0,0,1-3.5-3.583c-.014-2.012,0-4.024,0-6.036q0-4.43,0-8.86A3.378,3.378,0,0,1,2.049,15.47a10.134,10.134,0,0,1,1.45-.408m10.47,2.423H3.775c-1.072,0-1.441.365-1.442,1.436q0,7.312,0,14.625c0,1.041.386,1.424,1.423,1.424H24.183c1.037,0,1.423-.383,1.424-1.424q0-7.332,0-14.663c0-1.02-.384-1.4-1.4-1.4H13.969M22.1,15.138c0-1.708.047-3.372-.009-5.033a8.13,8.13,0,0,0-16.253.427c-.014,1.406,0,2.812,0,4.218,0,.124.014.248.023.388Z" transform="translate(-0.001 0)" fill="#b7b7b7"/>
                                        <path id="Trazado_3" data-name="Trazado 3" d="M103.951,211.481c0-.348-.02-.7.006-1.044.021-.277-.088-.394-.334-.5a3.478,3.478,0,0,1,.3-6.419,3.493,3.493,0,0,1,4.417,1.938,3.456,3.456,0,0,1-1.826,4.517.364.364,0,0,0-.249.4c.01.735.009,1.47,0,2.2a1.162,1.162,0,1,1-2.319-.013c0-.361,0-.722,0-1.083m1.128-3.518a1.091,1.091,0,0,0,1.192-1.123,1.159,1.159,0,1,0-2.317-.067,1.091,1.091,0,0,0,1.125,1.189" transform="translate(-91.143 -182.331)" fill="#b7b7b7"/>
                                      </g>
                                    </g>
                                  </g>
                                </svg>
                              </span>
                              <InputError class="mt-2" :message="form.errors.password" />
                          </div>
                          <div class="" style="display:flex;">
                              <label class="flex items-center">
                                  <Checkbox style="padding:0;margin-top: 0.5rem;" v-model:checked="form.remember" name="remember" />
                                  <span class="ml-2 text-sm">Recordar</span>
                              </label>
                          </div>
                          <div class="btn" style="margin-top:0rem; display: flex; justify-content: center;margin-right: 0rem;">
                             <button @click="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" style="font-size: 0.8rem;">Iniciar sesión</button>
                          </div>
                       </form>
                     </div>

                  </div>
                </div>
              </div>
              <div class="form-item sign-up">
                <div class="table">
                  <div class="table-cell">
                      <div class="" style="text-align:justify; margin-left:2rem; margin-top: -4rem;">
                      <span class="w-1 h-12 bg-[#EC2944]" style="position:absolute;"></span>
                        <h1 class="ml-2 text-xl">Restablece<br   >
                           <span class="font-extrabold uppercase" style="font-family:'Montserrat';">Tu contraseña.</span>
                        </h1>
                     </div>
                     <div class="ml-2 mr-2">
                       <form >
                        <div class="mt-10">
                            <span class="ml-2" style="position:absolute; margin-top:0rem;">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="38" height="38" viewBox="0 0 38 38">
                                  <defs>
                                    <clipPath id="clip-path">
                                      <rect id="Rectángulo_12" data-name="Rectángulo 12" width="32.182" height="24.523" fill="#b7b7b7" stroke="#b7b7b7" stroke-width="1"/>
                                    </clipPath>
                                    <clipPath id="clip-Icono-email">
                                      <rect width="38" height="38"/>
                                    </clipPath>
                                  </defs>
                                  <g id="Icono-email" clip-path="url(#clip-Icono-email)">
                                    <rect width="38" height="38" fill="rgba(255,255,255,0)"/>
                                    <g id="Grupo_23" data-name="Grupo 23" transform="translate(3 7)">
                                      <g id="Grupo_7" data-name="Grupo 7" clip-path="url(#clip-path)">
                                        <path id="Trazado_8" data-name="Trazado 8" d="M30.127.008h-.014c-.049,0-.1-.006-.147-.006H2.215c-.049,0-.1,0-.147.006H2.055A2.46,2.46,0,0,0,0,2.592V21.93a2.672,2.672,0,0,0,1.215,2.311.539.539,0,0,0,.09.051,1.943,1.943,0,0,0,.911.231H29.967a2.43,2.43,0,0,0,2.215-2.592V2.592A2.46,2.46,0,0,0,30.127.008M2.047,1.33l.045-.007.03,0c.031,0,.062-.005.093-.005H29.967c.031,0,.062,0,.093.005l.03,0,.045.007.036.007L17.61,13.1a2.172,2.172,0,0,1-3.042,0L2.011,1.337l.036-.007M1.406,22.787a1.4,1.4,0,0,1-.283-.856V2.592a1.42,1.42,0,0,1,.045-.362L10.1,10.594Zm1.164.422,8.405-11.8,2.893,2.709a3.167,3.167,0,0,0,4.446,0l2.893-2.709,8.405,11.8ZM31.059,21.93a1.4,1.4,0,0,1-.291.866l-8.687-12.2L31.015,2.23a1.42,1.42,0,0,1,.045.362Z" fill="#b7b7b7" stroke="#b7b7b7" stroke-width="1"/>
                                      </g>
                                    </g>
                                  </g>
                                </svg>
                            </span>
                             <TextInput
                                   id="email"
                                   v-model="formReset.email"
                                   type="email"
                                   class="block w-full mt-1 text-gray-500 opacity-80"
                                   required
                                   autofocus
                                   placeholder="Usuario"
                                   style="border-radius:5rem; font-family: 'Montserrat';padding-left: 3rem; "
                               />
                               <InputError class="" :message="formReset.errors.email" />
                           </div>
                           <div class="btn" style="display:flex; justify-content:center; margin-right:2rem">
                              <button @click="submitReset" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                Enviar
                              </button>
                          </div>
                       </form>
                     </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
   </div>
   <div style="
        margin-top:-5rem; 
        display: flex; 
        justify-content: center; 
        font-family: 'Montserrat'; 
        grid-column: 2/5;
        grid-row: 4/5;">
           <h2 class="text-white text-1xl" style="letter-spacing:1rem;">
             <a href="https://coorsamexico.com/">WWW.COORSAMEXICO.COM</a>
          </h2>
     </div>
 </div>
</template>

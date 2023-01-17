<script setup>
import { Head, useForm } from '@inertiajs/inertia-vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const props = defineProps({
    email: String,
    token: String,
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.reset.first.update'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>

    <Head title="Restablecer Contraseña" />
      <div class="flex justify-center fondo_reset" style="">
        <div class="grid h-auto grid-cols-2 p-12 mt-48 ml-16 mr-16 border md:grid-cols-20" style="margin-bottom:14.8rem; height: 20rem; border: 1rem solid #F4F4F4; border: 3px solid rgba(255,255,255,0.2);  border-radius: 2rem;">
          <div class="flex flex-col items-center justify-center w-full px-6 py-4 mt-6 overflow-hidden shadow-md sm:max-w-md sm:rounded-lg" style="width:45rem;">
             <div class="" style="display:flex; justify-content:center; margin-right:14rem; margin-bottom:5rem;">
                <span class="w-1 h-24 bg-[#EC2944] mt-1 mr-48" style="position:absolute;"></span>
                <h1 class="text-2xl text-white" style="font-family: 'Montserrat';">Bienvenido a <br>
                  <span class="text-3xl font-extrabold uppercase">
                     INTRANET<br>COORSA
                  </span>
                </h1>
             </div>
          </div>
          <div class="" style="width:45rem; margin-top: -8rem; margin-left: 28rem; height: 30rem; z-index: 5; position: absolute;">        
              <div class="w-full h-full px-6 py-4 mt-2 overflow-hidden bg-white shadow-md sm:max-w-md sm:rounded-3xl">
                <div class="mt-4">
                    <h1 class="mb-8 ml-4 text-2xl ">
                      Crea tu<br>
                      <span class="uppercase">NUEVA CONTRASEÑA</span>
                    </h1>
                </div>
                  <form @submit.prevent="submit">
                      <div>
                          <InputLabel for="email" value="Correo" />
                          <TextInput  id="email" v-model="form.email" type="email" placeholder="Correo electronico" class="block w-full mt-1 rounded-full" required autofocus
                              readonly style="padding-left:3rem" />
                          <span class="absolute mt-1 ml-2 top-40">
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
                          <InputError class="mt-2" :message="form.errors.email" />
                      </div>

                      <div class="mt-4">
                          <InputLabel for="password" value="Contraseña" />
                          <TextInput id="password" v-model="form.password" placeholder="Contraseña" type="password" class="block w-full mt-1 rounded-full" required
                              autocomplete="new-password"  style="padding-left:3rem"/>
                              <span class="ml-1" style="position:absolute; margin-top:-2.3rem">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="39" height="39" viewBox="0 0 39 39">
                                    <defs>
                                      <clipPath id="clip-path">
                                        <rect id="Rectángulo_4" data-name="Rectángulo 4" width="38" height="38" viewBox="0 0 38 38" fill="#b7b7b7"/>
                                      </clipPath>
                                      <clipPath id="clip-Icono-candado">
                                          <rect width="38" height="38"/>
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

                      <div class="mt-4">
                          <InputLabel for="password_confirmation" value="Confirmar Contraseña" />
                          <TextInput id="password_confirmation" v-model="form.password_confirmation" placeholder="Confirmar contraseña" type="password"
                              class="block w-full mt-1 rounded-full" required autocomplete="new-password" style="padding-left:3rem" />
                              <span class="ml-1" style="position:absolute; margin-top:-2.3rem">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="39" height="39" viewBox="0 0 39 39">
                                    <defs>
                                      <clipPath id="clip-path">
                                        <rect id="Rectángulo_4" data-name="Rectángulo 4" width="38" height="38" viewBox="0 0 38 38" fill="#b7b7b7"/>
                                      </clipPath>
                                      <clipPath id="clip-Icono-candado">
                                          <rect width="38" height="38"/>
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
                          <InputError class="mt-2" :message="form.errors.password_confirmation" />
                      </div>

                      <div class="flex items-center justify-end mt-4">
                          <button :class="{ 'opacity-25': form.processing }" 
                          style="background-color:#F1687B;
                           color:white; 
                           padding-left: 2rem; 
                           padding-right: 2rem; 
                           padding-top: 0.5rem;
                           padding-bottom: 0.5rem;
                           border-radius: 2rem;" :disabled="form.processing">
                              Enviar
                          </button>
                      </div>
                  </form>
              </div>
          </div>
       </div>
     </div>
</template>

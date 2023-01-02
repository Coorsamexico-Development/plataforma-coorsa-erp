<script setup>
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import ButtonAdd from '@/Components/ButtonAdd.vue';

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.transform(data => ({
        ...data,
        remember: form.remember ? 'on' : '',
    })).post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Log in" />

<div class="login">
    <AuthenticationCard >
        <template #logo>
        </template>

        <div  >
           <div >
              <span style="position:absolute" class="w-16 h-1 mt-12  bg-[#EC2944]"></span>
              <h1 class="mb-10 text-3xl login_title" style="float:left; margin-left: -1rem;font-family: 'Montserrat'; ">Iniciar Sesión</h1>
              
              <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
                  {{ status }}
              </div>
      
              <form @submit.prevent="submit"   >
                  <div class="">
                      <InputLabel for="email"  class="font-semibold text-white" />
                      <TextInput
                          id="email"
                          v-model="form.email"
                          type="email"
                          class="block w-full mt-1 opacity-80 text-white bg-[#70757f]"
                          required
                          autofocus
                          placeholder="USUARIO"
                          style="border-radius:5rem; font-family: 'Montserrat'; "
                      />
                      <InputError class="" :message="form.errors.email" />
                  </div>

                  <PrimaryButton class="" style="background: transparent;
                                                 border-radius: 2rem;
                                                float: right;
                                                /* margin-right: 16rem; */
                                                margin-left: 25rem;
                                                margin-top: 1rem;
                                                z-index: 3;
                                                position: absolute;"
                     :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="64" height="60" viewBox="0 0 64 60">
                      <defs>
                        <clipPath id="clip-FLECHA_CIRCULO">
                          <rect width="64" height="60"/>
                        </clipPath>
                      </defs>
                      <g id="FLECHA_CIRCULO" data-name="FLECHA CIRCULO" clip-path="url(#clip-FLECHA_CIRCULO)">
                        <g id="Grupo_65" data-name="Grupo 65" transform="translate(-842 -339)">
                          <g id="Elipse_14" data-name="Elipse 14" transform="translate(848 343)" fill="none" stroke="#fff" stroke-width="3">
                            <circle cx="26.5" cy="26.5" r="26.5" stroke="none"/>
                            <circle cx="26.5" cy="26.5" r="25" fill="none"/>
                          </g>
                          <g id="Grupo_60" data-name="Grupo 60" transform="translate(-1726.321 -4522.906)">
                            <path id="Trazado_110" data-name="Trazado 110" d="M2636.814,4884.656l7.779,7.779-7.721,7.721" transform="translate(-32.452)" fill="none" stroke="#fff" stroke-miterlimit="10" stroke-width="3" fill-rule="evenodd"/>
                            <line id="Línea_1" data-name="Línea 1" x1="22.642" transform="translate(2589.5 4892.436)" fill="none" stroke="#fff" stroke-miterlimit="10" stroke-width="3"/>
                          </g>
                        </g>
                      </g>
                    </svg>
                  </PrimaryButton>
      
                  <div class="mt-10">
                      <InputLabel for="password" class="font-semibold text-white"  />
                      <TextInput
                          id="password"
                          v-model="form.password"
                          type="password"
                          class="block w-full mt-1 opacity-80 text-white bg-[#70757f]"
                          required
                          autocomplete="current-password"
                          style="border-radius:5rem"
                      />
                      <InputError class="mt-2" :message="form.errors.password" />
                  </div>

      
                  <div class="block mt-4">
                      <label class="flex items-center">
                          <Checkbox v-model:checked="form.remember" name="remember" />
                          <span class="ml-2 text-sm text-white">Recordar</span>
                      </label>
                  </div>
      
                  <div class="flex items-center justify-center mt-4">
                      <Link v-if="canResetPassword" :href="route('password.request')" class="text-sm text-white underline hover:text-gray-900">
                       ¿Olvidaste tu contraseña?
                      </Link>
      
                  </div>
              </form>
           </div>
        </div>
        
    
    </AuthenticationCard>

</div>
    <div style="margin-top:-5rem; display: flex; justify-content: center; font-family: 'Montserrat'; ">
     <h2 class="text-white text-1xl" style="letter-spacing:1rem;">
        <a href="https://coorsamexico.com/">WWW.COORSAMEXICO.COM</a>
    </h2>
   </div>
</template>

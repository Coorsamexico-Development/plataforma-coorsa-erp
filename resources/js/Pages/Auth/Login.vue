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
                                   style="border-radius:5rem; font-family: 'Montserrat'; "
                               />
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
                                  style="border-radius:5rem"
                              />
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
                             <TextInput
                                   id="email"
                                   v-model="formReset.email"
                                   type="email"
                                   class="block w-full mt-1 text-gray-500 opacity-80"
                                   required
                                   autofocus
                                   placeholder="Usuario"
                                   style="border-radius:5rem; font-family: 'Montserrat'; "
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

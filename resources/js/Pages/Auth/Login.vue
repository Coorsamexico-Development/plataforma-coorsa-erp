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
    <AuthenticationCard>
        <template #logo>
        </template>

        <div  >
           <div >
              <h1 class="login_title text-3xl">INICIAR SESIÓN</h1>
              <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
                  {{ status }}
              </div>
      
              <form @submit.prevent="submit"   >
                  <div>
      
                      <InputLabel for="email" value="Email" class="text-white font-semibold" />
                      <TextInput
                          id="email"
                          v-model="form.email"
                          type="email"
                          class="block w-full mt-1 opacity-80 text-white bg-[#70757f]"
                          required
                          autofocus
                      />
                      <InputError class="mt-2" :message="form.errors.email" />
                  </div>
      
                  <div class="mt-4">
                      <InputLabel for="password" value="Password" class="text-white font-semibold"  />
                      <TextInput
                          id="password"
                          v-model="form.password"
                          type="password"
                          class="block w-full mt-1 opacity-80 text-white bg-[#70757f]"
                          required
                          autocomplete="current-password"
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
                      <Link v-if="canResetPassword" :href="route('password.request')" class="text-sm text-gray-600 underline hover:text-gray-900">
                          Forgot your password?
                      </Link>
      
                      <PrimaryButton class="ml-4 justify-center b-g[#09ac5c]" style="background-color:#09ac5c; border-radius: 2rem;" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                          Ingresar
                      </PrimaryButton>
                  </div>
              </form>
           </div>
        </div>
    </AuthenticationCard>
</div>
</template>

<script setup>
import InputLabel from '@/Components/InputLabel.vue';
import Select from '@/Components/Select.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';


const props = defineProps({
    form: {
        type: Object,
        required: true
    },
    bancos: {
        type: Object,
        required: true
    },
});
</script>

<template>
    <div class="border-b tab">
        <div class="relative border-l-2 border-transparent">
            <input class="absolute z-10 w-full h-10 opacity-0 cursor-pointer top-6" type="checkbox" id="datosBancarios"
                checked>
            <header class="flex items-center justify-between p-5 pl-8 pr-8 cursor-pointer select-none tab-label"
                for="datosBancarios">
                <span class="text-xl font-thin text-grey-darkest">
                    Datos Bancarios
                </span>
                <div class="flex items-center justify-center border rounded-full border-grey w-7 h-7 test">
                    <svg aria-hidden="true" class="" data-reactid="266" fill="none" height="24" stroke="#606F7B"
                        stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewbox="0 0 24 24" width="24"
                        xmlns="http://www.w3.org/2000/svg">
                        <polyline points="6 9 12 15 18 9">
                        </polyline>
                    </svg>
                </div>
            </header>
            <div class="tab-content">
                <div class="pb-5 pl-8 pr-8">
                    <div class="grid grid-cols-3 gap-4">
                        <div class="col-span-3">
                            <InputLabel for="banco_id" value="Banco:*" />
                            <Select v-model="props.form.banco_id" class="w-full">
                                <option v-for="banco in props.bancos" :key="banco.id" :value="banco.id">
                                    {{ banco.nombre }}
                                </option>
                            </Select>
                            <InputError :message="props.form.errors.banco_id" class="mt-2" />
                        </div>
                        <div class="mt-4">
                            <InputLabel for="clave_bancaria" value="Clave Interbancaria:*" />
                            <TextInput id="clave_bancaria" type="text" v-model="props.form.clave_bancaria"
                                class="block w-full mt-1" placeholder="Clave Interbancaria" />
                            <InputError :message="props.form.errors.clave_bancaria" class="mt-2" />
                        </div>
                        <div class="mt-4">
                            <InputLabel for="numero_cuenta_bancaria" value="No. Cuenta:*" />
                            <TextInput id="numero_cuenta_bancaria" type="text" v-model="props.form.numero_cuenta_bancaria"
                                class="block w-full mt-1" placeholder="No. Cuenta" />
                            <InputError :message="props.form.errors.numero_cuenta_bancaria" class="mt-2" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

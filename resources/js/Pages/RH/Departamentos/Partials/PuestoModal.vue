<template>
    <modal :show="show" :closeable="closeable" @close="close">
        <div class="m-2">
           <form id="formPuesto"></form>
            <div class="px-6 py-4">
                <div class="p-5 px-6 py-4 border-b-2 border-gray-50 ">
                    <span class="ml-2 text-lg" v-html="titleModal">
                    </span>
                </div>
                <div class="mt-4">
                    <component-label for="name" value="Nombre:" />
                    <component-input id="name" type="text" v-model="form.name"   class="block w-full mt-1"  required />
                        <input-error :message="form.errors.name" class="mt-2" />
                </div>
                <div v-if="typeForm === 'update'" class="block mt-4">
                    <label class="flex items-center">
                        <switch-button id="activo" name="activo" :value="form.activo" v-model:checked="form.activo" />
                        <label for="activo">Activo</label>
                    </label>
                </div>
            </div>

            <div class="px-6 py-4 text-right ">
                <secondary-button @click="close()">Cerrar</secondary-button>
                <primary-button   class="ml-4" @click="createOrUpdate()" :disabled="form.processing"> Guardar
                <!-- <spin-progress v-if="form.processing" :inprogress="form.processing"/>  Guardar -->
                </primary-button>
            </div>
        </div>
    </modal>
</template>
<script>
    import { defineComponent } from 'vue'
    import Modal from '@/Components/Modal.vue'
    import PrimaryButton from '@/Components/PrimaryButton.vue'
    import SecondaryButton from '@/Components/SecondaryButton.vue'
    import ComponentSelect from '@/Components/Select.vue'
    import ComponentInput from '@/Components/TextInput.vue'
    import SwitchButton from '@/Components/SwitchButton.vue'
    import ComponentLabel from '@/Components/InputLabel.vue'
    import InputError from '@/Components/InputError.vue'

    export default defineComponent({
        emits: ['close', 'savedPuesto'],

        components: {
            Modal,
            PrimaryButton,
            ComponentInput,
            ComponentSelect,
            SwitchButton,
            ComponentLabel,
            InputError,
            SecondaryButton
        },
        data(){
            return {
                form: this.$inertia.form({
                   'name': '',
                   'activo': true
                }),
                titleModal: '',
                catalogos: {
                    departamentos: []
                },
            }

        },
        props: {
            show: {
                default: false
            },
            maxWidth: {
                default: 'lg'
            },
            closeable: {
                default: true
            },
            typeForm:{
                required: true,
            },
            puesto: {
                type: Object,
                required: true
            }
        },

        methods: {
            close() {
                this.form.reset();
                this.$emit('close')
            },
            async createOrUpdate(){
                if(document.getElementById('formPuesto').reportValidity()){
                    if(this.typeForm == 'create'){
                      await this.form.post(route('puestos.store'), {
                            preserveScroll: true,
                            onSuccess: () => {
                                this.$emit('savedPuesto');
                                this.close()
                            },
                        });
                    }else{
                        await this.form.put(route('puestos.update', this.puesto.id), {
                            preserveScroll: true,
                            onSuccess: () => {
                                this.$emit('savedPuesto');
                                this.close()
                            }
                        });
                    }
                }
            },

        },

        watch: {
            show: function(){
                if(this.typeForm === 'create'){
                    this.titleModal= '<svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-5 h-5" fill="currentColor" viewBox="0 0 16 16">'
                    +'<path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>'
                    +'<path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>'
                    +'</svg> Nuevo puesto';
                }else{
                    this.titleModal = 'Actualizar Puesto';
                    this.form.name = this.puesto.name;
                    this.form.activo=  this.puesto.activo;
                }

            }
        }

    })
</script>

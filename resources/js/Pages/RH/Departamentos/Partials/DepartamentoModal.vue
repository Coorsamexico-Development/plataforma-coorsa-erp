<template>
    <modal :show="show" :closeable="closeable" @close="close">
        <div class="m-2">
            <div class="px-6 py-2 border-b-2 border-gray-50 ">
                <span class="ml-2 text-lg" v-html="titleModal">
                </span>
            </div>
            <div class="mt-4">
                <input-label for="name" value="Nombre:" />
                <component-input id="nombre" type="text" v-model="form.nombre" class="block w-full mt-1" required />
                <input-error :message="form.errors.nombre" class="mt-2" />
            </div>
            <div class="block mt-4">
                <input-label for="cliente_id" class="flex items-center" value="Clientes:" />
                <select-component id="cliente_id" class="w-full" v-model="form.cliente_id">
                    <option value="" disabled>Select una Opci&oacute;n</option>
                    <option v-for="cliente in clientes" :key="'c' + cliente.id" :value="cliente.id">
                        {{ cliente.nombre }}
                    </option>
                </select-component>
                <input-error :message="form.errors.cliente_id" class="mt-2" />
            </div>
            <div class="block mt-4">
                <input-label for="ubicacione_id" class="flex items-center" value="Ubicacion:" />
                <select-component id="ubicacione_id" class="w-full" v-model="form.ubicacione_id">
                    <option value="" disabled>Select una Opci&oacute;n</option>
                    <option v-for="ubicacion in ubicaciones" :key="'u' + ubicacion.id" :value="ubicacion.id">
                        {{ ubicacion.name }}
                    </option>
                </select-component>
                <input-error :message="form.errors.ubicacione_id" class="mt-2" />
            </div>

            <div class="px-6 py-4 text-right ">
                <secondary-button @click="close()">Cerrar</secondary-button>
                <primary-button class="ml-4" @click="create()" :disabled="form.processing">
                    <spin-progress v-if="form.processing" :inprogress="form.processing" /> Guardar
                </primary-button>
            </div>
        </div>
    </modal>
</template>

<script>
import { defineComponent } from 'vue'
import Modal from '@/Components/Modal.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import SpinProgress from '@/Components/SpinProgress.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'
import ComponentInput from '@/Components/TextInput.vue'
import SwitchButton from '@/Components/SwitchButton.vue'
import InputLabel from '@/Components/InputLabel.vue'
import InputError from '@/Components/InputError.vue'
import SelectComponent from '@/Components/SelectComponent.vue'

export default defineComponent({
    emits: ['close'],

    components: {
        Modal,
        PrimaryButton,
        ComponentInput,
        SwitchButton,
        InputLabel,
        InputError,
        SelectComponent,
        SecondaryButton,
        SpinProgress,
    },
    data() {
        return {
            form: this.$inertia.form({
                'id': -1,
                'nombre': this.departamento.nombre,
                'activo': this.departamento.activo,
                'ubicacione_id': this.departamento.ubicacione_id,
                'cliente_id': this.departamento.cliente_id,
            }),
            titleModal: ''
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
        typeForm: {
            required: true,
            default: 'create'
        },
        departamento: {
            type: Object,
            required: true
        },
        ubicaciones: {
            type: Array,
            required: true
        },
        clientes: {
            type: Array,
            required: true
        }
    },

    methods: {
        close() {
            this.$emit('close')
        },
        create() {
            if (this.typeForm == 'create') {
                this.form.post(route('departamentos.store'), {
                    preserveScroll: true,
                    only: ['departamentos', 'errors'],
                    onSuccess: () => {
                        this.close()
                    },
                });
            } else {
                this.form.put(route('departamentos.update', this.departamento.id), {
                    preserveScroll: true,
                    only: ['departamentos', 'errors'],
                    onSuccess: () => {
                        this.close()
                    }
                });
            }
        }
    },

    watch: {
        show: function (show) {
            if (this.typeForm === 'create') {
                this.titleModal = '<svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-5 h-5" fill="currentColor" viewBox="0 0 16 16">'
                    + '<path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>'
                    + '<path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>'
                    + '</svg> Nuevo Departamento';
            } else {
                this.titleModal = 'Actualizar departamento';
                this.form.id = this.departamento.id;
                this.form.nombre = this.departamento.nombre;
                this.form.activo = this.departamento.activo;
                this.form.ubicacione_id = this.departamento.ubicacione_id;
                this.form.cliente_id = this.departamento.cliente_id;
            }

        }
    }

})
</script>

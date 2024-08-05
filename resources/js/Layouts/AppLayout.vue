<script setup>
import { ref, computed, watchEffect, onMounted, watch } from "vue";
import { Inertia } from "@inertiajs/inertia";
import { Head, Link, usePage } from "@inertiajs/inertia-vue3";
import Banner from "@/Components/Banner.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import NavLink from "@/Components/NavLink.vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import PaginationNominas from "../Components/PaginationNominas.vue";
import axios from "axios";

defineProps({
    title: String,
    nominas: { type: Object, default: null },
});

const showingNavigationDropdown = ref(false);

const months = [
    "Ene",
    "Feb",
    "Mar",
    "Abr",
    "May",
    "Jun",
    "Jul",
    "Ago",
    "Sep",
    "Oct",
    "Nov",
    "Dec",
];
const switchToTeam = (team) => {
    Inertia.put(
        route("current-team.update"),
        {
            team_id: team.id,
        },
        {
            preserveState: false,
        }
    );
};

const logout = () => {
    Inertia.post(route("logout"));
};

let show = ref(false);

const cambio = () => {
    //console.log("hola")
    show.value = !show.value;
    //console.log(show.value)
};

let show2 = ref(false);

const cambio2 = () => {
    //console.log("hola")
    show2.value = !show2.value;
    //console.log(show.value)
};
</script>

<template>
    <div>
        <Head :title="title" />
        <Banner />

        <div class="min-h-screen">
            <nav class="navMenu">
                <!-- Primary Navigation Menu -->
                <div class="px-4 2xl:ml-16 sm:px-6 lg:px-8">
                    <div
                        class="flex flex-row-reverse items-end justify-between h-16 sm:flex-row"
                    >
                        <!-- Logo -->
                        <div class="items-center h-8 xl:flex shrink-0 xl:mr-60">
                            <Link :href="route('dashboard')">
                                <img
                                    class="w-28"
                                    src="../../img/logo_coorsa.png"
                                />
                            </Link>
                        </div>
                        <div class="flex">
                            <!-- Navigation Links -->
                            <div
                                class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex"
                            >
                                <NavLink
                                    class="navLinks"
                                    :href="route('dashboard')"
                                    :active="route().current('dashboard')"
                                >
                                    Inicio
                                </NavLink>
                            </div>

                            <div
                                v-if="$page.props.can['roles.manager']"
                                class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex"
                            >
                                <NavLink
                                    class="navLinks"
                                    :href="route('roles.index')"
                                    :active="route().current('roles.index')"
                                >
                                    Roles
                                </NavLink>
                            </div>
                            <div
                                class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex"
                                :class="{
                                    'border-b-2 border-indigo-400 focus:border-indigo-700 transition':
                                        route().current('control-interno*'),
                                }"
                            >
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <button
                                            type="button"
                                            class="inline-flex items-center px-3 py-2 mt-0 mb-2 text-sm font-medium leading-4 text-gray-500 transition bg-white border border-transparent rounded-md navLinks buttonDropDown hover:text-white focus:outline-none"
                                        >
                                            Control Interno
                                            <svg
                                                class="ml-2 -mr-0.5 h-4 w-4"
                                                xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20"
                                                fill="currentColor"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd"
                                                />
                                            </svg>
                                        </button>
                                    </template>
                                    <template #content>
                                        <DropdownLink
                                            :href="route('dashboardAuditoria')"
                                        >
                                            <span class="text-xs">
                                                Dashboard Auditorias
                                            </span>
                                        </DropdownLink>
                                        <DropdownLink
                                            :href="
                                                route(
                                                    'control-interno.politics.index'
                                                )
                                            "
                                        >
                                            <span class="text-xs">
                                                Documentos Generales
                                            </span>
                                        </DropdownLink>
                                        <DropdownLink
                                            v-if="
                                                $page.props.can[
                                                    'direccion-general.show'
                                                ]
                                            "
                                            :href="
                                                route(
                                                    'documentos-internos.socios.index',
                                                    2
                                                )
                                            "
                                        >
                                            <span class="text-xs">
                                                Direccion General
                                            </span>
                                        </DropdownLink>
                                        <DropdownLink
                                            v-if="
                                                $page.props.can[
                                                    'informacion-financiera.show'
                                                ]
                                            "
                                            :href="
                                                route(
                                                    'documentos-internos.socios.index',
                                                    3
                                                )
                                            "
                                        >
                                            <span class="text-xs">
                                                Información Financiera
                                            </span>
                                        </DropdownLink>
                                        <DropdownLink
                                            v-if="
                                                $page.props.can[
                                                    'documentos-nominas.show'
                                                ]
                                            "
                                            :href="
                                                route(
                                                    'documentos-internos.socios.index',
                                                    4
                                                )
                                            "
                                        >
                                            <span class="text-xs">
                                                Documentos Nominas
                                            </span>
                                        </DropdownLink>
                                        <DropdownLink
                                            v-if="
                                                $page.props.can[
                                                    'documentos-diseño.show'
                                                ]
                                            "
                                            :href="
                                                route(
                                                    'documentos-internos.socios.index',
                                                    5
                                                )
                                            "
                                        >
                                            <span class="text-xs">
                                                Imagen Corporativa
                                            </span>
                                        </DropdownLink>
                                        <DropdownLink
                                            v-if="
                                                $page.props.can[
                                                    'documentos-operaciones.show'
                                                ]
                                            "
                                            :href="
                                                route(
                                                    'documentos-internos.socios.index',
                                                    7
                                                )
                                            "
                                        >
                                            <span class="text-xs">
                                                Documentos Operaciones
                                            </span>
                                        </DropdownLink>
                                        <DropdownLink
                                            v-if="
                                                $page.props.can[
                                                    'documentos-gerencia.show'
                                                ]
                                            "
                                            :href="
                                                route(
                                                    'documentos-internos.socios.index',
                                                    6
                                                )
                                            "
                                        >
                                            <span class="text-xs">
                                                Gerencia
                                            </span>
                                        </DropdownLink>
                                        <div class="border-t border-gray-100" />
                                    </template>
                                </Dropdown>
                            </div>
                            <div
                                v-if="$page.props.can['rh.manager']"
                                class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex"
                            >
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <button
                                            type="button"
                                            class="inline-flex items-center px-3 py-2 mt-0 mb-2 text-sm font-medium leading-4 text-gray-500 transition bg-white border border-transparent rounded-md navLinks buttonDropDown hover:text-white focus:outline-none"
                                        >
                                            Recursos Humanos
                                            <svg
                                                class="ml-2 -mr-0.5 h-4 w-4"
                                                xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20"
                                                fill="currentColor"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd"
                                                />
                                            </svg>
                                        </button>
                                    </template>
                                    <template #content>
                                        <DropdownLink
                                            v-if="
                                                $page.props.can[
                                                    'user-activos.show'
                                                ]
                                            "
                                            :href="
                                                route('empleado.indexmanual', {
                                                    activo: 'activo',
                                                })
                                            "
                                        >
                                            Empleados Activos
                                        </DropdownLink>
                                        <DropdownLink
                                            v-if="
                                                $page.props.can[
                                                    'user-inactivos.show'
                                                ]
                                            "
                                            :href="
                                                route('empleado.indexmanual', {
                                                    activo: 'inactivo',
                                                })
                                            "
                                        >
                                            Empleados Inactivos
                                        </DropdownLink>
                                        <DropdownLink
                                            v-if="
                                                $page.props.can[
                                                    'departamentos.show'
                                                ]
                                            "
                                            :href="route('departamentos.index')"
                                        >
                                            DPTOS. y Puestos
                                        </DropdownLink>
                                        <DropdownLink
                                            v-if="
                                                $page.props.can[
                                                    'plantilla-autorizada.show'
                                                ]
                                            "
                                            :href="
                                                route(
                                                    'rh.plantillas-autorizadas.index'
                                                )
                                            "
                                        >
                                            Plantillas Autorizadas
                                        </DropdownLink>
                                        <DropdownLink
                                            v-if="
                                                $page.props.can[
                                                    'organigrama.show'
                                                ]
                                            "
                                            :href="route('organigrama.index')"
                                        >
                                            Organigrama
                                        </DropdownLink>
                                        <DropdownLink
                                            as="a"
                                            href="https://maniobristas.coorsamexico.com/erp"
                                            method="post"
                                            target="_blank"
                                        >
                                            Altas P&G
                                        </DropdownLink>
                                        <DropdownLink
                                            v-if="
                                                $page.props.can[
                                                    'vacaciones.show'
                                                ]
                                            "
                                            :href="route('vacaciones.index')"
                                        >
                                            Calendario Vacaciones
                                        </DropdownLink>
                                        <div class="border-t border-gray-100" />
                                    </template>
                                </Dropdown>
                            </div>
                            <div
                                class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex"
                            >
                                <NavLink
                                    class="navLinks"
                                    :href="route('activo.index')"
                                    :active="route().current('activo.index')"
                                >
                                    Activos
                                </NavLink>
                            </div>
                        </div>
                        <div class="hidden sm:flex sm:items-center sm:ml-6">
                            <div class="relative ml-3">
                                <!-- Teams Dropdown -->
                                <Dropdown
                                    v-if="$page.props.jetstream.hasTeamFeatures"
                                    align="right"
                                    width="60"
                                >
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button
                                                type="button"
                                                class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition bg-white border border-transparent rounded-md hover:bg-gray-50 hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50"
                                            >
                                                {{
                                                    $page.props.user
                                                        .current_team.name
                                                }}

                                                <svg
                                                    class="ml-2 -mr-0.5 h-4 w-4"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <div class="w-60">
                                            <!-- Team Management -->
                                            <template
                                                v-if="
                                                    $page.props.jetstream
                                                        .hasTeamFeatures
                                                "
                                            >
                                                <div
                                                    class="block px-4 py-2 text-xs text-gray-400"
                                                >
                                                    Manage Team
                                                </div>

                                                <!-- Team Settings -->
                                                <DropdownLink
                                                    :href="
                                                        route(
                                                            'teams.show',
                                                            $page.props.user
                                                                .current_team
                                                        )
                                                    "
                                                >
                                                    Team Settings
                                                </DropdownLink>

                                                <DropdownLink
                                                    v-if="
                                                        $page.props.jetstream
                                                            .canCreateTeams
                                                    "
                                                    :href="
                                                        route('teams.create')
                                                    "
                                                >
                                                    Create New Team
                                                </DropdownLink>

                                                <div
                                                    class="border-t border-gray-100"
                                                />

                                                <!-- Team Switcher -->
                                                <div
                                                    class="block px-4 py-2 text-xs text-gray-400"
                                                >
                                                    Switch Teams
                                                </div>

                                                <template
                                                    v-for="team in $page.props
                                                        .user.all_teams"
                                                    :key="team.id"
                                                >
                                                    <form
                                                        @submit.prevent="
                                                            switchToTeam(team)
                                                        "
                                                    >
                                                        <DropdownLink
                                                            as="button"
                                                        >
                                                            <div
                                                                class="flex items-center"
                                                            >
                                                                <svg
                                                                    v-if="
                                                                        team.id ==
                                                                        $page
                                                                            .props
                                                                            .user
                                                                            .current_team_id
                                                                    "
                                                                    class="w-5 h-5 mr-2 text-green-400"
                                                                    fill="none"
                                                                    stroke-linecap="round"
                                                                    stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    stroke="currentColor"
                                                                    viewBox="0 0 24 24"
                                                                >
                                                                    <path
                                                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                                                    />
                                                                </svg>
                                                                <div>
                                                                    {{
                                                                        team.name
                                                                    }}
                                                                </div>
                                                            </div>
                                                        </DropdownLink>
                                                    </form>
                                                </template>
                                            </template>
                                        </div>
                                    </template>
                                </Dropdown>
                            </div>

                            <!-- Settings Dropdown -->
                            <div class="relative ml-3">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <button
                                            v-if="
                                                $page.props.jetstream
                                                    .managesProfilePhotos
                                            "
                                            class="flex text-sm transition border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300"
                                        >
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                xmlns:xlink="http://www.w3.org/1999/xlink"
                                                width="54"
                                                height="52"
                                                viewBox="0 0 54 52"
                                            >
                                                <defs>
                                                    <clipPath id="clip-ICONO">
                                                        <rect
                                                            width="54"
                                                            height="52"
                                                        />
                                                    </clipPath>
                                                </defs>
                                                <g
                                                    id="ICONO"
                                                    clip-path="url(#clip-ICONO)"
                                                >
                                                    <g
                                                        id="Grupo_26"
                                                        data-name="Grupo 26"
                                                        transform="translate(-1147 -9.146)"
                                                    >
                                                        <g
                                                            id="Elipse_1"
                                                            data-name="Elipse 1"
                                                            transform="translate(1152.557 14)"
                                                            fill="none"
                                                            stroke="#fff"
                                                            stroke-width="2"
                                                        >
                                                            <circle
                                                                cx="21.5"
                                                                cy="21.5"
                                                                r="21.5"
                                                                stroke="none"
                                                            />
                                                            <circle
                                                                cx="21.5"
                                                                cy="21.5"
                                                                r="20.5"
                                                                fill="none"
                                                            />
                                                        </g>
                                                        <path
                                                            id="usuario_1_"
                                                            data-name="usuario (1)"
                                                            d="M16.8,13.233a5.654,5.654,0,1,0-6.607,0A10.514,10.514,0,0,0,3,23.192.807.807,0,0,0,3.808,24H23.192A.807.807,0,0,0,24,23.192,10.514,10.514,0,0,0,16.8,13.233ZM9.462,8.654A4.038,4.038,0,1,1,13.5,12.692,4.043,4.043,0,0,1,9.462,8.654ZM4.652,22.385a8.885,8.885,0,0,1,17.7,0Z"
                                                            transform="translate(1161 21)"
                                                            fill="#fff"
                                                        />
                                                    </g>
                                                </g>
                                            </svg>
                                        </button>

                                        <span
                                            v-else
                                            class="inline-flex rounded-md"
                                        >
                                            <button
                                                type="button"
                                                class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition bg-white border border-transparent rounded-md hover:text-gray-700 focus:outline-none"
                                            >
                                                {{ $page.props.user.name }}

                                                <svg
                                                    class="ml-2 -mr-0.5 h-4 w-4"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <div>
                                            <h1
                                                class="uppercase text-[#1D2B4E] font-semibold text-center"
                                            >
                                                Perfil
                                            </h1>
                                            <div
                                                style="
                                                    display: flex;
                                                    justify-content: center;
                                                "
                                            >
                                                <div
                                                    style="height: 100px; width: 100px;/* los siguientes valores son independientes del tamaño del círculo */ background-repeat: no-repea background-position: 50%;  border-radius: 50%; background-size: 100% auto;"
                                                    :style="{
                                                        backgroundImage: `url(${$page.props.user.profile_photo_url})`,
                                                    }"
                                                ></div>
                                            </div>
                                            <p
                                                class="text-center text-[#1D2B4E] font-bold"
                                            >
                                                {{
                                                    $page.props.user.name +
                                                    " " +
                                                    $page.props.user
                                                        .apellido_paterno
                                                }}
                                            </p>
                                            <!-- <p class="text-center text-[#1D2B4E]"> {{ $page.props.puesto.name }}</p> -->
                                        </div>
                                        <!-- Account Management
                                                <DropdownLink :href="route('profile.show')">
                                                    Profile
                                                </DropdownLink>

                                                <DropdownLink v-if="$page.props.jetstream.hasApiFeatures"
                                                    :href="route('api-tokens.index')">
                                                    API Tokens
                                                </DropdownLink>
                                                 -->
                                        <div class="border-t border-gray-100" />

                                        <!-- Authentication -->
                                        <form @submit.prevent="logout">
                                            <DropdownLink as="button">
                                                Salir
                                            </DropdownLink>
                                        </form>
                                        <div
                                            class="grid px-2 text-xs text-center border-t border-gray-100"
                                            v-if="nominas"
                                        >
                                            <span
                                                class="text-sm font-semibold border-b border-gray-400"
                                                >Recibos de Nominas</span
                                            >
                                            <table>
                                                <tr>
                                                    <th>Recibo</th>
                                                    <th>Año</th>
                                                    <th>Mes</th>
                                                    <th>Periodo</th>
                                                </tr>
                                                <tr
                                                    v-for="nomina in nominas.data"
                                                    :key="nomina.id"
                                                >
                                                    <td>
                                                        <a
                                                            :href="
                                                                nomina.nomina_doc
                                                            "
                                                            data-fancybox
                                                            data-type="pdf"
                                                            class="grid px-1 bg-indigo-400 rounded-2xl place-content-center"
                                                        >
                                                            <svg
                                                                xmlns="http://www.w3.org/2000/svg"
                                                                class="icon icon-tabler icon-tabler-eye"
                                                                width="20"
                                                                height="20"
                                                                viewBox="0 0 24 24"
                                                                stroke-width="1.5"
                                                                stroke="#ffffff"
                                                                fill="none"
                                                                stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                            >
                                                                <path
                                                                    stroke="none"
                                                                    d="M0 0h24v24H0z"
                                                                    fill="none"
                                                                />
                                                                <circle
                                                                    cx="12"
                                                                    cy="12"
                                                                    r="2"
                                                                />
                                                                <path
                                                                    d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7"
                                                                />
                                                            </svg>
                                                        </a>
                                                    </td>
                                                    <td>
                                                        {{
                                                            new Date(
                                                                nomina.fecha_doc
                                                            ).getFullYear()
                                                        }}
                                                    </td>
                                                    <td>
                                                        {{
                                                            months[
                                                                new Date(
                                                                    nomina.fecha_doc
                                                                ).getUTCMonth()
                                                            ]
                                                        }}
                                                    </td>
                                                    <td>
                                                        {{ nomina.periodo }}
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </template>
                                    <template #pagination v-if="nominas">
                                        <PaginationNominas
                                            :pagination="nominas"
                                        />
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="flex items-center -mr-2 sm:hidden">
                            <button
                                class="inline-flex items-center justify-center p-2 text-gray-400 transition rounded-md hover:text-white focus:outline-none focus:text-white"
                                @click="
                                    showingNavigationDropdown =
                                        !showingNavigationDropdown
                                "
                            >
                                <svg
                                    class="w-6 h-6"
                                    stroke="currentColor"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        :class="{
                                            hidden: showingNavigationDropdown,
                                            'inline-flex':
                                                !showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{
                                            hidden: !showingNavigationDropdown,
                                            'inline-flex':
                                                showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                <div
                    class="sm:hidden"
                    :class="{
                        block: showingNavigationDropdown,
                        hidden: !showingNavigationDropdown,
                    }"
                >
                    <!-- Responsive Settings Options -->
                    <div class="pt-4 pb-1 border-t border-gray-200">
                        <div class="flex flex-col items-center px-4">
                            <div
                                v-if="
                                    $page.props.jetstream.managesProfilePhotos
                                "
                                class="mr-3 shrink-0"
                            >
                                <img
                                    class="object-cover w-24 h-24 rounded-full"
                                    :src="$page.props.user.profile_photo_url"
                                    :alt="$page.props.user.name"
                                />
                            </div>

                            <div class="flex flex-col items-center mt-4">
                                <div
                                    class="text-base font-medium text-white"
                                    style="font-family: 'Montserrat'"
                                >
                                    {{
                                        $page.props.user.name +
                                        " " +
                                        $page.props.user.apellido_paterno +
                                        " " +
                                        $page.props.user.apellido_materno
                                    }}
                                </div>
                                <div class="text-sm font-medium text-gray-500">
                                    {{ $page.props.user.email }}
                                </div>
                                <div>
                                    <h1
                                        class="mt-2 font-semibold"
                                        style="
                                            font-family: 'Montserrat';
                                            font-size: 1.8rem;
                                            margin-left: -8rem;
                                        "
                                    >
                                        Encuentra lo <br />que buscas.
                                    </h1>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <!--
                                    <ResponsiveNavLink :href="route('profile.show')" :active="route().current('profile.show')">
                                        Profile
                                    </ResponsiveNavLink>
                                 -->
                            <ResponsiveNavLink
                                v-if="$page.props.jetstream.hasApiFeatures"
                                :href="route('api-tokens.index')"
                                :active="route().current('api-tokens.index')"
                            >
                                API Tokens
                            </ResponsiveNavLink>
                            <!-- Team Management -->
                            <template
                                v-if="$page.props.jetstream.hasTeamFeatures"
                            >
                                <div class="border-t border-gray-200" />

                                <div
                                    class="block px-4 py-2 text-xs text-gray-400"
                                >
                                    Manage Team
                                </div>

                                <!-- Team Settings -->
                                <ResponsiveNavLink
                                    :href="
                                        route(
                                            'teams.show',
                                            $page.props.user.current_team
                                        )
                                    "
                                    :active="route().current('teams.show')"
                                >
                                    Team Settings
                                </ResponsiveNavLink>

                                <ResponsiveNavLink
                                    v-if="$page.props.jetstream.canCreateTeams"
                                    :href="route('teams.create')"
                                    :active="route().current('teams.create')"
                                >
                                    Create New Team
                                </ResponsiveNavLink>

                                <div class="border-t border-gray-200" />

                                <!-- Team Switcher -->
                                <div
                                    class="block px-4 py-2 text-xs text-gray-400"
                                >
                                    Switch Teams
                                </div>

                                <template
                                    v-for="team in $page.props.user.all_teams"
                                    :key="team.id"
                                >
                                    <form @submit.prevent="switchToTeam(team)">
                                        <ResponsiveNavLink as="button">
                                            <div class="flex items-center">
                                                <svg
                                                    v-if="
                                                        team.id ==
                                                        $page.props.user
                                                            .current_team_id
                                                    "
                                                    class="w-5 h-5 mr-2 text-green-400"
                                                    fill="none"
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                                    />
                                                </svg>
                                                <div>{{ team.name }}</div>
                                            </div>
                                        </ResponsiveNavLink>
                                    </form>
                                </template>
                            </template>
                        </div>
                    </div>
                </div>
                <!-- Responsive Navigation Menu -->
                <div
                    :class="{
                        block: showingNavigationDropdown,
                        hidden: !showingNavigationDropdown,
                    }"
                    class="sm:hidden sm:bg-white"
                >
                    <div class="pt-2 pb-3 space-y-1">
                        <ResponsiveNavLink
                            class="sm:text-white"
                            :href="route('dashboard')"
                            :active="route().current('dashboard')"
                        >
                            Inicio
                        </ResponsiveNavLink>
                    </div>
                    <div
                        v-if="$page.props.can['roles.manager']"
                        class="pt-2 pb-3 space-y-1"
                    >
                        <ResponsiveNavLink
                            :href="route('roles.index')"
                            :active="route().current('roles.index')"
                        >
                            Roles
                        </ResponsiveNavLink>
                    </div>
                    <div class="pt-2 pb-3 space-y-1">
                        <button @click="cambio" class="ml-4">
                            Control Interno
                        </button>
                        <div class="ml-8" v-if="show">
                            <ResponsiveNavLink
                                :href="route('control-interno.politics.index')"
                                :active="
                                    route().current(
                                        'control-interno.politics.index'
                                    )
                                "
                            >
                                Documentos Generales
                            </ResponsiveNavLink>
                            <ResponsiveNavLink
                                v-if="
                                    $page.props.can['documentos-internos.show']
                                "
                                :href="
                                    route(
                                        'control-interno.documentos-internos.index'
                                    )
                                "
                                :active="
                                    route().current(
                                        'control-interno.documentos-internos.index'
                                    )
                                "
                            >
                                Documentos Internos
                            </ResponsiveNavLink>
                            <ResponsiveNavLink
                                :href="
                                    route(
                                        'control-interno.departamentos-aditorias.index'
                                    )
                                "
                                :active="
                                    route().current(
                                        'control-interno.departamentos-aditorias.index'
                                    )
                                "
                            >
                                Auditorias
                            </ResponsiveNavLink>
                        </div>
                    </div>
                    <div
                        v-if="$page.props.can['rh.manager']"
                        class="pt-2 pb-3 space-y-1"
                    >
                        <button @click="cambio2" class="ml-4">
                            Recursos Humanos
                        </button>
                        <div class="ml-8" v-if="show2">
                            <ResponsiveNavLink
                                v-if="$page.props.can['user-activos.show']"
                                :href="
                                    route('empleado.indexmanual', {
                                        activo: 'activo',
                                    })
                                "
                                :active="
                                    route().current('empleado.indexmanual')
                                "
                            >
                                Empleados Activos
                            </ResponsiveNavLink>
                            <ResponsiveNavLink
                                v-if="$page.props.can['user-inactivos.show']"
                                :href="
                                    route('empleado.indexmanual', {
                                        activo: 'inactivo',
                                    })
                                "
                                :active="
                                    route().current('empleado.indexmanual')
                                "
                            >
                                Empleados Inactivos
                            </ResponsiveNavLink>
                            <ResponsiveNavLink
                                v-if="
                                    $page.props.can['plantilla-autorizada.show']
                                "
                                :href="route('rh.plantillas-autorizadas.index')"
                                :active="
                                    route().current(
                                        'rh.plantillas-autorizadas.index'
                                    )
                                "
                            >
                                Plantillas Autorizadas
                            </ResponsiveNavLink>
                            <ResponsiveNavLink
                                v-if="$page.props.can['departamentos.show']"
                                :href="route('departamentos.index')"
                                :active="route().current('departamentos.index')"
                            >
                                DPTOS. y Puestos
                            </ResponsiveNavLink>
                        </div>
                    </div>
                    <div class="flex justify-center mt-2 mb-2">
                        <h2
                            style="
                                font-family: 'Montserrat';
                                letter-spacing: 0.5rem;
                                font-size: 0.6rem;
                            "
                            class="mt-4 text-white"
                        >
                            <a href="https://coorsamexico.com/"
                                >WWW.COORSAMEXICO.COM</a
                            >
                        </h2>
                    </div>
                    <div class="pt-4 pb-1 border-t border-gray-200">
                        <!-- Authentication -->
                        <form method="POST" @submit.prevent="logout">
                            <ResponsiveNavLink as="button">
                                Salir
                            </ResponsiveNavLink>
                        </form>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header
                v-if="$slots.header"
                class="bg-white shadow"
                style="
                    margin-top: 1rem;
                    margin-right: 2rem;
                    margin-left: 2rem;
                    border-radius: 1rem;
                "
            >
                <div class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>

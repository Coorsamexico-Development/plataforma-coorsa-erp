<script setup>

var props = defineProps(
    {
        activos:Object,
        campos:Object
    }
);

</script>
<template>
     <table class="table-auto w-full md:table-fixed">
        <thead class="drop-shadow-xl border-b-2 border-gray-300 text-gray-400 font-extralight" style="font-family: 'Montserrat';">
            <tr>
                <th>Acciones</th>
                <th>Fecha de alta</th>
                <th>Status</th>
                <th v-for="campo in campos" :key="campo.id">{{ campo.campo }}</th>
                <th>Documento</th>
                <th>Usuarios</th>
            </tr>
        </thead>
        <tbody>
            <tr class="text-center" v-for="activo in activos" :key="activo.id">
               <td class="">
                  <div class="flex flex-row justify-center">
                    <button class="bg-[#ec677e] m-2 rounded-2xl pl-2 pr-2">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="32" height="35" viewBox="0 0 32 35">
                          <defs>
                            <clipPath id="clip-Icono-eliminar">
                              <rect width="32" height="35"/>
                            </clipPath>
                          </defs>
                          <g id="Icono-eliminar" clip-path="url(#clip-Icono-eliminar)">
                            <rect width="32" height="35" fill="rgba(255,255,255,0)"/>
                            <g id="eliminar" transform="translate(7.003 6.001)">
                              <path id="Trazado_318" data-name="Trazado 318" d="M222.939,154.7a.541.541,0,0,0-.541.541v10.221a.541.541,0,0,0,1.082,0V155.244A.541.541,0,0,0,222.939,154.7Zm0,0" transform="translate(-210.374 -146.338)" fill="#fff"/>
                              <path id="Trazado_319" data-name="Trazado 319" d="M104.939,154.7a.541.541,0,0,0-.541.541v10.221a.541.541,0,0,0,1.082,0V155.244A.541.541,0,0,0,104.939,154.7Zm0,0" transform="translate(-98.756 -146.338)" fill="#fff"/>
                              <path id="Trazado_320" data-name="Trazado 320" d="M1.533,6.873V20.2a2.984,2.984,0,0,0,.793,2.058,2.663,2.663,0,0,0,1.932.835H14.49a2.663,2.663,0,0,0,1.932-.835,2.984,2.984,0,0,0,.793-2.058V6.873a2.066,2.066,0,0,0-.53-4.063H13.917V2.135A2.124,2.124,0,0,0,11.775,0h-4.8A2.124,2.124,0,0,0,4.832,2.135v.676H2.063a2.066,2.066,0,0,0-.53,4.063ZM14.49,22.009H4.258A1.713,1.713,0,0,1,2.614,20.2V6.921h13.52V20.2A1.713,1.713,0,0,1,14.49,22.009ZM5.913,2.135A1.042,1.042,0,0,1,6.973,1.08h4.8a1.042,1.042,0,0,1,1.06,1.055v.676H5.913ZM2.063,3.893H16.686a.973.973,0,1,1,0,1.947H2.063a.973.973,0,1,1,0-1.947Zm0,0" transform="translate(0 0)" fill="#fff"/>
                              <path id="Trazado_321" data-name="Trazado 321" d="M163.939,154.7a.541.541,0,0,0-.541.541v10.221a.541.541,0,0,0,1.082,0V155.244A.541.541,0,0,0,163.939,154.7Zm0,0" transform="translate(-154.565 -146.338)" fill="#fff"/>
                            </g>
                          </g>
                        </svg>
                    </button>
                    <button class="bg-[#f28c00] m-2 rounded-2xl pl-2 pr-2">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="35" height="33" viewBox="0 0 35 33">
                          <defs>
                            <clipPath id="clip-Icono-editar">
                              <rect width="35" height="33"/>
                            </clipPath>
                          </defs>
                          <g id="Icono-editar" clip-path="url(#clip-Icono-editar)">
                            <rect width="35" height="33" fill="rgba(255,255,255,0)"/>
                            <g id="Grupo_513" data-name="Grupo 513" transform="translate(-7736.179 -2823)">
                              <g id="editar" transform="translate(7743.179 2828.996)">
                                <g id="Grupo_459" data-name="Grupo 459" transform="translate(0 1.55)">
                                  <g id="Grupo_458" data-name="Grupo 458">
                                    <path id="Trazado_286" data-name="Trazado 286" d="M17.778,43.412a.773.773,0,0,0-.773.773v7.729a.773.773,0,0,1-.773.773H2.319a.773.773,0,0,1-.773-.773V36.456a.773.773,0,0,1,.773-.773h9.275a.773.773,0,1,0,0-1.546H2.319A2.319,2.319,0,0,0,0,36.456V51.915a2.319,2.319,0,0,0,2.319,2.319H16.232a2.319,2.319,0,0,0,2.319-2.319V44.185A.773.773,0,0,0,17.778,43.412Z" transform="translate(0 -34.137)" fill="#fff"/>
                                  </g>
                                </g>
                                <g id="Grupo_461" data-name="Grupo 461" transform="translate(4.638 0.004)">
                                  <g id="Grupo_460" data-name="Grupo 460">
                                    <path id="Trazado_287" data-name="Trazado 287" d="M118.547.87a2.959,2.959,0,0,0-4.185,0l-10.181,10.18a.781.781,0,0,0-.186.3l-1.546,4.638a.773.773,0,0,0,.733,1.017.785.785,0,0,0,.244-.039l4.638-1.546a.773.773,0,0,0,.3-.187L118.547,5.055A2.959,2.959,0,0,0,118.547.87Zm-1.093,3.092L107.405,14.011l-3,1,1-3L115.455,1.967a1.412,1.412,0,1,1,2,2Z" transform="translate(-102.409 -0.004)" fill="#fff"/>
                                  </g>
                                </g>
                              </g>
                            </g>
                          </g>
                        </svg>
                    </button>
                  </div>
               </td>
               <td>
                 
               </td>
            </tr>
        </tbody>
    </table>
</template>
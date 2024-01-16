<script setup>
import { ref, computed, reactive, watch, onMounted } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import ButtonAdd from "../../../Components/ButtonAdd.vue";
import Dropdown from "@/Components/Dropdown.vue";
import SwitchButton from "../Partials/SwitchButton.vue";
import AddProcesoModal from "../Modals/AddProcesoModal.vue";
import ShowDocumentoModal from "../Modals/ShowDocumentoModal.vue";
import ShowCalificacionesModal from "../Modals/ShowCalificacionesModal.vue";
import Graph2 from "../Partials/Graph2.vue";
import GraphRub from "./GraphRub.vue";
import GraphPastel from "./Partials/GraphPastel.vue";
import GraphRubMonth from "./GraphRubMonth.vue";
import BotonProc from "./Partials/BotonProc.vue";
import "@fancyapps/ui/dist/fancybox.css";
import moment from "moment";
import { Inertia } from "@inertiajs/inertia";
import TablaSUA from "./Partials/TablaSUA.vue";
import "moment/locale/es";

const now = new Date().getMonth();
let props = defineProps({
    departamentosAuditoria: {
        type: Object,
        required: true,
    },
    filters: {
        type: Object,
        required: true,
    },
    procesos: Object,
    usuarios: Object,
    calificaciones_mes: Object,
    documentos_mes: {
        type: Object,
        required: true,
    },
    rubros_mes: Object,
    nominas: Object,
    rubTot: Object,
});
const params = reactive({
    departamento_auditoria_id: props.filters.departamento_auditoria_id,
});

const departamento = computed(() => {
    return props.departamentosAuditoria.find(
        (dep) => dep.id == params.departamento_auditoria_id
    );
});

const proc = ref(props.procesos[0]);

watch(params, (newParams) => {
    //console.log(newParams);
    Inertia.visit(route("control-interno.departamentos-aditorias.index"), {
        data: newParams,
        replace: true,
        only: [
            "documentos_mes",
            "procesos",
            "calificaciones_mes",
            "rubros_mes",
            "rubTot",
        ],
        preserveScroll: true,
        preserveState: true,
    });
});

const cambio = ref(false);
const cambioSwitch = () => {
    //console.log("gola");
    cambio.value = !cambio.value;
};

const modalAddProceso = ref(false);
const openModalAddProceso = () => {
    modalAddProceso.value = true;
};

const closeModalAddProceso = () => {
    modalAddProceso.value = false;
};

const modalAddDocumento = ref(false);
const procesoReactive = ref(-1);
const openModalAddDoc = (proceso_id) => {
    procesoReactive.value = proceso_id;
    consultarDocumentos(proceso_id);
    modalAddDocumento.value = true;
};
const closeModalAddDoc = () => {
    modalAddDocumento.value = false;
};

const documentos = ref([]);
const consultarDocumentos = (proceso_id) => {
    //console.log(proceso_id);
    axios.get("/getDocumentos/" + proceso_id).then((response) => {
        //console.log(response.data);
        documentos.value = response.data;
    });
};

const modalAdCalf = ref(false);
const openModalShowCalif = (proceso_id, year) => {
    consultarRubros(proceso_id, year);
    procesoReactive.value = proceso_id;
    modalAdCalf.value = true;
};

const closeModalShowCalif = () => {
    calificaciones.value = [];
    modalAdCalf.value = false;
};

const rubros = ref([]);
const calificaciones = ref([]);
const meses = [
    {
        numero: "12",
        mes: "Dic",
    },
    {
        numero: "11",
        mes: "Nov",
    },
    {
        numero: "10",
        mes: "Oct",
    },
    {
        numero: "9",
        mes: "Sep",
    },
    {
        numero: "8",
        mes: "Ago",
    },
    {
        numero: "7",
        mes: "Jul",
    },
    {
        numero: "6",
        mes: "Jun",
    },
    {
        numero: "5",
        mes: "May",
    },
    {
        numero: "4",
        mes: "Abr",
    },
    {
        numero: "3",
        mes: "Mar",
    },
    {
        numero: "2",
        mes: "Feb",
    },
    {
        numero: "1",
        mes: "Ene",
    },
];

let meses2 = [
    {
        numero: "1",
        mes: "Ene",
    },
    {
        numero: "2",
        mes: "Feb",
    },
    {
        numero: "3",
        mes: "Mar",
    },
    {
        numero: "4",
        mes: "Abr",
    },
    {
        numero: "5",
        mes: "May",
    },
    {
        numero: "6",
        mes: "Jun",
    },
    {
        numero: "7",
        mes: "Jul",
    },
    {
        numero: "8",
        mes: "Ago",
    },
    {
        numero: "9",
        mes: "Sep",
    },
    {
        numero: "10",
        mes: "Oct",
    },
    {
        numero: "11",
        mes: "Nov",
    },
    {
        numero: "12",
        mes: "Dic",
    },
];

const consultarRubros = (proceso_id, year) => {
    rubros.value = [];
    calificaciones.value = [];
    axios.get("/getRubros/" + proceso_id + "/" + year).then((response) => {
        // console.log(response.data);
        rubros.value = response.data;

        for (let index = 0; index < rubros.value.length; index++) {
            const rubro = rubros.value[index];
            // console.log(rubro);
            for (let index2 = 0; index2 < meses.length; index2++) {
                const mes = meses[index2];
                let newInterseccion = {};
                //console.log(mes);
                newInterseccion.mes = mes.numero;
                newInterseccion.rubro = rubro.id;
                newInterseccion.valor = null;

                calificaciones.value.push(newInterseccion);
            }
        }

        for (let index3 = 0; index3 < calificaciones.value.length; index3++) {
            const interseccion = calificaciones.value[index3];
            //console.log(interseccion);
            for (let index4 = 0; index4 < rubros.value.length; index4++) {
                const rubro = rubros.value[index4];
                for (
                    let index5 = 0;
                    index5 < rubro.calificaciones.length;
                    index5++
                ) {
                    const calificaion = rubro.calificaciones[index5];
                    //console.log(calificaion);
                    if (
                        calificaion.mes == interseccion.mes &&
                        calificaion.rubro_id == interseccion.rubro
                    ) {
                        interseccion.valor = calificaion.valor;
                    }
                }
            }
        }
    });
};

const arregloCalificaciones = computed(() => {
    /***/
    let arregloAñoMes = [];
    let fechaTemp = "";

    // console.log(props.calificaciones_mes)
    for (let index = 0; index < props.calificaciones_mes.length; index++) {
        const element = props.calificaciones_mes[index];
        let fechaActual = element.año + "-" + element.mes;
        //console.log(element.año+'-'+element.mes);
        if (element == props.calificaciones_mes[0]) {
            arregloAñoMes.push(fechaActual);
            fechaTemp = fechaActual;
        } else {
            if (fechaTemp !== fechaActual) {
                arregloAñoMes.push(fechaActual);
                fechaTemp = fechaActual;
            }
        }
    }

    let arregloFechasTotales = [];
    const first = arregloAñoMes[0];
    const fechaInicio = moment(first);
    const last = _.last(arregloAñoMes);
    const fechaFinal = moment(last);

    while (fechaInicio.isSameOrBefore(fechaFinal)) {
        let Obj = {
            date: fechaInicio.format("YYYY-MM"),
            promedio: 0,
        };
        arregloFechasTotales.push(Obj);
        fechaInicio.add(1, "months");
    }

    for (let index2 = 0; index2 < arregloFechasTotales.length; index2++) {
        let conteo = [];
        let suma = 0;
        let promedio = 0;
        const fecha = arregloFechasTotales[index2];
        for (
            let index3 = 0;
            index3 < props.calificaciones_mes.length;
            index3++
        ) {
            const calificacion = props.calificaciones_mes[index3];
            //console.log(calificacion);
            let fechaActual = calificacion.año + "-" + calificacion.mes;
            let fechaActual2 = moment(fechaActual);
            let fechaActual3 = fechaActual2.format("YYYY-MM");

            if (fechaActual3 === fecha.date) {
                if (
                    proc.value.id ===
                    props.calificaciones_mes[index3].proceso_id
                ) {
                    suma += calificacion.valor;
                    conteo.push(calificacion);
                }
            } else {
                fecha.promedio = 0;
            }
        }
        promedio = suma / conteo.length;
        fecha.promedio = promedio.toFixed(2);
        //console.log(promedio)
    }

    let hoy = new Date().getFullYear() + "-";
    let mes = new Date().getMonth() + 1;
    if (mes < 10) {
        mes = "0" + mes;
    }

    hoy = hoy + mes;
    //console.log(hoy)

    let arregloFiltrado = arregloFechasTotales.filter(
        (promedio) => promedio.date < hoy
    );
    //console.log(arregloFiltrado)

    arregloFiltrado.reverse();
    return arregloFiltrado.slice(0, 3);
});

const arregloCalificacionesGrafica = computed(() => {
    /***/
    let arregloAñoMes = [];
    let fechaTemp = "";

    // console.log(props.calificaciones_mes)
    for (let index = 0; index < props.calificaciones_mes.length; index++) {
        const element = props.calificaciones_mes[index];
        let fechaActual = element.año + "-" + element.mes;
        //console.log(element.año+'-'+element.mes);
        if (element == props.calificaciones_mes[0]) {
            arregloAñoMes.push(fechaActual);
            fechaTemp = fechaActual;
        } else {
            if (fechaTemp !== fechaActual) {
                arregloAñoMes.push(fechaActual);
                fechaTemp = fechaActual;
            }
        }
    }

    let arregloFechasTotales = [];
    const first = arregloAñoMes[0];
    const fechaInicio = moment(first);
    const last = _.last(arregloAñoMes);
    const fechaFinal = moment(last);

    while (fechaInicio.isSameOrBefore(fechaFinal)) {
        let Obj = {
            date: fechaInicio.format("YYYY-MM"),
            promedio: 0,
        };
        arregloFechasTotales.push(Obj);
        fechaInicio.add(1, "months");
    }

    for (let index2 = 0; index2 < arregloFechasTotales.length; index2++) {
        let conteo = [];
        let suma = 0;
        let promedio = 0;
        const fecha = arregloFechasTotales[index2];
        for (
            let index3 = 0;
            index3 < props.calificaciones_mes.length;
            index3++
        ) {
            const calificacion = props.calificaciones_mes[index3];
            //console.log(calificacion);
            let fechaActual = calificacion.año + "-" + calificacion.mes;
            let fechaActual2 = moment(fechaActual);
            let fechaActual3 = fechaActual2.format("YYYY-MM");

            if (fechaActual3 === fecha.date) {
                suma += calificacion.valor;
                conteo.push(calificacion);
            } else {
                fecha.promedio = 0;
            }
        }
        promedio = suma / conteo.length;
        fecha.promedio = promedio.toFixed(2);
        //console.log(promedio)
    }

    return arregloFechasTotales;
});

const arregloParametros = computed(() => {
    /***/
    let arregloAñoMes = [];
    let fechaTemp = "";

    // console.log(props.calificaciones_mes)
    for (let index = 0; index < props.calificaciones_mes.length; index++) {
        const element = props.calificaciones_mes[index];

        let fechaActual = element.año + "-" + element.mes;
        //console.log(element.año+'-'+element.mes);
        if (element == props.calificaciones_mes[0]) {
            arregloAñoMes.push(fechaActual);
            fechaTemp = fechaActual;
        } else {
            if (fechaTemp !== fechaActual) {
                arregloAñoMes.push(fechaActual);
                fechaTemp = fechaActual;
            }
        }
    }

    let arregloFechasTotales = [];
    const first = arregloAñoMes[0];
    const fechaInicio = moment(first);
    const last = _.last(arregloAñoMes);
    const fechaFinal = moment(last);

    while (fechaInicio.isSameOrBefore(fechaFinal)) {
        let Obj = {
            date: fechaInicio.format("YYYY-MM"),
        };

        for (let index = 0; index < props.procesos.length; index++) {
            const proceso = props.procesos[index];
            Obj[`${proceso.nombre}`] = 0;
            // console.log(proceso)
            for (
                let index2 = 0;
                index2 < props.calificaciones_mes.length;
                index2++
            ) {
                const calificacion = props.calificaciones_mes[index2];
                if (calificacion.proceso_name == proceso.nombre) {
                    let fechaActual = calificacion.año + "-" + calificacion.mes;
                    let fechaActual2 = moment(fechaActual);
                    let fechaActual3 = fechaActual2.format("YYYY-MM");
                    //console.log(fechaActual3);
                    if (fechaActual3 == Obj.date) {
                        Obj[`${proceso.nombre}`] = calificacion.valor;
                    }
                }
            }
        }

        arregloFechasTotales.push(Obj);
        fechaInicio.add(1, "months");
    }

    /*
    const año = fecha.getFullYear();
    for (let index = 0; index < meses2.length; index++)
    {
        let mes = meses2[index];
        let newObj = {
            numero_mes:mes.numero,
            mes:mes.mes,
        };
        for (let index2 = 0; index2 < props.procesos.length; index2++)
        {
            let proceso = props.procesos[index2];
            newObj[`${proceso.nombre}`] = 0;
            for (let index3 = 0; index3 < props.calificaciones_mes.length; index3++)
             {
                const calificacion = props.calificaciones_mes[index3];
                //console.log(calificacion);
                if(calificacion.proceso_name == proceso.nombre && mes.numero ==calificacion.mes)
                {
                    if(calificacion.año == año)
                    {
                        newObj[`${proceso.nombre}`] = calificacion.valor;
                        newObj.rubro = calificacion.rubro_name
                    }
                }

            }
        }

        arregloMesesAux.push(newObj);
    }
    */
    return arregloFechasTotales;
});

let fecha = new Date();
let year = ref(null);
year.value = fecha.getFullYear();

let mes = ref(fecha.getMonth() + 1);

/*
if (fecha.getMonth.length < 1) {
    mes.value = "0" + fecha.getMonth();
} else {
    mes.value = fecha.getMonth();
}
*/

const promedios = computed(() => {
    let arregloAux = [];
    for (let index = 0; index < meses.length; index++) {
        const fecha = new Date();
        const año = fecha.getFullYear();
        let fullFecha = "";
        const mes = meses[index];

        if (mes.numero < 10) {
            fullFecha = año + "-0" + mes.numero;
            //console.log(fullFecha)
        } else {
            fullFecha = año + "-" + mes.numero;
        }

        let newObjCalf = {
            numero: mes.numero,
            mes: mes.mes,
            año: año,
            promedio: 0,
            fecha: fullFecha,
        };
        arregloAux.push(newObjCalf);
    }

    for (let index2 = 0; index2 < arregloAux.length; index2++) {
        const objeto = arregloAux[index2];
        //console.log(objeto);
        let conteo = [];
        let suma = 0;
        let promedio = 0;
        for (
            let index3 = 0;
            index3 < props.calificaciones_mes.length;
            index3++
        ) {
            const calificacion = props.calificaciones_mes[index3];
            if (calificacion.mes == objeto.numero) {
                objeto.promedio += calificacion.valor;
                suma += calificacion.valor;
                conteo.push(calificacion);
            } else {
            }
        }
        //console.log(suma/conteo.length)
        promedio = suma / conteo.length;
        //console.log(promedio)
        objeto.promedio = promedio;
    }

    return arregloAux;
});

//Rubros
const rubrosAct = ref([]);
const rubrosCalculados = computed(() => {
    let arregloMesesAñoAux = [];
    let arregloAux = [];
    for (let index = 0; index < meses.length; index++) {
        const fecha = new Date();
        const año = fecha.getFullYear();
        let fullFecha = "";
        const mes = meses[index];

        if (mes.numero < 10) {
            fullFecha = año + "-0" + mes.numero;
            //console.log(fullFecha)
        } else {
            fullFecha = año + "-" + mes.numero;
        }

        let newObjCalf = {
            numero: mes.numero,
            mes: mes.mes,
            año: año,
            promedio: 0,
            fecha: fullFecha,
        };
        arregloAux.push(newObjCalf);
    }

    for (let index2 = 0; index2 < arregloAux.length; index2++) {
        const objeto = arregloAux[index2];
        //console.log(objeto);
        let conteo = [];
        let suma = 0;
        let promedio = 0;
        for (
            let index3 = 0;
            index3 < props.calificaciones_mes.length;
            index3++
        ) {
            const calificacion = props.calificaciones_mes[index3];
            if (calificacion.mes == objeto.numero) {
                objeto.promedio += calificacion.valor;
                suma += calificacion.valor;
                conteo.push(calificacion);
            } else {
            }
        }
        //console.log(suma/conteo.length)
        promedio = suma / conteo.length;
        //console.log(promedio)
        objeto.promedio = promedio;
    }

    for (let index = 0; index < arregloAux.length; index++) {
        const element = arregloAux[index];
        //console.log(element)

        arregloMesesAñoAux.push(element);
    }

    let hoy = new Date().getFullYear() + "-";
    let mes = new Date().getMonth() + 1;
    if (mes < 10) {
        mes = "0" + mes;
    }
    hoy = hoy + mes;

    let anotherArray = [];
    for (let index = 0; index < arregloMesesAñoAux.length; index++) {
        const añoMes = arregloMesesAñoAux[index];
        if (añoMes.promedio > 0) {
            //solo los que tengan promedio mayo a 0
            anotherArray.push(añoMes);
        }
    }

    let arregloFiltrado = anotherArray.filter(
        (promedio) => promedio.fecha < hoy
    );

    if (arregloFiltrado.length > 0) {
        let añoConsulta = arregloFiltrado[0].año;
        let mesConsulta = arregloFiltrado[0].numero;
        //tenemos el ultimo evaludado
        //console.log(params.departamento_auditoria_id)
        consultar(mesConsulta, añoConsulta);
        //console.log(anotherArray)
    } else {
        rubrosAct.value = [];
    }
});

const consultar = async (mes, año) => {
    rubrosCalculados;
    await axios
        .get(
            "/getAnterioresRubros/" +
                params.departamento_auditoria_id +
                "/" +
                mes +
                "/" +
                año
        )
        .then((response) => {
            // Obtenemos los datos
            rubrosAct.value = response.data;
        })
        .catch((e) => {
            // Capturamos los errores
        });
    proc.value = props.procesos[0];
};

moment.updateLocale("en", {
    months: [
        "Enero",
        "Febrero",
        "Marzo",
        "Abril",
        "Mayo",
        "Junio",
        "Julio",
        "Agosto",
        "Septiembre",
        "Octubre",
        "Noviembre",
        "Diciembre",
    ],
});
</script>

<template>
    <AppLayout title="Dashboard" :nominas="nominas">
        <div class="bg-[#f8f8f8]">
            <section class="p-2 pt-8 objetivo_auditoria sm:p-8">
                <div
                    class="mr-0 sm:text-center sm:mr-96 sm:pt-8"
                    style="font-family: 'Montserrat'"
                >
                    <h1
                        class="text-xl font-semibold text-white sm:text-4xl sm:mr-12"
                    >
                        Objetivo del área
                    </h1>
                    <span
                        class="w-16 h-1 bg-[#EC2944] mt-4 sm:ml-96"
                        style="display: block"
                    ></span>
                </div>
                <div class="mr-0 sm:mr-12 sm:pl-16">
                    <p
                        class="mt-6 mb-16 text-base text-white sm:text-xl sm:ml-72"
                        style="font-family: 'Montserrat'; line-height: 1.8"
                    >
                        Dentro de Control Interno, nos encargamos de la creación
                        y seguimiento del cumplimiento de los procesos,
                        políticas, manuales, normas y métodos estratégicos de la
                        empresa, todo con la finalidad de llegar al plan
                        estratégico de esta, para poder lograrlo se realizan
                        evaluaciones continuamente a las distintas áreas que la
                        conforman.
                    </p>
                </div>
            </section>
            <section class="documentos">
                <div class="lateral">
                    <header
                        class="text-[#1A1A22]"
                        style="
                            font-family: 'Montserrat';
                            font-size: 0.8rem;
                            margin-bottom: 1rem;
                        "
                    >
                        DASHBOARD AUDITORIAS
                    </header>
                    <ul
                        class="menuVert"
                        v-for="dep in departamentosAuditoria"
                        :key="'dep' + dep.id"
                    >
                        <span
                            class="absolute w-2 h-8 mt-2"
                            style="float: left"
                        ></span>
                        <li @click="params.departamento_auditoria_id = dep.id">
                            <a
                                class="font-light hover:text-black hover:cursor-pointer hover:font-bold"
                                style="
                                    font-family: 'Montserrat';
                                    font-size: 0.8rem;
                                    margin-bottom: 1rem;
                                "
                                >{{ dep.nombre }}</a
                            >
                        </li>
                    </ul>
                </div>
                <div
                    v-if="params.departamento_auditoria_id !== null"
                    class="documentos_view"
                    style="margin-right: 5rem"
                >
                    <div
                        class="flex justify-between"
                        style="font-family: 'Montserrat'"
                    >
                        <div class="w-2/3">
                            <div
                                v-for="departamento in departamentosAuditoria"
                                :key="departamento.id"
                            >
                                <div
                                    v-if="
                                        departamento.id ==
                                        params.departamento_auditoria_id
                                    "
                                    class="flex items-center justify-between"
                                >
                                    <h1 class="text-2xl w-fit">
                                        {{ departamento.nombre }}
                                    </h1>

                                    <h2
                                        class="text-xl font-bold"
                                        v-if="
                                            proc &&
                                            params.departamento_auditoria_id !=
                                                6
                                        "
                                    >
                                        {{ proc.nombre }}
                                    </h2>
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-end w-1/3">
                            <SwitchButton
                                @click="cambioSwitch"
                                :checked="cambio"
                            />
                        </div>
                    </div>
                    <div class="mt-4">
                        <section
                            v-if="
                                !cambio && params.departamento_auditoria_id != 6
                            "
                            class="grid grid-cols-3 gap-12"
                        >
                            <div class="col-start-1 col-end-3">
                                <!--Graficas-->
                                <div class="flex justify-between">
                                    <h2 class="text-lg font-bold">Graficas</h2>
                                </div>
                                <div
                                    class="flex justify-between gap-[1rem] select-none mt-[2rem]"
                                    v-if="proc"
                                >
                                    <BotonProc
                                        v-for="pro in procesos"
                                        :key="id"
                                        @click="proc = pro"
                                        :active="pro.id === proc.id"
                                    >
                                        {{ pro.nombre }}
                                    </BotonProc>
                                </div>
                                <div
                                    class="mt-[2rem] bg-white rounded-2xl p-2"
                                    v-if="proc"
                                >
                                    <GraphRubMonth
                                        :rubro="rubTot"
                                        :proceso="proc.id"
                                    />
                                </div>
                                <div
                                    class="mt-[2rem] bg-white rounded-2xl p-2"
                                    v-if="proc"
                                >
                                <!--
                                    <GraphRub
                                        :rubro="rubTot"
                                        :proceso="proc.id"
                                    />
                                    -->
                                </div>
                                <div
                                    class="mt-[2rem] bg-white rounded-2xl p-2"
                                    v-if="proc"
                                >
                                    <Graph2
                                        :parametros="arregloParametros"
                                        :procesos="procesos"
                                    />
                                </div>
                            </div>
                            <div>
                                <!--Contadores {{ promedios }}-->
                                <div>
                                    <h2 class="mb-4 text-lg font-bold">
                                        Promedio
                                    </h2>
                                    <div
                                        class="w-full h-full bg-white border shadow-lg rounded-2xl"
                                    >
                                        <div
                                            v-for="(
                                                calificacion, index
                                            ) in arregloCalificaciones"
                                            :key="index"
                                            class="bg-[#F8F8F8] m-6 rounded-2xl"
                                        >
                                            <div
                                                class="grid grid-cols-2 p-3"
                                                v-if="index < 4"
                                            >
                                                <div
                                                    class="flex justify-center"
                                                >
                                                    <h1
                                                        v-if="
                                                            calificacion.date.slice(
                                                                5,
                                                                7
                                                            ) == '01'
                                                        "
                                                        class="text-3xl font-semibold"
                                                    >
                                                        Enero
                                                    </h1>
                                                    <h1
                                                        v-if="
                                                            calificacion.date.slice(
                                                                5,
                                                                7
                                                            ) == '02'
                                                        "
                                                        class="text-3xl font-semibold"
                                                    >
                                                        Febrero
                                                    </h1>
                                                    <h1
                                                        v-if="
                                                            calificacion.date.slice(
                                                                5,
                                                                7
                                                            ) == '03'
                                                        "
                                                        class="text-3xl font-semibold"
                                                    >
                                                        Marzo
                                                    </h1>
                                                    <h1
                                                        v-if="
                                                            calificacion.date.slice(
                                                                5,
                                                                7
                                                            ) == '04'
                                                        "
                                                        class="text-3xl font-semibold"
                                                    >
                                                        Abril
                                                    </h1>
                                                    <h1
                                                        v-if="
                                                            calificacion.date.slice(
                                                                5,
                                                                7
                                                            ) == '05'
                                                        "
                                                        class="text-3xl font-semibold"
                                                    >
                                                        Mayo
                                                    </h1>
                                                    <h1
                                                        v-if="
                                                            calificacion.date.slice(
                                                                5,
                                                                7
                                                            ) == '06'
                                                        "
                                                        class="text-3xl font-semibold"
                                                    >
                                                        Junio
                                                    </h1>
                                                    <h1
                                                        v-if="
                                                            calificacion.date.slice(
                                                                5,
                                                                7
                                                            ) == '07'
                                                        "
                                                        class="text-3xl font-semibold"
                                                    >
                                                        Julio
                                                    </h1>
                                                    <h1
                                                        v-if="
                                                            calificacion.date.slice(
                                                                5,
                                                                7
                                                            ) == '08'
                                                        "
                                                        class="text-3xl font-semibold"
                                                    >
                                                        Agosto
                                                    </h1>
                                                    <h1
                                                        v-if="
                                                            calificacion.date.slice(
                                                                5,
                                                                7
                                                            ) == '09'
                                                        "
                                                        class="text-3xl font-semibold"
                                                    >
                                                        Septiembre
                                                    </h1>
                                                    <h1
                                                        v-if="
                                                            calificacion.date.slice(
                                                                5,
                                                                7
                                                            ) == '10'
                                                        "
                                                        class="text-3xl font-semibold"
                                                    >
                                                        Octubre
                                                    </h1>
                                                    <h1
                                                        v-if="
                                                            calificacion.date.slice(
                                                                5,
                                                                7
                                                            ) == '11'
                                                        "
                                                        class="text-3xl font-semibold"
                                                    >
                                                        Noviembre
                                                    </h1>
                                                    <h1
                                                        v-if="
                                                            calificacion.date.slice(
                                                                5,
                                                                7
                                                            ) == '12'
                                                        "
                                                        class="text-3xl font-semibold"
                                                    >
                                                        Diciembre
                                                    </h1>
                                                </div>
                                                <div
                                                    class="flex justify-center"
                                                >
                                                    <h1
                                                        class="text-3xl font-semibold text-[#D9435E]"
                                                        v-if="
                                                            calificacion.promedio <=
                                                            30
                                                        "
                                                    >
                                                        {{
                                                            calificacion.promedio
                                                        }}
                                                    </h1>
                                                    <h1
                                                        class="text-3xl font-semibold text-[#F28C20]"
                                                        v-if="
                                                            calificacion.promedio >
                                                                30 &&
                                                            calificacion.promedio <=
                                                                60
                                                        "
                                                    >
                                                        {{
                                                            calificacion.promedio
                                                        }}
                                                    </h1>
                                                    <h1
                                                        class="text-3xl font-semibold text-[#00CB83]"
                                                        v-if="
                                                            calificacion.promedio >
                                                            60
                                                        "
                                                    >
                                                        {{
                                                            calificacion.promedio
                                                        }}
                                                    </h1>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-16">
                                    <div
                                        class="w-full h-full bg-white border shadow-lg rounded-2xl"
                                    >
                                        <div
                                            class="grid grid-cols-2 m-2 text-center border-b"
                                            style="
                                                font-family: 'Montserrat';
                                                letter-spacing: 0.2rem;
                                            "
                                        >
                                            <h1
                                                class="uppercase font-extralight"
                                            >
                                                Parametros
                                            </h1>
                                            <h1
                                                class="uppercase font-extralight"
                                            >
                                                Calif.
                                            </h1>
                                        </div>
                                        <template
                                            v-for="rubro in rubrosAct"
                                            :key="rubro.id"
                                        >
                                            <div
                                                class="grid content-center grid-cols-2 m-2 text-center odd:bg-[#f8f8f8] rounded-xl px-2 py-1"
                                                v-if="rubro.proceso === proc.id"
                                            >
                                                <!--RUBROS PEOR CALIFICADOS DEL ULTIMO MES CALF-->
                                                <div
                                                    class="text-md"
                                                    v-if="
                                                        rubro.proceso ===
                                                        proc.id
                                                    "
                                                >
                                                    <span class="font-bold">
                                                        {{ rubro.rubro_nombre }}
                                                    </span>
                                                </div>
                                                <div
                                                    v-if="
                                                        rubro.proceso ===
                                                        proc.id
                                                    "
                                                >
                                                    <span
                                                        class="flex items-center justify-center h-full font-bold"
                                                    >
                                                        <span
                                                            v-if="
                                                                rubro.valor < 40
                                                            "
                                                            class="text-[#D9435E]"
                                                        >
                                                            {{ rubro.valor }}
                                                        </span>
                                                        <span
                                                            v-if="
                                                                rubro.valor >=
                                                                    40 &&
                                                                rubro.valor < 60
                                                            "
                                                            class="text-[#F28C20]"
                                                        >
                                                            {{ rubro.valor }}
                                                        </span>
                                                        <span
                                                            v-if="
                                                                rubro.valor >=
                                                                    60 &&
                                                                rubro.valor <
                                                                    100
                                                            "
                                                            class="text-[#00CB83]"
                                                        >
                                                            {{ rubro.valor }}
                                                        </span>
                                                    </span>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                            <div class="col-start-1 col-end-4">
                                <h2 class="mb-4 text-lg font-bold">
                                    Documentos que comprueban
                                </h2>
                                <div class="grid grid-cols-3 gap-8">
                                    <div
                                        v-for="documento in documentos_mes"
                                        :key="documento.id"
                                        class="border shadow-lg h-72 w-72"
                                    >
                                        <!--Card Documento-->
                                        <img
                                            v-if="
                                                documento.documento.endsWith(
                                                    '.pdf'
                                                ) ||
                                                documento.documento.endsWith(
                                                    '.png'
                                                ) ||
                                                documento.documento.endsWith(
                                                    '.jpg'
                                                ) ||
                                                documento.documento.endsWith(
                                                    '.svg'
                                                )
                                            "
                                            data-fancybox
                                            :href="documento.documento"
                                            class="w-full h-56"
                                            alt="imagen"
                                            :src="documento.portada"
                                        />
                                        <img
                                            v-else
                                            class="w-full h-56"
                                            alt="imagen"
                                            :src="documento.portada"
                                            download
                                            :href="documento.documento"
                                        />
                                        <div>
                                            <span
                                                class="bg-[#EC2944] h-14 w-2 absolute"
                                            ></span>
                                            <h2 class="ml-4">
                                                {{ documento.proceso_name }}
                                            </h2>
                                            <h2 class="ml-4">
                                                {{
                                                    documento.name +
                                                    " " +
                                                    documento.apellido_paterno +
                                                    " " +
                                                    documento.apellido_materno
                                                }}
                                            </h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <section
                            v-if="
                                params.departamento_auditoria_id === 6 &&
                                !cambio
                            "
                            class="grid mb-8"
                        >
                            <div class="flex justify-between">
                                <h2 class="text-lg font-bold">Graficas</h2>
                            </div>

                            <div class="grid gap-8">
                                <div>
                                    <div
                                        class="flex justify-between gap-[1rem] select-none mt-[2rem]"
                                        v-if="proc"
                                    >
                                        <BotonProc
                                            v-for="pro in arregloParametros"
                                            @click="
                                                proc = pro;
                                                now = '';
                                            "
                                            :active="
                                                pro.date === proc.date ||
                                                Number(
                                                    pro.date.split('-')[1]
                                                ) === now
                                            "
                                            class="uppercase"
                                        >
                                            {{
                                                moment(pro.date)
                                                    .locale("es")
                                                    .format("MMMM")
                                            }}
                                        </BotonProc>
                                    </div>
                                    <GraphPastel
                                        :rubro="rubTot"
                                        :proceso="proc.date"
                                        :meses="arregloParametros"
                                    />
                                </div>
                                <div>
                                    <TablaSUA
                                        :rubTot="rubTot"
                                        :meses="arregloParametros"
                                    />
                                </div>
                            </div>
                        </section>

                        <section v-if="cambio">
                            <h1 class="text-sm font-bold">Procesos</h1>
                            <ButtonAdd
                                class="mt-2"
                                @click="openModalAddProceso"
                            >
                                Agregar proceso
                            </ButtonAdd>
                            <div class="grid grid-cols-3 gap-6 mt-4 mb-8">
                                <div
                                    class="flex flex-col items-center h-64 border shadow-xl rounded-xl"
                                    v-for="proceso in procesos"
                                    :key="proceso.id"
                                >
                                    <div
                                        class="flex flex-col items-center mt-2"
                                    >
                                        <img
                                            class="w-14 h-14"
                                            alt="logo"
                                            :src="proceso.logo"
                                        />
                                        <h3 class="mt-4 text-xl">
                                            {{ proceso.nombre }}
                                        </h3>
                                    </div>
                                    <div
                                        class="grid w-full grid-cols-2 mt-20 border-t-2"
                                    >
                                        <div
                                            @click="openModalAddDoc(proceso.id)"
                                            class="w-full p-4 text-center border-r-2"
                                        >
                                            Documentos
                                        </div>

                                        <div
                                            @click="
                                                openModalShowCalif(
                                                    proceso.id,
                                                    year
                                                )
                                            "
                                            class="w-full p-4 text-center"
                                        >
                                            Calificaciones
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </section>
            <!--Docs responsive-->
            <section class="static sm:hidden" style="font-family: 'Montserrat'">
                <div class="flex flex-col mt-8 ml-2">
                    <h1 class="uppercase font-semibold text-[#1A1A22]">
                        Dashboard Auditorías
                    </h1>
                    <div class="flex flex-row p-2">
                        <div
                            class="flex mr-2"
                            :class="{
                                ' transition':
                                    route().current('control-interno*'),
                            }"
                        >
                            <Dropdown align="right" width="48">
                                <template #trigger>
                                    <ButtonAdd
                                        type="button"
                                        class="inline-flex items-center py-2 mt-0 mb-2 text-sm transition focus:outline-none"
                                    >
                                        <p class="text-black">VER CATEGORIAS</p>
                                    </ButtonAdd>
                                </template>
                                <template #content>
                                    <button
                                        class="ml-2 mr-12 sm:ml-0 hover:cursor-pointer"
                                        v-for="dep in departamentosAuditoria"
                                        :key="'dep' + dep.id"
                                    >
                                        <span
                                            @click="
                                                params.departamento_auditoria_id =
                                                    dep.id
                                            "
                                            class="text-xs"
                                        >
                                            <a class="font-semibold">{{
                                                dep.nombre
                                            }}</a>
                                        </span>
                                    </button>
                                    <div class="border-t border-gray-100" />
                                </template>
                            </Dropdown>
                        </div>

                        <ButtonAdd
                            v-if="
                                $page.props.can['calificacion.create'] &&
                                departamento != undefined
                            "
                            @click="showingFormCalificacion = true"
                        >
                            <p class="text-xs">AGREGAR DOCUMENTO</p>
                        </ButtonAdd>
                    </div>
                </div>
            </section>
            <!-- Modal Procesos -->
            <AddProcesoModal
                :departamento="parseInt(params.departamento_auditoria_id)"
                :show="modalAddProceso"
                @close="closeModalAddProceso"
            />
            <!-- End Modal Procesos -->

            <!-- Modal Documentos -->
            <ShowDocumentoModal
                @recargarDocs="consultarDocumentos"
                :documentos="documentos"
                :usuarios="usuarios"
                :procesoId="procesoReactive"
                :show="modalAddDocumento"
                @close="closeModalAddDoc"
            />
            <!--End Modal Documentos -->

            <!-- Modal Calificaciones -->
            <ShowCalificacionesModal
                :calificaciones="calificaciones"
                :rubros="rubros"
                :procesoId="procesoReactive"
                :show="modalAdCalf"
                @close="closeModalShowCalif"
                @consultarRubros="openModalShowCalif"
            />
            <!--End Modal Calificaciones -->
        </div>
    </AppLayout>
</template>

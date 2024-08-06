<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import FullCalendar from "@fullcalendar/vue3";
import rrulePlugin from "@fullcalendar/rrule";
import multiMonthPlugin from "@fullcalendar/multimonth";
import esLocale from "@fullcalendar/core/locales/es";
import { ref, watchEffect } from "vue";
import axios from "axios";
import DiaData from "./Modals/DiaData.vue";
import ReporteMensual from "./Modals/ReporteMensual.vue";
import interactionPlugin from "@fullcalendar/interaction";

defineProps({
    nominas: {
        type: Object,
        default: null,
    },
});

const dataEvents = ref([
    {
        title: "Año Nuevo",
        date: "0000-01-01",
        rrule: {
            freq: "yearly",
            dtstart: "2024-01-01",
        },
        color: "#3EB3A3",
        display: "list-item",
        description: "Año Nuevo",
    },
    {
        title: "Aniversario de la promulgación de la Constitución de 1917",
        date: "2024-02-05",
        rrule: {
            freq: "yearly",
            dtstart: "0000-02-05",
            bymonth: [2],
            byweekday: ["mo"],
            byweekno: [6],
        },
        color: "#3EB3A3",
        display: "list-item",
    },
    {
        title: "Natalicio de Benito Juárez",
        date: "2024-03-21",
        rrule: {
            freq: "yearly",
            dtstart: "0000-03-21",
            bymonth: [3],
            byweekday: ["mo"],
            byweekno: [12],
        },
        color: "#3EB3A3",
        display: "list-item",
    },
    {
        title: "Día del Trabajo",
        date: "0000-05-01",
        rrule: {
            freq: "yearly",
            dtstart: "2024-05-01",
        },
        color: "#3EB3A3",
        display: "list-item",
    },
    {
        title: "Independencia de México",
        date: "0000-09-16",
        rrule: {
            freq: "yearly",
            dtstart: "2024-09-16",
        },
        color: "#3EB3A3",
        display: "list-item",
    },
    {
        title: "Toma presidencial",
        date: "2018-10-01",
        rrule: {
            freq: "yearly",
            dtstart: "2018-10-01",
            interval: 6,
        },
        color: "#3EB3A3",
        display: "list-item",
    },
    {
        title: "Revolución Mexicana",
        date: "0000-11-20",
        rrule: {
            freq: "yearly",
            dtstart: "0000-11-20",
            bymonth: [11],
            byweekday: ["mo"],
            byweekno: [47],
        },
        color: "#3EB3A3",
        display: "list-item",
    },
    {
        title: "Navidad",
        date: "0000-12-25",
        rrule: {
            freq: "yearly",
            dtstart: "0000-12-25",
        },
        color: "#3EB3A3",
        display: "list-item",
    },
]);
const dayShow = ref(false);
const reporteMensShow = ref(false);
const position = ref(null);
const evento = ref(null);

let calendar = {
    plugins: [multiMonthPlugin, rrulePlugin, interactionPlugin],
    initialView: "multiMonthYear",
    timeZone: "UTC",
    events: dataEvents.value,
    multiMonthMaxColumns: 4,
    locale: esLocale,
    moreLinkClick: (info) => {
        position.value = info.jsEvent.target.getBoundingClientRect();
        info.event = {
            startStr: info.allSegs[0].event.startStr,
        };
        evento.value = info;
        dayShow.value = true;
        return "day";
    },
    headerToolbar: {
        start: "prev",
        center: "title",
        end: "downloadReport next",
    },
    customButtons: {
        downloadReport: {
            text: "Reporte de vacaciones",
            click: function (evt) {
                position.value = evt.target.getBoundingClientRect();
                reporteMensShow.value = true;
            },
        },
    },
    eventClick: function (info) {
        position.value = info.jsEvent.target.getBoundingClientRect();
        evento.value = info;
        dayShow.value = true;
    },
    dayHeaderClassNames: "dayHeaderClassNames",
};

function getDataCalendarVacaciones() {
    axios
        .post(route("getDataCalendarVacaciones"))
        .then(({ data }) => {
            data.cumpleaños.forEach((event) => {
                if (event.title !== null) {
                    dataEvents.value.push({
                        date: event.fecha_nacimiento,
                        title: event.title,
                        rrule: {
                            freq: "yearly",
                            dtstart: event.fecha_nacimiento,
                        },
                        color: "#ec2944",
                    });
                }
            });
            data.vacaciones.forEach((event) => {
                const fechas = JSON.parse(event.fechas);

                dataEvents.value.push({
                    title: event.title,
                    start: fechas[0],
                    end: fechas[fechas.length - 1],
                    color: "#44befa",
                });
            });
        })
        .catch((err) => {
            console.log(err.response ?? err);
        });
}

watchEffect(() => getDataCalendarVacaciones());
</script>
<template>
    <AppLayout title="Vacaciones Calendar" :nominas="nominas">
        <div class="p-4">
            <FullCalendar :options="calendar" />
        </div>
        <DiaData
            :position="position"
            :show="dayShow"
            @close="dayShow = false"
            :evento="evento"
        />
        <ReporteMensual
            :position="position"
            :show="reporteMensShow"
            @close="reporteMensShow = false"
        />
    </AppLayout>
</template>

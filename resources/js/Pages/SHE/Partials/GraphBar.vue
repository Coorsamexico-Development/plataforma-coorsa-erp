<script setup>
import * as am5 from "@amcharts/amcharts5";
import * as am5xy from "@amcharts/amcharts5/xy";
import am5themes_Animated from "@amcharts/amcharts5/themes/Animated";
import * as am5plugins_exporting from "@amcharts/amcharts5/plugins/exporting";
import { onMounted, onUpdated, ref, watch } from "vue";
import axios from "axios";

const reporteDetalladoGraphBar = ref(null);
const emit = defineEmits(["columnClick"]);
const props = defineProps({
    periodo: {
        type: String,
        default: "mensual",
    },
    ruta: {
        type: String,
        default: "dataGraphBarDetalle",
    },
    tipo: {
        type: String,
    },
    barPercent: {
        type: Number,
        default: 90,
    },
    filters: {
        type: Object,
        default: null,
    },
});

let cursor;
let root;
let chart;
let legend;
let data = [];
let xAxis;
let yAxis;
let series;
let seriesCumplimiento;
const first = ref(true);

let meses = [
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
    "Deciembre",
];

onMounted(() => {
    root = am5.Root.new(reporteDetalladoGraphBar.value);

    root.setThemes([am5themes_Animated.new(root)]);
    root.fps = 120;

    chart = root.container.children.push(
        am5xy.XYChart.new(root, {
            panX: false,
            panY: false,
            wheelX: "panX",
            wheelY: "zoomX",
            paddingLeft: 0,
            layout: root.verticalLayout,
        })
    );

    //setColorTheme();

    legend = chart.children.push(
        am5.Legend.new(root, {
            centerX: am5.p50,
            x: am5.p50,
        })
    );

    if (first) {
        getDataTable();
    }
});

function generateData() {
    value = Math.round(Math.random() * 10 - 5 + value);
    am5.time.add(date, "month", 1);
    return {
        date: date.getTime(),
        value: value,
    };
}
async function getDataTable() {
    const { data: response } = await axios
        .post(route(props.ruta, { ...props }))
        .catch((err) => console.log(err.response));

    if (response) {
        data = [];

        response.data.forEach((datos) => {
            const date = new Date(datos.date);
            let info = {};
            date.setHours(0, 0, 0, 0);
            am5.time.add(date, "month", 0);

            info.date = date.getTime();
            info[datos.tipo] = datos.cantidad;
            console.log(info);
            data.push(info);
        });

        xAxis = chart.xAxes.push(
            am5xy.DateAxis.new(root, {
                maxDeviation: 0,
                baseInterval: {
                    timeUnit: "month",
                    count: 1,
                },
                renderer: am5xy.AxisRendererX.new(root, {
                    minorGridEnabled: true,
                    minorLabelsEnabled: true,
                }),
                tooltip: am5.Tooltip.new(root, {}),
            })
        );

        xAxis.set("minorDateFormats", {
            month: "MM",
            year: "YYYY",
        });

        yAxis = chart.yAxes.push(
            am5xy.ValueAxis.new(root, {
                extraMax: 0.1,
                min: 0,
                renderer: am5xy.AxisRendererY.new(root, {
                    strokeOpacity: 0.1,
                }),
            })
        );

        response.series.forEach((serie) => {
            makeSeries(serie.name, serie.name);
        });
        cursor = chart.set(
            "cursor",
            am5xy.XYCursor.new(root, {
                behavior: "zoomX",
            })
        );
        cursor.lineY.set("visible", false);

        chart.appear(1000, 100);
    } else console.log(resp.error.response ?? resp.error);
}

watch(props, () => {
    root.container.children.clear();
    seriesCumplimiento.bullets.clear();
    chart.remove("cursor");

    chart = root.container.children.push(
        am5xy.XYChart.new(root, {
            panX: false,
            panY: false,
            wheelX: "panX",
            wheelY: "zoomX",
            paddingLeft: 0,
            layout: root.verticalLayout,
        })
    );

    //setColorTheme();

    legend = chart.children.push(
        am5.Legend.new(root, {
            centerX: am5.p50,
            x: am5.p50,
        })
    );

    getDataTable();
});

function setColorTheme() {
    chart
        .get("colors")
        .set("colors", [
            am5.color(0x267365),
            am5.color(0xf2cb05),
            am5.color(0xf29f05),
            am5.color(0xf28705),
            am5.color(0xf23030),
        ]);
}

function makeSeries(name, fieldName, stacked = false, center = false) {
    series = chart.series.push(
        am5xy.ColumnSeries.new(root, {
            name: name,
            xAxis: xAxis,
            yAxis: yAxis,
            valueYField: fieldName,
            valueXField: "date",
        })
    );
    series.columns.template.setAll({
        tooltipText: "{name}, {valueXField}:{valueY}",
    });

    series.data.setAll(data);

    series.appear();

    series.bullets.push(function () {
        return am5.Bullet.new(root, {
            locationY: center ? 0.5 : 1,
            sprite: am5.Label.new(root, {
                text: "{valueY}",
                fill: root.interfaceColors.get("alternativeText"),
                centerY: am5.percent(50),
                centerX: am5.percent(10),
                populateText: true,
                rotation: -90,
                fontWeight: 500,
            }),
        });
    });

    legend.data.push(series);
}
</script>
<template>
    <div ref="reporteDetalladoGraphBar" class="w-full h-[45vh]"></div>
</template>

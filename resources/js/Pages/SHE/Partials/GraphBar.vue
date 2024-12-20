<script setup>
import * as am5 from "@amcharts/amcharts5";
import * as am5xy from "@amcharts/amcharts5/xy";
import am5themes_Animated from "@amcharts/amcharts5/themes/Animated";
import { onMounted, onUnmounted, ref, watch } from "vue";
import axios from "axios";
import moment from "moment";
import Add from "@/Iconos/Add.vue";

const graphBar = ref(null);
const emit = defineEmits(["columnClick", "addClick"]);
const props = defineProps({
    ruta: {
        type: String,
        default: "dataGraphBarDetalle",
    },
    table: {
        type: String,
        required: true,
    },
    title: String,
    channel: String,
});

let cursor;
let root;
let chart;
let legend;
let xAxis;
let yAxis;
let series;
const first = ref(true);

onMounted(() => {
    root = am5.Root.new(graphBar.value);

    root.setThemes([am5themes_Animated.new(root)]);

    chart = root.container.children.push(
        am5xy.XYChart.new(root, {
            panX: false,
            panY: false,
            wheelX: "panX",
            wheelY: "zoomX",
            layout: root.verticalLayout,
        })
    );

    setColorTheme();

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

async function getDataTable() {
    const {
        data: { series: tipos, data: datos },
    } = await axios
        .post(route(props.ruta, { ...props }))
        .catch((err) => console.log(err.response));
    console.log(datos);

    if (tipos) {
        xAxis = chart.xAxes.push(
            am5xy.DateAxis.new(root, {
                maxDeviation: 0,
                groupData: true,
                baseInterval: {
                    timeUnit: "month",
                    count: 1,
                },
                gridIntervals: [
                    { timeUnit: "month", count: 1 },
                    { timeUnit: "year", count: 1 },
                ],
                renderer: am5xy.AxisRendererX.new(root, {
                    minorGridEnabled: true,
                    minorLabelsEnabled: true,
                }),
                tooltip: am5.Tooltip.new(root, {}),
            })
        );

        xAxis.set("minorDateFormats", {
            month: "MM",
            year: "yyyy",
        });

        yAxis = chart.yAxes.push(
            am5xy.ValueAxis.new(root, {
                min: 0,
                max: 100,
                renderer: am5xy.AxisRendererY.new(root, {
                    strokeOpacity: 0.1,
                }),
            })
        );

        yAxis.set("numberFormat", "#'%");

        tipos.forEach((serie) => {
            let values = [];
            datos.forEach((dato) => {
                const date = new Date(dato.date);
                let info = {};
                date.setHours(0, 0, 0, 0);

                if (serie.name === dato.tipo) {
                    info[serie.name] = Number(dato.cantidad);
                    info.date = date.getTime();
                    values.push(info);
                }
            });
            makeSeries(serie.name, serie.name, values);
        });

        cursor = chart.set(
            "cursor",
            am5xy.XYCursor.new(root, {
                behavior: "zoomX",
            })
        );
        cursor.lineY.set("visible", false);

        chart.set(
            "scrollbarX",
            am5.Scrollbar.new(root, {
                orientation: "horizontal",
            })
        );

        chart.children.unshift(
            am5.Label.new(root, {
                text: props.title,
                fontSize: 30,
                fontWeight: "500",
                textAlign: "center",
                x: am5.percent(50),
                centerX: am5.percent(50),
                paddingTop: 0,
                paddingBottom: 0,
            })
        );

        chart.appear(1000, 100);
    }
}

watch(props, () => {
    root.container.children.clear();
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

    setColorTheme();

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
            am5.color(0x3e55b1),
        ]);
}

async function makeSeries(name, fieldName, resp) {
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
        tooltipText: "{name}, Calificacion: {valueY}%",
        tooltipY: am5.percent(10),
        paddingTop: 10,
        strokeOpacity: 0,
    });

    series.data.setAll(resp);

    series.appear();

    /* const mes = moment();
    const año = moment().format("YYYY");
    series.events.once("datavalidated", function (ev) {
        ev.target
            .get("xAxis")
            .zoomToDates(
                new Date(año, mes.format("MM"), 1),
                new Date(año, mes.format("MM"), 1)
            );
    }); */

    legend.data.push(series);
}

onMounted(() => {
    Echo.channel(props.channel).listen("SheGraph", () => {
        root.container.children.clear();
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

        setColorTheme();

        legend = chart.children.push(
            am5.Legend.new(root, {
                centerX: am5.p50,
                x: am5.p50,
            })
        );

        getDataTable();
    });
});

onUnmounted(() => {
    Echo.leave(props.channel);
});
</script>
<template>
    <div ref="graphBar" class="w-full h-[45dvh] relative">
        <div
            class="absolute z-10 flex items-center justify-end w-full p-4 rounded-full"
        >
            <Add
                class="h-[2rem] w-[2rem] hover:cursor-pointer active:scale-95 transition-all duration-200"
                @click="$emit('addClick', $event)"
                v-if="$page.props.can['edit.she']"
            />
        </div>
    </div>
</template>

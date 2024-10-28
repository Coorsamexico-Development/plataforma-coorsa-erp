<script setup>
import * as am5 from "@amcharts/amcharts5";
import * as am5xy from "@amcharts/amcharts5/xy";
import am5themes_Animated from "@amcharts/amcharts5/themes/Animated";
import * as am5plugins_exporting from "@amcharts/amcharts5/plugins/exporting";
import { onMounted, onUpdated, ref, watch } from "vue";
import axios from "axios";
import { months } from "../../../utils/index.js";
import Add from "@/Iconos/Add.vue";

const graphBar = ref(null);
const emit = defineEmits(["columnClick"]);
const props = defineProps({
    ruta: {
        type: String,
        default: "getTableCDUQ",
    },
    filters: {
        type: Object,
        default: null,
    },
    table: {
        type: String,
        required: true,
    },
    title: String,
    channel: String,
});

let root;
let chart;
let legend;
let data = [];
let xRenderer;
let xAxis;
let yAxis;
let series;
const first = ref(true);
let cursor;

onMounted(() => {
    root = am5.Root.new(graphBar.value);

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
        .post(route(props.ruta, { ...props, ...props.filters }))
        .catch((err) => console.log(err.response));
    console.log(datos);

    data = [];
    let value = [];
    datos.forEach(
        (dato) => (value[dato.tipo] = Number(dato.cantidad).toFixed(0))
    );
    data.push({
        month: "Acto Inseguro",
        ...value,
    });

    if (tipos) {
        xRenderer = am5xy.AxisRendererX.new(root, {
            cellStartLocation: 0.1,
            cellEndLocation: 0.9,
            minorGridEnabled: true,
            minGridDistance: 60,
        });

        xAxis = chart.xAxes.push(
            am5xy.CategoryAxis.new(root, {
                categoryField: "month",
                renderer: xRenderer,
                tooltip: am5.Tooltip.new(root, {}),
            })
        );

        xRenderer.grid.template.setAll({ location: 1 });

        xAxis.data.setAll(data);

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

        tipos.forEach((tipo) => {
            makeSeries(tipo.name, tipo.name);
        });
        cursor = chart.set(
            "cursor",
            am5xy.XYCursor.new(root, {
                behavior: "zoomX",
            })
        );
        cursor.lineY.set("visible", false);

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
        ]);
}

function makeSeries(name, fieldName, stacked = false) {
    series = chart.series.push(
        am5xy.ColumnSeries.new(root, {
            stacked: stacked,
            name: name,
            xAxis: xAxis,
            yAxis: yAxis,
            valueYField: fieldName,
            categoryXField: "month",
        })
    );

    series.columns.template.setAll({
        tooltipText: "{categoryX}, {name}: {valueY}",
        tooltipY: am5.percent(10),
        paddingTop: 10,
    });

    series.data.setAll(data);

    series.appear();

    legend.data.push(series);
}
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

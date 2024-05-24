<script setup>
import * as am5 from "@amcharts/amcharts5";
import * as am5xy from "@amcharts/amcharts5/xy";
import am5themes_Animated from "@amcharts/amcharts5/themes/Animated";
import { onMounted, ref, watchEffect } from "vue";
import { isArray, isObject } from "lodash";

const graphBarNomina = ref(null);
const props = defineProps({
    data: {
        required: true,
    },
});

let root;
let chart;
let xRenderer;
let xAxis;
let yAxis;
let yRenderer;
let series;
let data = [];
let cursor;

onMounted(() => {
    root = am5.Root.new(graphBarNomina.value);
    root.setThemes([am5themes_Animated.new(root)]);
    chart = root.container.children.push(
        am5xy.XYChart.new(root, {
            panX: true,
            panY: true,
            wheelX: "panX",
            wheelY: "zoomX",
            pinchZoomX: true,
            paddingLeft: 0,
            paddingRight: 1,
        })
    );
    cursor = chart.set("cursor", am5xy.XYCursor.new(root, {}));
    cursor.lineY.set("visible", false);

    xRenderer = am5xy.AxisRendererX.new(root, {
        minGridDistance: 30,
        minorGridEnabled: true,
    });

    xRenderer.labels.template.setAll({
        rotation: -90,
        centerY: am5.p50,
        centerX: am5.p100,
        paddingRight: 15,
    });

    xRenderer.grid.template.setAll({
        location: 1,
    });

    xAxis = chart.xAxes.push(
        am5xy.CategoryAxis.new(root, {
            maxDeviation: 0.3,
            categoryField: "mes",
            renderer: xRenderer,
            tooltip: am5.Tooltip.new(root, {}),
        })
    );

    yRenderer = am5xy.AxisRendererY.new(root, {
        strokeOpacity: 0.1,
    });

    yAxis = chart.yAxes.push(
        am5xy.ValueAxis.new(root, {
            maxDeviation: 0.3,
            renderer: yRenderer,
        })
    );

    // Create series
    // https://www.amcharts.com/docs/v5/charts/xy-chart/series/
    series = chart.series.push(
        am5xy.ColumnSeries.new(root, {
            name: "Series 1",
            xAxis: xAxis,
            yAxis: yAxis,
            valueYField: "value",
            sequencedInterpolation: true,
            categoryXField: "mes",
            tooltip: am5.Tooltip.new(root, {
                labelText: "{valueY}",
            }),
        })
    );

    series.columns.template.setAll({
        cornerRadiusTL: 5,
        cornerRadiusTR: 5,
        strokeOpacity: 0,
    });
    series.columns.template.adapters.add("fill", function (fill, target) {
        return chart.get("colors").getIndex(series.columns.indexOf(target));
    });

    series.columns.template.adapters.add("stroke", function (stroke, target) {
        return chart.get("colors").getIndex(series.columns.indexOf(target));
    });

    // Set data
    data = [];

    xAxis.data.setAll(data);
    series.data.setAll(data);

    // Make stuff animate on load
    // https://www.amcharts.com/docs/v5/concepts/animations/
    series.appear(1000);
    chart.appear(1000, 100);
});

watchEffect(() => {
    if (props.data != 0) {
        data = [];
        props.data[5].forEach((e) => {
            data.push({
                mes: e.mes,
                value: e.value,
            });
        });

        xAxis.data.setAll(data);
        series.data.setAll(data);
    }
});
</script>
<template>
    <div ref="graphBarNomina" class="w-full h-[45vh]"></div>
</template>

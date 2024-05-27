<script setup>
import * as am5 from "@amcharts/amcharts5";
import * as am5xy from "@amcharts/amcharts5/xy";
import am5themes_Animated from "@amcharts/amcharts5/themes/Animated";
import { onMounted, ref, watchEffect } from "vue";
import { isArray, isObject } from "lodash";

const graphLineNomina = ref(null);
const props = defineProps({
    data: {
        type: Object,
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
    root = am5.Root.new(graphLineNomina.value);

    const myTheme = am5.Theme.new(root);

    myTheme.rule("AxisLabel", ["minor"]).setAll({
        dy: 1,
    });

    myTheme.rule("Grid", ["minor"]).setAll({
        strokeOpacity: 0.08,
    });
    root.setThemes([am5themes_Animated.new(root), myTheme]);

    chart = root.container.children.push(
        am5xy.XYChart.new(root, {
            panX: false,
            panY: false,
            wheelX: "panX",
            wheelY: "zoomX",
            paddingLeft: 0,
        })
    );

    cursor = chart.set(
        "cursor",
        am5xy.XYCursor.new(root, {
            behavior: "zoomX",
        })
    );
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
        minGridDistance: 10,
        location: 1,
    });

    xAxis = chart.xAxes.push(
        am5xy.DateAxis.new(root, {
            maxDeviation: 0,
            baseInterval: {
                timeUnit: "month",
                count: 1,
            },
            renderer: xRenderer,
            tooltip: am5.Tooltip.new(root, {}),
        })
    );

    yRenderer = am5xy.AxisRendererY.new(root, {});

    yAxis = chart.yAxes.push(
        am5xy.ValueAxis.new(root, {
            numberFormat: "#'%'",
            maxPrecision: 0,
            renderer: yRenderer,
            tooltip: am5.Tooltip.new(root, {}),
        })
    );

    series = chart.series.push(
        am5xy.LineSeries.new(root, {
            name: "Series",
            xAxis: xAxis,
            yAxis: yAxis,
            valueYField: "value",
            valueXField: "date",
            tooltip: am5.Tooltip.new(root, {
                labelText: "{valueY}%",
            }),
            connect: false,
        })
    );

    series.bullets.push(function () {
        let bulletCircle = am5.Circle.new(root, {
            radius: 5,
            fill: series.get("fill"),
        });
        return am5.Bullet.new(root, {
            sprite: bulletCircle,
        });
    });

    series.strokes.template.setAll({
        strokeWidth: 2,
    });

    // Set data
    data = [];

    xAxis.data.setAll(data);
    series.data.setAll(data);

    series.appear(1000);
    chart.appear(1000, 100);
});

let date = new Date();
date.setHours(0, 0, 0, 0);

watchEffect(() => {
    if (Object.keys(props.data).length != 0) {
        data = [];
        Object.entries(props.data).forEach(([año, meses]) => {
            Object.entries(meses).forEach(([mes, value]) => {
                date.setFullYear(value.año, value.mes - 1, 1);
                data.push({
                    date: date.getTime(),
                    value: value.value,
                });
            });
        });

        xAxis.data.setAll(data);
        series.data.setAll(data);
    }
});
</script>
<template>
    <div ref="graphLineNomina" class="w-full h-[40vh]"></div>
</template>

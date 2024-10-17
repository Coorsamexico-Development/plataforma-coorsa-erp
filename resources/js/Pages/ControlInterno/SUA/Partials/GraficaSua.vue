<script setup>
import * as am5 from "@amcharts/amcharts5";
import * as am5xy from "@amcharts/amcharts5/xy";
import am5themes_Animated from "@amcharts/amcharts5/themes/Animated";
import { onMounted, ref, watchEffect } from "vue";
import { isArray, isObject } from "lodash";

const graphSua = ref(null);
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
    root = am5.Root.new(graphSua.value);
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

    xRenderer = am5xy.AxisRendererX.new(root, {
        minorGridEnabled: true,
        minorLabelsEnabled: true,
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
            valueXField: "date",
            tooltip: am5.Tooltip.new(root, {
                labelText: "{valueY}",
            }),
        })
    );

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
            const date = new Date(e.año, e.mes - 1, 2);
            date.setHours(0, 0, 0, 0);
            console.log(date);
            data.push({
                date: date.getTime(),
                value: e.value,
            });
        });

        xAxis.data.setAll(data);
        series.data.setAll(data);
        series.appear(1000);
    }
});
</script>
<template>
    <div ref="graphSua" class="w-[45vw] h-[45vh]"></div>
</template>

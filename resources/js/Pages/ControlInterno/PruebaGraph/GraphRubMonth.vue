<script setup>
import { onMounted, watch, reactive, onBeforeUpdate, ref } from "vue";
import * as Am5 from "@amcharts/amcharts5";
import * as Am5XY from "@amcharts/amcharts5/xy";
import Am5themes_Animated from "@amcharts/amcharts5/themes/Animated";

const props = defineProps(["rubro", "proceso"]);

let chart = null;
let series = null;
let data = null;
let xAxis = null;
let yAxis = null;
let root = null;
let cursor = null;
let xRenderer = null;
let yRenderer = null;
let RubMonth = [];

onMounted(() => {
    RubMonth = [];
    props.rubro.forEach((e) => {
        if (props.proceso === e.procesoid) {
            RubMonth[e.name] = e;
        }
    });
    // Create root element
    root = Am5.Root.new("chartdiv4");

    // Set themes
    root.setThemes([Am5themes_Animated.new(root)]);

    // Create chart
    chart = root.container.children.push(
        Am5XY.XYChart.new(root, {
            panX: true,
            panY: false,
            wheelX: "panX",
            wheelY: "zoomX",
            pinchZoomX: true,
        })
    );

    // Add cursor
    cursor = chart.set("cursor", Am5XY.XYCursor.new(root, {}));
    cursor.lineY.set("visible", false);

    // Create axes
    xRenderer = Am5XY.AxisRendererX.new(root, { minGridDistance: 10 });
    xRenderer.labels.template.setAll({
        rotation: -90,
        maxHeight: 100,
        centerY: Am5.p50,
        centerX: Am5.p100,
        paddingRight: 10,
        fontSize: "12px",
        oversizedBehavior: "truncate",
        textAlign: "right",
    });

    xAxis = chart.xAxes.push(
        Am5XY.CategoryAxis.new(root, {
            maxDeviation: 0.3,
            categoryField: "country",
            renderer: xRenderer,
            tooltip: Am5.Tooltip.new(root, {}),
        })
    );
    yAxis = chart.yAxes.push(
        Am5XY.ValueAxis.new(root, {
            maxDeviation: 0.3,
            min: 0,
            max: 100,
            strictMinMax: false,
            renderer: Am5XY.AxisRendererY.new(root, {
                strokeOpacity: 0.1,
                minGridDistance: 30,
            }),
        })
    );

    // Create series
    series = chart.series.push(
        Am5XY.ColumnSeries.new(root, {
            name: "Series 1",
            xAxis: xAxis,
            yAxis: yAxis,
            valueYField: "value",
            sequencedInterpolation: true,
            categoryXField: "country",
            tooltip: Am5.Tooltip.new(root, {
                labelText: "{valueY}",
            }),
        })
    );

    series.columns.template.setAll({
        cornerRadiusTL: 10,
        cornerRadiusTR: 10,
        strokeOpacity: 0,
    });
    series.columns.template.adapters.add("fill", function (fill, target) {
        return chart.get("colors").getIndex(series.columns.indexOf(target));
    });

    series.columns.template.adapters.add("stroke", function (stroke, target) {
        return chart.get("colors").getIndex(series.columns.indexOf(target));
    });

    data = [];
    // Set data
    for (let key in RubMonth) {
        let e = RubMonth[key];
        data.push({
            country: e.name,
            value: e.calificacion,
        });
    }

    series.bullets.push(function () {
        return Am5.Bullet.new(root, {
            sprite: Am5.Label.new(root, {
                rotation: -90,
                fontSize: "10px",
                text: "{valueY}",
                fill: root.interfaceColors.get("alternativeText"),
                centerY: Am5.p50,
                centerX: Am5.p50,
                populateText: true,
            }),
        });
    });

    xAxis.data.setAll(data);
    series.data.setAll(data);

    series.appear(1000);
    chart.appear(1000, 100);
});

onBeforeUpdate(() => {
    RubMonth = [];
    props.rubro.forEach((e) => {
        if (props.proceso === e.procesoid) {
            RubMonth[e.name] = e;
        }
    });

    chart.series.pop();
    series = chart.series.push(
        Am5XY.ColumnSeries.new(root, {
            name: "Series 1",
            xAxis: xAxis,
            yAxis: yAxis,
            valueYField: "value",
            sequencedInterpolation: true,
            categoryXField: "country",
            tooltip: Am5.Tooltip.new(root, {
                labelText: "{valueY}",
            }),
        })
    );

    series.columns.template.setAll({
        cornerRadiusTL: 10,
        cornerRadiusTR: 10,
        strokeOpacity: 0,
    });
    series.columns.template.adapters.add("fill", function (fill, target) {
        return chart.get("colors").getIndex(series.columns.indexOf(target));
    });

    series.columns.template.adapters.add("stroke", function (stroke, target) {
        return chart.get("colors").getIndex(series.columns.indexOf(target));
    });

    data = [];
    // Set data
    for (let key in RubMonth) {
        let e = RubMonth[key];
        data.push({
            country: e.name,
            value: e.calificacion,
        });
    }

    series.bullets.push(function () {
        return Am5.Bullet.new(root, {
            sprite: Am5.Label.new(root, {
                rotation: -90,
                fontSize: "10px",
                text: "{valueY}",
                fill: root.interfaceColors.get("alternativeText"),
                centerY: Am5.p50,
                centerX: Am5.p50,
                populateText: true,
            }),
        });
    });

    xAxis.data.setAll(data);
    series.data.setAll(data);

    series.appear(1000);
    chart.appear(1000, 100);
});
</script>

<template>
    <div id="chartdiv4" class="w-full h-[600px]"></div>
</template>

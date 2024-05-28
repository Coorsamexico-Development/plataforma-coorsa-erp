<script setup>
import * as am5 from "@amcharts/amcharts5";
import * as am5xy from "@amcharts/amcharts5/xy";
import * as am5radar from "@amcharts/amcharts5/radar";
import am5themes_Animated from "@amcharts/amcharts5/themes/Animated";
import { onMounted, ref, watch, watchEffect } from "vue";

const radarRiesgoNominas = ref(null);
const props = defineProps({
    riesgo: {
        type: Number,
        default: 0,
    },
});

let root;
let chart;
let axisRenderer;
let xAxis;
let clockHand;
let axisDataItem;

onMounted(() => {
    root = am5.Root.new(radarRiesgoNominas.value);

    root.setThemes([am5themes_Animated.new(root)]);

    chart = root.container.children.push(
        am5radar.RadarChart.new(root, {
            panX: false,
            panY: false,
            startAngle: 160,
            endAngle: 380,
        })
    );

    axisRenderer = am5radar.AxisRendererCircular.new(root, {
        innerRadius: -40,
    });

    axisRenderer.grid.template.setAll({
        stroke: root.interfaceColors.get("background"),
        visible: true,
        strokeOpacity: 0.8,
    });

    xAxis = chart.xAxes.push(
        am5xy.ValueAxis.new(root, {
            maxDeviation: 0,
            min: 0,
            max: 5,
            strictMinMax: true,
            renderer: axisRenderer,
        })
    );

    axisDataItem = xAxis.makeDataItem({});

    clockHand = am5radar.ClockHand.new(root, {
        pinRadius: am5.percent(20),
        radius: am5.percent(100),
        bottomWidth: 40,
    });

    let bullet = axisDataItem.set(
        "bullet",
        am5xy.AxisBullet.new(root, {
            sprite: clockHand,
        })
    );

    xAxis.createAxisRange(axisDataItem);

    let label = chart.radarContainer.children.push(
        am5.Label.new(root, {
            fill: am5.color(0xffffff),
            centerX: am5.percent(50),
            textAlign: "center",
            centerY: am5.percent(50),
            fontSize: "2rem",
        })
    );

    axisDataItem.set("value", 0);

    bullet.get("sprite").on("rotation", function () {
        let value = axisDataItem.get("value");
        let fill = am5.color(0x000000);
        xAxis.axisRanges.each(function (axisRange) {
            if (
                value >= axisRange.get("value") &&
                value <= axisRange.get("endValue")
            ) {
                fill = axisRange.get("axisFill").get("fill");
            }
        });

        /* label.set("text", Number(value).toFixed(2).toString()); */

        clockHand.pin.animate({
            key: "fill",
            to: fill,
            duration: 500,
            easing: am5.ease.out(am5.ease.cubic),
        });

        clockHand.hand.animate({
            key: "fill",
            to: fill,
            duration: 500,
            easing: am5.ease.out(am5.ease.cubic),
        });
    });

    chart.bulletsContainer.set("mask", undefined);

    let bandsData = [
        {
            title: "Riesgo Alto",
            color: "#ee1f25",
            lowScore: 4,
            highScore: 5,
        },
        {
            title: "Riesgo Moderado",
            color: "#fdae19",
            lowScore: 3,
            highScore: 4,
        },
        {
            title: "Riesgo Medio",
            color: "#f3eb0c",
            lowScore: 2,
            highScore: 3,
        },
        {
            title: "Riesgo Bajo",
            color: "#b0d136",
            lowScore: 1,
            highScore: 2,
        },
        {
            title: "Sin Riesgo",
            color: "#0f9747",
            lowScore: 0,
            highScore: 1,
        },
    ];

    am5.array.each(bandsData, function (data) {
        let axisRange = xAxis.createAxisRange(xAxis.makeDataItem({}));

        axisRange.setAll({
            value: data.lowScore,
            endValue: data.highScore,
        });

        axisRange.get("axisFill").setAll({
            visible: true,
            fill: am5.color(data.color),
            fillOpacity: 0.9,
        });

        axisRange.get("label").setAll({
            text: data.title,
            inside: true,
            radius: 15,
            fontSize: "1rem",
            fill: root.interfaceColors.get("background"),
            fontWeight: 600,
        });
    });

    chart.appear(1000, 100);
});

watchEffect(() => {
    if (props.riesgo != 0) {
        axisDataItem.animate({
            key: "value",
            to: props.riesgo,
            duration: 500,
            easing: am5.ease.out(am5.ease.cubic),
        });
    }
});
</script>
<template>
    <div ref="radarRiesgoNominas" class="w-full h-[40vh]"></div>
</template>

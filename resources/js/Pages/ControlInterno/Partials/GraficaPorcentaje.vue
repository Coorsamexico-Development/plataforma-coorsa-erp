<script setup>
import * as am5 from "@amcharts/amcharts5";
import * as am5xy from "@amcharts/amcharts5/xy";
import * as am5radar from "@amcharts/amcharts5/radar";
import am5themes_Animated from "@amcharts/amcharts5/themes/Animated";
import { onMounted, ref, watch, watchEffect } from "vue";

const porcentajeGraph = ref(null);
const props = defineProps({
    porcentaje: {
        type: Number,
        default: 0,
    },
});

let root;
let chart;
let xRenderer;
let yRenderer;
let xAxis;
let yAxis;
let data;
let series1;
let series2;
let label;

onMounted(() => {
    root = am5.Root.new(porcentajeGraph.value);

    root.setThemes([am5themes_Animated.new(root)]);

    chart = root.container.children.push(
        am5radar.RadarChart.new(root, {
            panX: false,
            panY: false,
            wheelX: "panX",
            wheelY: "zoomX",
            innerRadius: am5.percent(70),
            startAngle: 0,
            endAngle: 360,
        })
    );

    data = [
        {
            category: "Porcentaje de cumplimiento",
            value: props.porcentaje,
            full: 100,
            columnSettings: {
                fill: getColor(props.porcentaje),
            },
        },
    ];

    let cursor = chart.set(
        "cursor",
        am5radar.RadarCursor.new(root, {
            behavior: "zoomX",
        })
    );
    cursor.lineY.set("visible", false);

    xRenderer = am5radar.AxisRendererCircular.new(root, {
        //minGridDistance: 50
    });

    xRenderer.labels.template.setAll({
        forceHidden: true,
        radius: 0,
    });

    xRenderer.grid.template.setAll({
        forceHidden: true,
    });

    xAxis = chart.xAxes.push(
        am5xy.ValueAxis.new(root, {
            renderer: xRenderer,
            min: 0,
            max: 100,
            strictMinMax: true,
            numberFormat: "#'%'",
            tooltip: am5.Tooltip.new(root, {}),
        })
    );

    yRenderer = am5radar.AxisRendererRadial.new(root, {
        minGridDistance: 20,
    });

    yRenderer.labels.template.setAll({
        forceHidden: true,
    });

    yRenderer.grid.template.setAll({
        forceHidden: true,
    });

    yAxis = chart.yAxes.push(
        am5xy.CategoryAxis.new(root, {
            categoryField: "category",
            renderer: yRenderer,
        })
    );

    yAxis.data.setAll(data);

    series1 = chart.series.push(
        am5radar.RadarColumnSeries.new(root, {
            xAxis: xAxis,
            yAxis: yAxis,
            clustered: false,
            valueXField: "full",
            categoryYField: "category",
            fill: root.interfaceColors.get("alternativeBackground"),
        })
    );

    series1.columns.template.setAll({
        width: am5.p100,
        fillOpacity: 0.08,
        strokeOpacity: 0,
        cornerRadius: 20,
    });

    series1.data.setAll(data);

    series2 = chart.series.push(
        am5radar.RadarColumnSeries.new(root, {
            xAxis: xAxis,
            yAxis: yAxis,
            clustered: false,
            valueXField: "value",
            categoryYField: "category",
        })
    );

    series2.columns.template.setAll({
        width: am5.p100,
        strokeOpacity: 0,
        tooltipText: "{category}: {valueX}%",
        cornerRadius: 20,
        templateField: "columnSettings",
    });

    series2.data.setAll(data);

    label = chart.radarContainer.children.push(
        am5.Label.new(root, {
            fill: getColor(props.porcentaje),
            centerX: am5.percent(50),
            textAlign: "center",
            centerY: am5.percent(50),
            fontSize: "2rem",
        })
    );
    label.set("text", `${Number(props.porcentaje).toFixed(2).toString()}%`);

    series1.appear(1000);
    series2.appear(1000);
    chart.appear(1000, 100);
});

watchEffect(() => {
    if (props.porcentaje != 0 && chart != undefined) {
        data = [
            {
                category: "Porcentaje de cumplimiento",
                value: props.porcentaje,
                full: 100,
                columnSettings: {
                    fill: getColor(props.porcentaje),
                },
            },
        ];

        label.set("fill", getColor(props.porcentaje));
        label.set("text", `${Number(props.porcentaje).toFixed(2).toString()}%`);
        series1.data.setAll(data);
        series2.data.setAll(data);
        series2.appear(1000);
    }
});

function getColor(porcentaje) {
    switch (true) {
        case porcentaje <= 20:
            return "#ee1f25";
            break;
        case porcentaje > 20 && porcentaje <= 40:
            return "#fdae19";
            break;
        case porcentaje > 40 && porcentaje <= 60:
            return "#f3eb0c";
            break;
        case porcentaje > 60 && porcentaje <= 80:
            return "#b0d136";
            break;
        case porcentaje > 80 && porcentaje <= 100:
            return "#0f9747";
            break;
    }
}
</script>
<template>
    <div ref="porcentajeGraph" class="w-full h-[40vh]"></div>
</template>

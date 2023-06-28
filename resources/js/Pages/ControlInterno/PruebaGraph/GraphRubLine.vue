use Object;
<script setup>
import { onMounted, onBeforeUpdate, ref } from "vue";
import * as Am5 from "@amcharts/amcharts5";
import * as Am5XY from "@amcharts/amcharts5/xy";
import Am5themes_Animated from "@amcharts/amcharts5/themes/Animated";

const props = defineProps(["rubro", "proceso"]);
let parametros = ref(props.rubro);
let root = null;
let chart = null;
let data = [];
let RubMonth = [];
let RubMonthAux = [];
let arrayAux = [];
let valAux = parametros.value[0].id;
let legend = null;
let date = null;

onMounted(() => {
    let nameAux = null;
    RubMonth = [];
    RubMonthAux = [];
    parametros.value.forEach((e) => {
        if (props.proceso === e.procesoid) {
            if (valAux === e.id) {
                arrayAux.push({
                    id: e.id,
                    mes: e.mes,
                    calf: e.calificacion,
                    fecha: e.año + "-" + e.mes + "-02",
                });
                valAux = e.id;
                nameAux = e.name;
                if (nameAux != null) RubMonthAux[nameAux] = arrayAux;
            } else {
                if (nameAux != null) RubMonthAux[nameAux] = arrayAux;
                arrayAux = [];
                arrayAux.push({
                    id: e.id,
                    mes: e.mes,
                    calf: e.calificacion,
                    fecha: e.año + "-" + e.mes + "-02",
                });
                valAux = e.id;
                nameAux = e.name;
            }
        }
    });

    for (let key in RubMonthAux) {
        arrayAux = new Object();
        let rubro = RubMonthAux[key];
        arrayAux.name = key;
        rubro.forEach((e) => {
            switch (e.mes) {
                case 1:
                    arrayAux.Ene = e.calf;
                    arrayAux.fecha = e.fecha;
                    break;
                case 2:
                    arrayAux.Feb = e.calf;
                    arrayAux.fecha = e.fecha;
                    break;
                case 3:
                    arrayAux.Mar = e.calf;
                    arrayAux.fecha = e.fecha;
                    break;
                case 4:
                    arrayAux.Abr = e.calf;
                    arrayAux.fecha = e.fecha;
                    break;
                case 5:
                    arrayAux.May = e.calf;
                    arrayAux.fecha = e.fecha;
                    break;
                case 6:
                    arrayAux.Jun = e.calf;
                    arrayAux.fecha = e.fecha;
                    break;
                case 7:
                    arrayAux.Jul = e.calf;
                    arrayAux.fecha = e.fecha;
                    break;
                case 8:
                    arrayAux.Ago = e.calf;
                    arrayAux.fecha = e.fecha;
                    break;
                case 9:
                    arrayAux.Sep = e.calf;
                    arrayAux.fecha = e.fecha;
                    break;
                case 10:
                    arrayAux.Oct = e.calf;
                    arrayAux.fecha = e.fecha;
                    break;
                case 11:
                    arrayAux.Nov = e.calf;
                    arrayAux.fecha = e.fecha;
                    break;
                case 12:
                    arrayAux.Dic = e.calf;
                    arrayAux.fecha = e.fecha;
                    break;
            }
        });
        RubMonth.push(arrayAux);
    }

    root = Am5.Root.new("chartdiv5");

    // Set themes
    // https://www.amcharts.com/docs/v5/concepts/themes/
    root.setThemes([Am5themes_Animated.new(root)]);

    // Create chart
    // https://www.amcharts.com/docs/v5/charts/xy-chart/
    chart = root.container.children.push(
        Am5XY.XYChart.new(root, {
            panX: true,
            panY: true,
            wheelX: "panX",
            wheelY: "zoomX",
            maxTooltipDistance: 0,
            pinchZoomX: true,
        })
    );

    var value = 100;

    function generateDatas(e) {
        var data = [];
        e.forEach((element) => {
            date = new Date(element.fecha);
            date.setHours(0, 0, 0, 0);
            value = element.calf;
            Am5.time.add(date, "day", 1);
            data.push({
                date: date.getTime(),
                value: value,
            });
        });
        return data;
    }

    // Create axes
    // https://www.amcharts.com/docs/v5/charts/xy-chart/axes/
    var xAxis = chart.xAxes.push(
        Am5XY.DateAxis.new(root, {
            maxDeviation: 0.2,
            baseInterval: {
                timeUnit: "day",
                count: 1,
            },
            renderer: Am5XY.AxisRendererX.new(root, {}),
            tooltip: Am5.Tooltip.new(root, {}),
        })
    );

    var yAxis = chart.yAxes.push(
        Am5XY.ValueAxis.new(root, {
            renderer: Am5XY.AxisRendererY.new(root, {}),
        })
    );

    // Add series
    // https://www.amcharts.com/docs/v5/charts/xy-chart/series/
    for (let key in RubMonthAux) {
        var series = chart.series.push(
            Am5XY.SmoothedXYLineSeries.new(root, {
                name: key,
                xAxis: xAxis,
                yAxis: yAxis,
                valueYField: "value",
                valueXField: "date",
                legendValueText: "{valueY}",
                tooltip: Am5.Tooltip.new(root, {
                    pointerOrientation: "horizontal",
                    labelText: "{name}: {valueY}",
                }),
            })
        );

        date = new Date();
        date.setHours(0, 0, 0, 0);
        value = 0;

        var data = generateDatas(RubMonthAux[key]);
        series.data.setAll(data);

        // Make stuff animate on load
        // https://www.amcharts.com/docs/v5/concepts/animations/
        series.appear();
    }

    // Add cursor
    // https://www.amcharts.com/docs/v5/charts/xy-chart/cursor/
    var cursor = chart.set(
        "cursor",
        Am5XY.XYCursor.new(root, {
            behavior: "none",
        })
    );
    cursor.lineY.set("visible", false);

    // Add legend
    // https://www.amcharts.com/docs/v5/charts/xy-chart/legend-xy-series/
    legend = chart.bottomAxesContainer.children.push(
        Am5.Legend.new(root, {
            height: 125,
            centerX: Am5.percent(50),
            x: Am5.percent(50),
            layout: Am5.GridLayout.new(root, {
                maxColumns: 10,
                fixedWidthGrid: true,
            }),
        })
    );
    legend.valueLabels.template.set("forceHidden", true);
    legend.labels.template.setAll({
        fontSize: 13,
        fontWeight: "300",
        maxWidth: 50,
        oversizedBehavior: "truncate",
    });

    // When legend item container is hovered, dim all the series except the hovered one
    legend.itemContainers.template.events.on("pointerover", function (e) {
        var itemContainer = e.target;

        // As series list is data of a legend, dataContext is series
        series = itemContainer.dataItem.dataContext;

        chart.series.each(function (chartSeries) {
            if (chartSeries != series) {
                chartSeries.strokes.template.setAll({
                    strokeOpacity: 0.05,
                    stroke: Am5.color(0x000000),
                });
            } else {
                chartSeries.strokes.template.setAll({
                    strokeWidth: 3,
                });
            }
        });
    });

    // When legend item container is unhovered, make all series as they are
    legend.itemContainers.template.events.on("pointerout", function (e) {
        var itemContainer = e.target;
        series = itemContainer.dataItem.dataContext;

        chart.series.each(function (chartSeries) {
            chartSeries.strokes.template.setAll({
                strokeOpacity: 1,
                strokeWidth: 1,
                stroke: chartSeries.get("fill"),
            });
        });
    });

    // It's is important to set legend data after all the events are set on template, otherwise events won't be copied
    legend.data.setAll(chart.series.values);

    // Make stuff animate on load
    // https://www.amcharts.com/docs/v5/concepts/animations/
    chart.appear(1000, 100);
});

onBeforeUpdate(() => {
    parametros.value = props.rubro;
    let nameAux = null;
    RubMonth = [];
    RubMonthAux = [];
    parametros.value.forEach((e) => {
        if (props.proceso === e.procesoid) {
            if (valAux === e.id) {
                arrayAux.push({
                    id: e.id,
                    mes: e.mes,
                    calf: e.calificacion,
                    fecha: e.año + "-" + e.mes + "-02",
                });
                valAux = e.id;
                nameAux = e.name;
                if (nameAux != null) RubMonthAux[nameAux] = arrayAux;
            } else {
                if (nameAux != null) RubMonthAux[nameAux] = arrayAux;
                arrayAux = [];
                arrayAux.push({
                    id: e.id,
                    mes: e.mes,
                    calf: e.calificacion,
                    fecha: e.año + "-" + e.mes + "-02",
                });
                valAux = e.id;
                nameAux = e.name;
            }
        }
    });

    for (let key in RubMonthAux) {
        arrayAux = new Object();
        let rubro = RubMonthAux[key];
        arrayAux.name = key;
        rubro.forEach((e) => {
            switch (e.mes) {
                case 1:
                    arrayAux.Ene = e.calf;
                    arrayAux.fecha = e.fecha;
                    break;
                case 2:
                    arrayAux.Feb = e.calf;
                    arrayAux.fecha = e.fecha;
                    break;
                case 3:
                    arrayAux.Mar = e.calf;
                    arrayAux.fecha = e.fecha;
                    break;
                case 4:
                    arrayAux.Abr = e.calf;
                    arrayAux.fecha = e.fecha;
                    break;
                case 5:
                    arrayAux.May = e.calf;
                    arrayAux.fecha = e.fecha;
                    break;
                case 6:
                    arrayAux.Jun = e.calf;
                    arrayAux.fecha = e.fecha;
                    break;
                case 7:
                    arrayAux.Jul = e.calf;
                    arrayAux.fecha = e.fecha;
                    break;
                case 8:
                    arrayAux.Ago = e.calf;
                    arrayAux.fecha = e.fecha;
                    break;
                case 9:
                    arrayAux.Sep = e.calf;
                    arrayAux.fecha = e.fecha;
                    break;
                case 10:
                    arrayAux.Oct = e.calf;
                    arrayAux.fecha = e.fecha;
                    break;
                case 11:
                    arrayAux.Nov = e.calf;
                    arrayAux.fecha = e.fecha;
                    break;
                case 12:
                    arrayAux.Dic = e.calf;
                    arrayAux.fecha = e.fecha;
                    break;
            }
        });
        RubMonth.push(arrayAux);
    }
    root.container.children.pop();

    // Set themes
    // https://www.amcharts.com/docs/v5/concepts/themes/
    root.setThemes([Am5themes_Animated.new(root)]);

    // Create chart
    // https://www.amcharts.com/docs/v5/charts/xy-chart/
    chart = root.container.children.push(
        Am5XY.XYChart.new(root, {
            panX: true,
            panY: true,
            wheelX: "panX",
            wheelY: "zoomX",
            maxTooltipDistance: 0,
            pinchZoomX: true,
        })
    );

    var value = 100;

    function generateDatas(e) {
        var data = [];
        e.forEach((element) => {
            date = new Date(element.fecha);
            date.setHours(0, 0, 0, 0);
            value = element.calf;
            Am5.time.add(date, "day", 1);
            data.push({
                date: date.getTime(),
                value: value,
            });
        });
        return data;
    }

    // Create axes
    // https://www.amcharts.com/docs/v5/charts/xy-chart/axes/
    var xAxis = chart.xAxes.push(
        Am5XY.DateAxis.new(root, {
            maxDeviation: 0.2,
            baseInterval: {
                timeUnit: "day",
                count: 1,
            },
            renderer: Am5XY.AxisRendererX.new(root, {}),
            tooltip: Am5.Tooltip.new(root, {}),
        })
    );

    var yAxis = chart.yAxes.push(
        Am5XY.ValueAxis.new(root, {
            renderer: Am5XY.AxisRendererY.new(root, {}),
        })
    );

    // Add series
    // https://www.amcharts.com/docs/v5/charts/xy-chart/series/
    for (let key in RubMonthAux) {
        var series = chart.series.push(
            Am5XY.SmoothedXYLineSeries.new(root, {
                name: key,
                xAxis: xAxis,
                yAxis: yAxis,
                valueYField: "value",
                valueXField: "date",
                legendValueText: "{valueY}",
                tooltip: Am5.Tooltip.new(root, {
                    pointerOrientation: "horizontal",
                    labelText: "{name}: {valueY}",
                }),
            })
        );

        date = new Date();
        date.setHours(0, 0, 0, 0);
        value = 0;

        var data = generateDatas(RubMonthAux[key]);
        series.data.setAll(data);

        // Make stuff animate on load
        // https://www.amcharts.com/docs/v5/concepts/animations/
        series.appear();
    }

    // Add cursor
    // https://www.amcharts.com/docs/v5/charts/xy-chart/cursor/
    var cursor = chart.set(
        "cursor",
        Am5XY.XYCursor.new(root, {
            behavior: "none",
        })
    );
    cursor.lineY.set("visible", false);

    // Add legend
    // https://www.amcharts.com/docs/v5/charts/xy-chart/legend-xy-series/
    legend = chart.bottomAxesContainer.children.push(
        Am5.Legend.new(root, {
            height: 125,
            centerX: Am5.percent(50),
            x: Am5.percent(50),
            layout: Am5.GridLayout.new(root, {
                maxColumns: 10,
                fixedWidthGrid: true,
            }),
        })
    );
    legend.valueLabels.template.set("forceHidden", true);
    legend.labels.template.setAll({
        fontSize: 13,
        fontWeight: "300",
        maxWidth: 50,
        oversizedBehavior: "truncate",
    });

    // When legend item container is hovered, dim all the series except the hovered one
    legend.itemContainers.template.events.on("pointerover", function (e) {
        var itemContainer = e.target;

        // As series list is data of a legend, dataContext is series
        series = itemContainer.dataItem.dataContext;

        chart.series.each(function (chartSeries) {
            if (chartSeries != series) {
                chartSeries.strokes.template.setAll({
                    strokeOpacity: 0.05,
                    stroke: Am5.color(0x000000),
                });
            } else {
                chartSeries.strokes.template.setAll({
                    strokeWidth: 3,
                });
            }
        });
    });

    // When legend item container is unhovered, make all series as they are
    legend.itemContainers.template.events.on("pointerout", function (e) {
        var itemContainer = e.target;
        series = itemContainer.dataItem.dataContext;

        chart.series.each(function (chartSeries) {
            chartSeries.strokes.template.setAll({
                strokeOpacity: 1,
                strokeWidth: 1,
                stroke: chartSeries.get("fill"),
            });
        });
    });

    // It's is important to set legend data after all the events are set on template, otherwise events won't be copied
    legend.data.setAll(chart.series.values);

    // Make stuff animate on load
    // https://www.amcharts.com/docs/v5/concepts/animations/
    chart.appear(1000, 100);
});
</script>

<template>
    <div id="chartdiv5" class="h-[600px] w-full"></div>
</template>

<script setup>
import { onMounted, onBeforeUpdate, ref } from "vue";
import * as Am5 from "@amcharts/amcharts5";
import * as Am5XY from "@amcharts/amcharts5/xy";
import Am5themes_Animated from "@amcharts/amcharts5/themes/Animated";

const props = defineProps(["rubro", "proceso"]);
const parametros = ref(props.rubro);
let chart = null;
let series = null;
let data = null;
let xAxis = null;
let yAxis = null;
let root = null;
let legend = null;
let yRenderer = null;
let myTheme = null;
let RubMonth = [];
let RubMonthAux = [];
let arrayAux = [];
let valAux = parametros.value[0].id;

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
                });
                valAux = e.id;
                nameAux = e.name;
                RubMonthAux[nameAux] = arrayAux;
            } else {
                RubMonthAux[nameAux] = arrayAux;
                arrayAux = [];
                arrayAux.push({
                    id: e.id,
                    mes: e.mes,
                    calf: e.calificacion,
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
                    break;
                case 2:
                    arrayAux.Feb = e.calf;
                    break;
                case 3:
                    arrayAux.Mar = e.calf;
                    break;
                case 4:
                    arrayAux.Abr = e.calf;
                    break;
                case 5:
                    arrayAux.May = e.calf;
                    break;
                case 6:
                    arrayAux.Jun = e.calf;
                    break;
                case 7:
                    arrayAux.Jul = e.calf;
                    break;
                case 8:
                    arrayAux.Ago = e.calf;
                    break;
                case 9:
                    arrayAux.Sep = e.calf;
                    break;
                case 10:
                    arrayAux.Oct = e.calf;
                    break;
                case 11:
                    arrayAux.Nov = e.calf;
                    break;
                case 12:
                    arrayAux.Dic = e.calf;
                    break;
            }
        });
        RubMonth.push(arrayAux);
    }

    // Create root element
    root = Am5.Root.new("chartdiv3");

    myTheme = Am5.Theme.new(root);

    myTheme.rule("Grid", ["base"]).setAll({
        strokeOpacity: 0.1,
    });

    // Set themes
    root.setThemes([Am5themes_Animated.new(root), myTheme]);

    // Create chart
    chart = root.container.children.push(
        Am5XY.XYChart.new(root, {
            panX: false,
            panY: false,
            wheelX: "panY",
            wheelY: "zoomY",
            layout: root.verticalLayout,
        })
    );

    data = [];
    RubMonth.forEach((e) => {
        data.push(e);
    });

    // Create axes
    yRenderer = Am5XY.AxisRendererY.new(root, { minGridDistance: 10 });
    yRenderer.labels.template.setAll({
        maxWidth: 100,
        paddingRight: 10,
        fontSize: "12px",
        oversizedBehavior: "truncate",
        textAlign: "left",
    });

    yAxis = chart.yAxes.push(
        Am5XY.CategoryAxis.new(root, {
            categoryField: "name",
            renderer: yRenderer,
            tooltip: Am5.Tooltip.new(root, {}),
        })
    );

    yRenderer.grid.template.setAll({
        location: 1,
    });

    yAxis.data.setAll(data);

    xAxis = chart.xAxes.push(
        Am5XY.ValueAxis.new(root, {
            min: 0,
            visible: false,
            renderer: Am5XY.AxisRendererX.new(root, {
                strokeOpacity: 0.1,
                minGridDistance: 30,
            }),
        })
    );

    // Add legend
    legend = chart.children.push(
        Am5.Legend.new(root, {
            centerX: Am5.p50,
            x: Am5.p50,
            layout: root.gridLayout,
        })
    );

    // Add series
    function makeSeries(name, fieldName) {
        series = chart.series.push(
            Am5XY.ColumnSeries.new(root, {
                name: name,
                stacked: true,
                xAxis: xAxis,
                yAxis: yAxis,
                baseAxis: yAxis,
                valueXField: fieldName,
                categoryYField: "name",
            })
        );

        series.columns.template.setAll({
            tooltipText: "{name}, {categoryY}: {valueX}",
            tooltipY: Am5.percent(90),
        });
        series.data.setAll(data);

        // Make stuff animate on load
        series.appear(1000);

        series.bullets.push(function () {
            return Am5.Bullet.new(root, {
                sprite: Am5.Label.new(root, {
                    text: "{valueX}",
                    fill: root.interfaceColors.get("alternativeText"),
                    fontSize: "8px",
                    maxHeight: "8px",
                    oversizedBehavior: "hide",
                    textAlign: "center",
                    centerY: Am5.p50,
                    centerX: Am5.p50,
                    populateText: true,
                }),
            });
        });

        legend.data.push(series);
    }

    makeSeries("Enero", "Ene");
    makeSeries("Febrero", "Feb");
    makeSeries("Marzo", "Mar");
    makeSeries("Abril", "Abr");
    makeSeries("Mayo", "May");
    makeSeries("Junio", "Jun");
    makeSeries("Julio", "Jul");
    makeSeries("Agosto", "Ago");
    makeSeries("Septiembre", "Sep");
    makeSeries("Octubre", "Oct");
    makeSeries("Noviembre", "Nov");
    makeSeries("Diciembre", "Dic");

    // Make stuff animate on load
    chart.appear(1000, 100);
    series.appear(1000);
});

onBeforeUpdate(() => {
    if (props.rubro.length != 0) {
        parametros.value = props.rubro;
        let nameAux = null;
        valAux = parametros.value[0].id;
        RubMonthAux = [];
        arrayAux = [];
        RubMonth = [];

        parametros.value.forEach((e) => {
            if (props.proceso === e.procesoid) {
                if (valAux === e.id) {
                    arrayAux.push({
                        id: e.id,
                        mes: e.mes,
                        calf: e.calificacion,
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
                        break;
                    case 2:
                        arrayAux.Feb = e.calf;
                        break;
                    case 3:
                        arrayAux.Mar = e.calf;
                        break;
                    case 4:
                        arrayAux.Abr = e.calf;
                        break;
                    case 5:
                        arrayAux.May = e.calf;
                        break;
                    case 6:
                        arrayAux.Jun = e.calf;
                        break;
                    case 7:
                        arrayAux.Jul = e.calf;
                        break;
                    case 8:
                        arrayAux.Ago = e.calf;
                        break;
                    case 9:
                        arrayAux.Sep = e.calf;
                        break;
                    case 10:
                        arrayAux.Oct = e.calf;
                        break;
                    case 11:
                        arrayAux.Nov = e.calf;
                        break;
                    case 12:
                        arrayAux.Dic = e.calf;
                        break;
                }
            });
            RubMonth.push(arrayAux);
        }

        chart.children.pop();
        root.container.children.pop();

        chart = root.container.children.push(
            Am5XY.XYChart.new(root, {
                panX: false,
                panY: false,
                wheelX: "panY",
                wheelY: "zoomY",
                layout: root.verticalLayout,
            })
        );

        data = [];
        RubMonth.forEach((e) => {
            data.push(e);
        });

        // Create axes
        yRenderer = Am5XY.AxisRendererY.new(root, { minGridDistance: 10 });
        yRenderer.labels.template.setAll({
            maxWidth: 100,
            paddingRight: 10,
            fontSize: "12px",
            oversizedBehavior: "truncate",
            textAlign: "left",
        });

        chart.yAxes.pop();
        yAxis = chart.yAxes.push(
            Am5XY.CategoryAxis.new(root, {
                categoryField: "name",
                renderer: yRenderer,
                tooltip: Am5.Tooltip.new(root, {}),
            })
        );

        yRenderer.grid.template.setAll({
            location: 1,
        });

        yAxis.data.setAll(data);

        chart.xAxes.pop();
        xAxis = chart.xAxes.push(
            Am5XY.ValueAxis.new(root, {
                min: 0,
                visible: false,
                renderer: Am5XY.AxisRendererX.new(root, {
                    strokeOpacity: 0.1,
                    minGridDistance: 30,
                }),
            })
        );

        // Add legend
        legend = chart.children.push(
            Am5.Legend.new(root, {
                centerX: Am5.p50,
                x: Am5.p50,
            })
        );

        chart.series.pop();
        function makeSeries(name, fieldName) {
            series = chart.series.push(
                Am5XY.ColumnSeries.new(root, {
                    name: name,
                    stacked: true,
                    xAxis: xAxis,
                    yAxis: yAxis,
                    baseAxis: yAxis,
                    valueXField: fieldName,
                    categoryYField: "name",
                })
            );

            series.columns.template.setAll({
                tooltipText: " {categoryY}: {valueX}",
                tooltipY: Am5.percent(90),
            });
            series.data.setAll(data);

            // Make stuff animate on load
            series.appear(1000);

            series.bullets.push(function () {
                return Am5.Bullet.new(root, {
                    sprite: Am5.Label.new(root, {
                        text: "{valueX}",
                        fill: root.interfaceColors.get("alternativeText"),
                        fontSize: "8px",
                        maxHeight: "8px",
                        oversizedBehavior: "hide",
                        textAlign: "center",
                        centerY: Am5.p50,
                        centerX: Am5.p50,
                        populateText: true,
                    }),
                });
            });

            legend.data.push(series);
        }

        makeSeries("Enero", "Ene");
        makeSeries("Febrero", "Feb");
        makeSeries("Marzo", "Mar");
        makeSeries("Abril", "Abr");
        makeSeries("Mayo", "May");
        makeSeries("Junio", "Jun");
        makeSeries("Julio", "Jul");
        makeSeries("Agosto", "Ago");
        makeSeries("Septiembre", "Sep");
        makeSeries("Octubre", "Oct");
        makeSeries("Noviembre", "Nov");
        makeSeries("Diciembre", "Dic");

        // Make stuff animate on load
        chart.appear(1000, 100);
    }
});
</script>

<template>
    <div id="chartdiv3" class="h-[600px] w-full"></div>
</template>

<style>
#chartdiv3 {
    width: 100%;
    height: 500px;
}
</style>

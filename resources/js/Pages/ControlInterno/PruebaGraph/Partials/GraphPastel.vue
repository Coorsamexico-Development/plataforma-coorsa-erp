<script setup>
import { onMounted, onBeforeUpdate, ref } from "vue";
import * as Am5 from "@amcharts/amcharts5";
import * as Am5percent from "@amcharts/amcharts5/percent";
import Am5themes_Animated from "@amcharts/amcharts5/themes/Animated";

const props = defineProps(["rubro", "proceso", "meses"]);

const auxArray = ref([]);

onBeforeUpdate(() => {
    const i = props.meses.length;
    let auxObj = [];
    for (let a = 0; a < props.meses.length; a++) {
        auxObj = {
            mes: new Date(
                "2023-0" + (Number(props.meses[a].date.split("-")[1]) + 1)
            ).toLocaleDateString("es-MX", {
                month: "long",
            }),
            riesgo: props.rubro[a].calificacion,
            sAlta: props.rubro[a + i].calificacion,
            riesgo2: props.rubro[a + i * 2].calificacion,
            cotStra: props.rubro[a + i * 3].calificacion,
            riesIn: props.rubro[a + i * 4].calificacion,
        };

        auxArray.value[a] = auxObj;
    }
    Am5.ready(function () {
        // Create root element
        // https://www.amcharts.com/docs/v5/getting-started/#Root_element
        var root = Am5.Root.new("chartPastel");

        // Set themes
        // https://www.amcharts.com/docs/v5/concepts/themes/
        root.setThemes([Am5themes_Animated.new(root)]);

        // Create chart
        // https://www.amcharts.com/docs/v5/charts/percent-charts/pie-chart/
        var chart = root.container.children.push(
            Am5percent.PieChart.new(root, {
                layout: root.verticalLayout,
            })
        );

        // Create series
        // https://www.amcharts.com/docs/v5/charts/percent-charts/pie-chart/#Series
        var series = chart.series.push(
            Am5percent.PieSeries.new(root, {
                valueField: "value",
                categoryField: "category",
            })
        );

        // Set data
        // https://www.amcharts.com/docs/v5/charts/percent-charts/pie-chart/#Setting_data
        auxArray.value.forEach((element) => {
            console.log(element);
            series.data.setAll([
                {
                    value: element.riesgo,
                    category: "riesfgo",
                },
            ]);
        });

        // Create legend
        // https://www.amcharts.com/docs/v5/charts/percent-charts/legend-percent-series/
        var legend = chart.children.push(
            Am5.Legend.new(root, {
                centerX: Am5.percent(50),
                x: Am5.percent(50),
                marginTop: 15,
                marginBottom: 15,
            })
        );

        legend.data.setAll(series.dataItems);

        // Play initial series animation
        // https://www.amcharts.com/docs/v5/concepts/animations/#Animation_of_series
        series.appear(1000, 100);
    }); // end Am5.ready()
});
</script>
<template>
    <div id="chartPastel" class="w-full h-[600px]"></div>
</template>

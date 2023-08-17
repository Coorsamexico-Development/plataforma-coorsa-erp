<script setup>
import { onMounted, onBeforeUpdate, ref } from "vue";
import * as Am5 from "@amcharts/amcharts5";
import * as Am5percent from "@amcharts/amcharts5/percent";
import Am5themes_Animated from "@amcharts/amcharts5/themes/Animated";

const props = defineProps(["rubro", "proceso", "meses"]);

const auxArray = ref([]);
let root = "";
let chart = null;
let data = [];
let series = null;
let legend = null;

onMounted(() => {
    const now = new Date().getMonth();
    if (props.proceso === undefined) {
        props.rubro.forEach((element) => {
            if (element.mes === now)
                auxArray.value.push({
                    value: element.calificacion,
                    category: element.name,
                });
        });
    } else {
        props.rubro.forEach((element) => {
            if (element.mes === Number(props.proceso.split("-")[1]))
                auxArray.value.push({
                    value: element.calificacion,
                    category: element.name,
                });
        });
    }

    Am5.ready(function () {
        root = Am5.Root.new("chartPastel");
        // Create root element
        // Set themes
        root.setThemes([Am5themes_Animated.new(root)]);
        chart = root.container.children.push(
            Am5percent.PieChart.new(root, {
                layout: root.verticalLayout,
            })
        );
        // Create series
        series = chart.series.push(
            Am5percent.PieSeries.new(root, {
                valueField: "value",
                categoryField: "category",
            })
        );

        // Set data
        auxArray.value.forEach((element) => {
            data.push({
                value: element.value,
                category: element.category,
            });
        });
        series.data.setAll(data);

        // Create legend
        legend = chart.children.push(
            Am5.Legend.new(root, {
                centerX: Am5.percent(50),
                x: Am5.percent(50),
                marginTop: 15,
                marginBottom: 15,
            })
        );

        legend.data.setAll(series.dataItems);

        // Play initial series animation
        series.appear(1000, 100);
    }); // end Am5.ready()
});
onBeforeUpdate(() => {
    const now = new Date().getMonth();
    auxArray.value = [];
    if (props.proceso === undefined) {
        props.rubro.forEach((element) => {
            if (element.mes === now)
                auxArray.value.push({
                    value: element.calificacion,
                    category: element.name,
                });
        });
    } else {
        props.rubro.forEach((element) => {
            if (element.mes === Number(props.proceso.split("-")[1]))
                auxArray.value.push({
                    value: element.calificacion,
                    category: element.name,
                });
        });
    }
    chart.series.pop();
    series.data.pop();
    legend.data.pop();

    series = chart.series.push(
        Am5percent.PieSeries.new(root, {
            valueField: "value",
            categoryField: "category",
        })
    );

    // Set data
    data = [];
    auxArray.value.forEach((element) => {
        data.push({
            value: element.value,
            category: element.category,
        });
    });
    series.data.setAll(data);

    legend.data.setAll(series.dataItems);

    // Play initial series animation
    series.appear(1000, 100);
});
</script>
<template>
    <div id="chartPastel" class="w-full h-[600px]"></div>
</template>

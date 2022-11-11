<script setup>
import { onBeforeMount, onMounted, ref, watch } from 'vue';
import Card from '../../../Components/Card.vue';
import CirculeLogo from '../../../Components/CirculeLogo.vue';
import Title from '../../../Components/Title.vue';
import * as am5 from '@amcharts/amcharts5';
import * as am5xy from '@amcharts/amcharts5/xy';
import am5themes_Animated from '@amcharts/amcharts5/themes/Animated';

const chartCalifi = ref(null)

const props = defineProps({
    calificaciones: {
        type: Object,
        required: true
    }
})

let root;
let xAxis;
let series1;
let chart
onMounted(() => {
    root = am5.Root.new(chartCalifi.value);

    root.setThemes([am5themes_Animated.new(root)]);

    chart = root.container.children.push(
        am5xy.XYChart.new(root, {
            panY: false,
            layout: root.verticalLayout
        })
    );
    chart.get("colors").set("step", 2);


    // Define data
    let data = props.calificaciones.map(cal => {
        return {
            mes: cal.mes,
            calificacion: cal.calificacion
        }
    });

    // Create Y-axis
    var yAxis = chart.yAxes.push(
        am5xy.ValueAxis.new(root, {
            max: 100,
            min: 0,
            calculateTotals: true,
            renderer: am5xy.AxisRendererY.new(root, {})
        })
    );

    xAxis = chart.xAxes.push(
        am5xy.DateAxis.new(root, {
            baseInterval: { timeUnit: "day", count: 1 },
            renderer: am5xy.AxisRendererX.new(root, {}),
            tooltip: am5.Tooltip.new(root, {}),
            groupData: true,
            // Agrupamos por mes
            groupInterval: { timeUnit: "month", count: 1 },
        })
    );

    xAxis.data.setAll(data);

    // Create series
    series1 = chart.series.push(
        am5xy.ColumnSeries.new(root, {
            name: "Mes",
            xAxis: xAxis,
            yAxis: yAxis,
            valueYField: "calificacion",
            valueXField: "mes",
            valueYGrouped: "average",
            stacked: false,
            tooltip: am5.Tooltip.new(root, {
                labelText: "{valueY}"
            })
        })
    );

    // series1.columns.template.adapters.add("fill", function (fill, target) {
    //     return chart.get("colors").getIndex(series1.columns.indexOf(target));
    // });

    series1.data.processor = am5.DataProcessor.new(root, {
        dateFields: ["mes"],
        dateFormat: "yyyy-MM-dd"
    });

    series1.data.setAll(data);
    // Add legend
    // let legend = chart.children.push(am5.Legend.new(root, {}));
    // legend.data.setAll(chart.series.values);

    // Add cursor
    chart.set("cursor", am5xy.XYCursor.new(root, {}));

});

onBeforeMount(() => {
    if (root) {
        root.dispose();
    }
});


watch(props, () => {
    let data = props.calificaciones.map(cal => {
        return {
            mes: cal.mes,
            calificacion: cal.calificacion
        }
    });
    series1.data.setAll(data);
    xAxis.data.setAll(data);
    // series1.columns.template.adapters.add("fill", function (fill, target) {
    //     return chart.get("colors").getIndex(series1.columns.indexOf(target));
    // });
})

</script>
<template>
    <Card class="py-2">
        <Title class="flex items-center">
            <CirculeLogo url-logo="/assets/img/direccion.png" /> <span>Gr&aacute;ficas</span>
        </Title>
        <div class="w-full mx-1 h-80" ref="chartCalifi">
        </div>
        <div class="flex justify-end">
            <slot />
        </div>
    </Card>
</template>

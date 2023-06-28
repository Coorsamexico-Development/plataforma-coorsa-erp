<script setup>
import { ref, onMounted, onBeforeUpdate, computed, reactive, watch } from "vue";
import { useForm } from "@inertiajs/inertia-vue3";
import DialogModal from "@/Components/DialogModal.vue";
import * as Am5 from "@amcharts/amcharts5";
import * as Am5XY from "@amcharts/amcharts5/xy";
import Am5themes_Animated from "@amcharts/amcharts5/themes/Animated";

const emit = defineEmits(["close"]);
const props = defineProps({
    rubros: Object,
    valores: Object,
    mes: String,
    proceso: String,
    show: Boolean,
});

let chart = null;
let series = null;
let data = null;
let xAxis = null;
let yAxis = null;
let root = null;
let cursor = null;
let xRenderer = null;
let yRenderer = null;

const close = () => {
    emit("close");
};

onBeforeUpdate(() => {
    // Create root element
    try {
        root = Am5.Root.new("chartdiv10");
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

        series.columns.template.adapters.add(
            "stroke",
            function (stroke, target) {
                return chart
                    .get("colors")
                    .getIndex(series.columns.indexOf(target));
            }
        );

        data = [];
        // Set data
        props.valores.forEach((e) => {
            data.push({
                country: e.rubro_nombre,
                value: e.valor,
            });
        });

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
    } catch (error) {
        console.log("Se deja de Renderizar el DIV10");
    }
});
</script>
<template>
    <DialogModal :show="show" maxWidth="4xl" @close="close()">
        <template #title>
            <div class="grid grid-cols-2">
                <div class="flex justify-start">
                    <h2
                        class="text-lg tracking-widest uppercase font-extralight"
                    >
                        Calificaciones en el mes
                        <span class="font-bold">{{ mes }}</span> del proceso
                        <span class="font-bold">{{ proceso }}</span>
                    </h2>
                </div>
                <div class="flex justify-end">
                    <button
                        @click="close"
                        class="bg-[#EC2944] absolute p-2 text-white rounded-full"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="11.606"
                            height="11.606"
                            viewBox="0 0 11.606 11.606"
                        >
                            <g
                                id="Grupo_581"
                                data-name="Grupo 581"
                                transform="translate(-15.921 -7.94)"
                            >
                                <line
                                    id="Línea_4"
                                    data-name="Línea 4"
                                    x2="12.413"
                                    transform="translate(17.335 18.132) rotate(-45)"
                                    fill="none"
                                    stroke="#fff"
                                    stroke-linecap="round"
                                    stroke-width="2"
                                />
                                <line
                                    id="Línea_5"
                                    data-name="Línea 5"
                                    y2="12.413"
                                    transform="translate(17.335 9.355) rotate(-45)"
                                    fill="none"
                                    stroke="#fff"
                                    stroke-linecap="round"
                                    stroke-width="2"
                                />
                            </g>
                        </svg>
                    </button>
                </div>
            </div>
        </template>
        <template #content>
            <div style="overflow-y: scroll">
                <div
                    class="hello h-[600px] w-full"
                    id="chartdiv10"
                    ref="chartdiv10"
                ></div>
            </div>
        </template>
    </DialogModal>
</template>

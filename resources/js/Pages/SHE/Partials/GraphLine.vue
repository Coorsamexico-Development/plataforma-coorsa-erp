<script setup>
import * as am5 from "@amcharts/amcharts5";
import * as am5xy from "@amcharts/amcharts5/xy";
import am5themes_Animated from "@amcharts/amcharts5/themes/Animated";
import { onMounted, reactive, ref, watch, onUnmounted } from "vue";
import axios from "axios";
import moment from "moment";
import SelectInput from "@/Components/SelectInput.vue";

const graphLines = ref(null);
const emit = defineEmits(["columnClick"]);
const props = defineProps({
    periodo: {
        type: String,
        default: "mensual",
    },
    ruta: {
        type: String,
        default: "dataGraphLinesDetalle",
    },
    title: String,
});

let cursor;
let root;
let chart;
let legend;
let xAxis;
let yAxis;
let series;
const first = ref(true);

const filters = reactive({
    sitio: "6",
});

onMounted(() => {
    root = am5.Root.new(graphLines.value);

    root.setThemes([am5themes_Animated.new(root)]);
    root.fps = 120;

    chart = root.container.children.push(
        am5xy.XYChart.new(root, {
            panX: false,
            panY: false,
            wheelX: "panX",
            wheelY: "zoomX",
            layout: root.verticalLayout,
        })
    );

    setColorTheme();

    legend = chart.children.push(
        am5.Legend.new(root, {
            centerX: am5.p50,
            x: am5.p50,
        })
    );

    if (first) {
        getDataTable();
    }
});

async function getDataTable() {
    const {
        data: { series: tipos, data: datos, sitio },
    } = await axios
        .post(route(props.ruta, { ...filters }))
        .catch((err) => console.log(err.response));

    if (tipos) {
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
                renderer: am5xy.AxisRendererX.new(root, {
                    minorGridEnabled: true,
                    minorLabelsEnabled: true,
                }),
                tooltip: am5.Tooltip.new(root, {}),
            })
        );

        xAxis.set("minorDateFormats", {
            month: "MM",
            year: "yyyy",
        });

        yAxis = chart.yAxes.push(
            am5xy.ValueAxis.new(root, {
                min: 0,
                renderer: am5xy.AxisRendererY.new(root, {
                    strokeOpacity: 0.1,
                }),
            })
        );

        yAxis.set("numberFormat", "#'%");

        tipos.forEach((serie) => {
            let values = [];
            datos.forEach((dato) => {
                const date = new Date(dato.date);
                let info = {};
                date.setHours(0, 0, 0, 0);

                if (serie.name === dato.tipo) {
                    info[serie.name] = Number(dato.cantidad);
                    info.date = date.getTime();
                    values.push(info);
                }
            });

            makeSeries(serie.name, serie.name, values);
        });

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

        chart.children.unshift(
            am5.Label.new(root, {
                text: `${props.title} ${sitio.name}`,
                fontSize: 30,
                fontWeight: "500",
                textAlign: "center",
                x: am5.percent(50),
                centerX: am5.percent(50),
                paddingTop: 0,
                paddingBottom: 0,
            })
        );

        chart.appear(1000, 100);
    }
}

watch(filters, () => {
    root.container.children.clear();
    chart.remove("cursor");

    chart = root.container.children.push(
        am5xy.XYChart.new(root, {
            panX: false,
            panY: false,
            wheelX: "panX",
            wheelY: "zoomX",
            paddingLeft: 0,
            layout: root.verticalLayout,
        })
    );

    setColorTheme();

    legend = chart.children.push(
        am5.Legend.new(root, {
            centerX: am5.p50,
            x: am5.p50,
        })
    );

    getDataTable();
});

function setColorTheme() {
    chart
        .get("colors")
        .set("colors", [
            am5.color(0x267365),
            am5.color(0xf2cb05),
            am5.color(0xf29f05),
            am5.color(0xf28705),
            am5.color(0xf23030),
            am5.color(0x3e55b1),
        ]);
}

async function makeSeries(name, fieldName, resp) {
    series = chart.series.push(
        am5xy.LineSeries.new(root, {
            name: name,
            xAxis: xAxis,
            yAxis: yAxis,
            valueYField: fieldName,
            valueXField: "date",
            connect: true,
            tooltip: am5.Tooltip.new(root, {
                labelText: "{name}, {categoryX}: {valueY}%",
                fill: root.interfaceColors.get("alternativeBackground"),
            }),
        })
    );
    series.strokes.template.setAll({
        strokeWidth: 3,
        templateField: "strokeSettings",
    });

    series.data.setAll(resp);

    series.appear();

    series.bullets.push(function () {
        let bulletCircle = am5.Circle.new(root, {
            radius: 6,
            fill: am5.color(0xd1256c),
        });
        return am5.Bullet.new(root, {
            sprite: bulletCircle,
        });
    });

    const mes = moment();
    const año = moment().format("YYYY");
    series.events.once("datavalidated", function (ev) {
        ev.target
            .get("xAxis")
            .zoomToDates(
                new Date(año, mes.format("MM"), 1),
                new Date(año, mes.format("MM"), 1)
            );
    });

    /* series.columns.template.events.on("click", function (ev) {
        const date = moment(ev.target.dataItem._settings.valueX);

        emit("columnClick", {
            mes: date.format("MM"),
            year: date.format("YYYY"),
            sitio: ev.target.dataItem.component._settings.valueYField,
        });
    }); */

    legend.data.push(series);
}

onMounted(() => {
    Echo.channel("garphSitio").listen("SheGraph", () => {
        root.container.children.clear();
        chart.remove("cursor");

        chart = root.container.children.push(
            am5xy.XYChart.new(root, {
                panX: false,
                panY: false,
                wheelX: "panX",
                wheelY: "zoomX",
                paddingLeft: 0,
                layout: root.verticalLayout,
            })
        );

        setColorTheme();

        legend = chart.children.push(
            am5.Legend.new(root, {
                centerX: am5.p50,
                x: am5.p50,
            })
        );

        getDataTable();
    });
});

onUnmounted(() => {
    Echo.leave("garphSitio");
});
</script>
<template>
    <div ref="graphLines" class="w-full h-[45vh] relative">
        <div
            class="absolute z-10 flex items-center justify-end w-full gap-2 p-4 rounded-full"
        >
            <SelectInput v-model="filters.sitio">
                <option value="1">GDL</option>
                <option value="2">CDU</option>
                <option value="3">Procter</option>
                <option value="4">Quantum</option>
                <option value="5">Scania</option>
                <option value="6">Corporativo</option>
            </SelectInput>
        </div>
    </div>
</template>

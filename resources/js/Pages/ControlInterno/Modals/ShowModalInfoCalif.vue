<script setup>
import { ref, onMounted, onUpdated, computed, reactive, watch } from "vue";
import { useForm } from "@inertiajs/inertia-vue3";
import DialogModal from "@/Components/DialogModal.vue";
import * as am4core from "@amcharts/amcharts4/core";
import * as am4charts from "@amcharts/amcharts4/charts";
import am4themes_animated from "@amcharts/amcharts4/themes/animated";

const emit = defineEmits(["close"]);
const props = defineProps({
    rubros:Object,
    valores:Object,
    mes:String,
    proceso:String
});

const close = () => {
    emit('close');
}

const arregloGrafica = computed(() => {
   
});

 onUpdated (() => {
   // Themes begin
   am4core.useTheme(am4themes_animated);
   // Themes end
   // Create chart instance
   var chart = am4core.create("chartdiv3", am4charts.XYChart);
   chart.scrollbarX = new am4core.Scrollbar();
   // Add data



   chart.data = props.valores;
   /*[{
     "rubro_nombre": "USA",
     "valor": 3025
   }, {
     "country": "China",
     "visits": 1882
   }, {
     "country": "Japan",
     "visits": 1809
   }, {
     "country": "Germany",
     "visits": 1322
   }, {
     "country": "UK",
     "visits": 1122
   }, {
     "country": "France",
     "visits": 1114
   }, {
     "country": "India",
     "visits": 984
   }, {
     "country": "Spain",
     "visits": 711
   }, {
     "country": "Netherlands",
     "visits": 665
   }, {
     "country": "Russia",
     "visits": 580
   }, {
     "country": "South Korea",
     "visits": 443
   }, {
     "country": "Canada",
     "visits": 441
   }];
   */
   
   // Create axes
   var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
   categoryAxis.dataFields.category = "rubro_nombre";
   categoryAxis.renderer.grid.template.location = 0;
   categoryAxis.renderer.minGridDistance = 30;
   categoryAxis.renderer.labels.template.horizontalCenter = "right";
   categoryAxis.renderer.labels.template.verticalCenter = "middle";
   categoryAxis.renderer.labels.template.rotation = 270;
   categoryAxis.tooltip.disabled = true;
   categoryAxis.renderer.minHeight = 110;
   
   var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
   valueAxis.renderer.minWidth = 50;
   
   // Create series
   var series = chart.series.push(new am4charts.ColumnSeries());
   series.sequencedInterpolation = true;
   series.dataFields.valueY = "valor";
   series.dataFields.categoryX = "rubro_nombre";
   series.tooltipText = "[{categoryX}: bold]{valueY}[/]";
   series.columns.template.strokeWidth = 0;
   
   series.tooltip.pointerOrientation = "vertical";
   
   series.columns.template.column.cornerRadiusTopLeft = 10;
   series.columns.template.column.cornerRadiusTopRight = 10;
   series.columns.template.column.fillOpacity = 0.8;
   
   // on hover, make corner radiuses bigger
   var hoverState = series.columns.template.column.states.create("hover");
   hoverState.properties.cornerRadiusTopLeft = 0;
   hoverState.properties.cornerRadiusTopRight = 0;
   hoverState.properties.fillOpacity = 1;
   
   series.columns.template.adapter.add("fill", function(fill, target) {
     return chart.colors.getIndex(target.dataItem.index);
   });
   
   // Cursor
   chart.cursor = new am4charts.XYCursor();
})

</script>
<style>
#chartdiv3 {
  width: 100%;
  height: 500px;
}
</style>
<template>
    <DialogModal :show="show" maxWidth="2xl" @close="close()">
        <template #title>
            <div class="grid grid-cols-2">
             <div class="flex justify-start">
                <h2 class="text-lg tracking-widest uppercase font-extralight">
                   Calificaciones en el mes de <span class="font-bold">{{ mes }}</span> del proceso <span class="font-bold">{{ proceso }}</span>
                </h2>
            </div>
            <div class="flex justify-end">
               <button @click="close" class="bg-[#EC2944] pl-4 pr-4  text-white rounded-full">
                  <svg xmlns="http://www.w3.org/2000/svg" width="11.606" height="11.606" viewBox="0 0 11.606 11.606">
                   <g id="Grupo_581" data-name="Grupo 581" transform="translate(-15.921 -7.94)">
                     <line id="Línea_4" data-name="Línea 4" x2="12.413" transform="translate(17.335 18.132) rotate(-45)" fill="none" stroke="#fff" stroke-linecap="round" stroke-width="2"/>
                     <line id="Línea_5" data-name="Línea 5" y2="12.413" transform="translate(17.335 9.355) rotate(-45)" fill="none" stroke="#fff" stroke-linecap="round" stroke-width="2"/>
                   </g>
                 </svg>
               </button>
            </div>
          </div>
        </template>
        <template #content> 
            <div class="hello" id="chartdiv3" ref="chartdiv3">
            </div>
        </template>
    </DialogModal>
</template>

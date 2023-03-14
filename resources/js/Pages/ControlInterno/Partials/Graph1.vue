<script>
import * as am4core from "@amcharts/amcharts4/core";
import * as am4charts from "@amcharts/amcharts4/charts";
import am4themes_animated from "@amcharts/amcharts4/themes/animated";

am4core.useTheme(am4themes_animated);

let chart = null;

export default {
  props: {
        calificaciones: Array,
    },
  watch:
  {
    calificaciones(newData, oldData) 
    {
        chart.data = newData; 
    }
  },
  mounted() {
    // Themes begin
am4core.useTheme(am4themes_animated);
// Themes end

// Create chart instance
chart = am4core.create("chartdiv", am4charts.XYChart);

// Add data
chart.data = this.calificaciones;

// Create category axis
var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
categoryAxis.dataFields.category = "mes";
categoryAxis.renderer.opposite = true;

// Create value axis
var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
valueAxis.renderer.inversed = false;
valueAxis.title.text = "Valor";
valueAxis.renderer.minLabelPosition = 0.01;

// Create series
var series1 = chart.series.push(new am4charts.LineSeries());
series1.dataFields.valueY = "promedio";
series1.dataFields.categoryX = "mes";
series1.name = "Promedio";
series1.bullets.push(new am4charts.CircleBullet());
series1.tooltipText = "{name} En {categoryX}: {valueY}";
series1.legendSettings.valueText = "{valueY}";
series1.visible  = false;


// Add chart cursor
chart.cursor = new am4charts.XYCursor();
chart.cursor.behavior = "zoomY";


let hs1 = series1.segments.template.states.create("hover")
hs1.properties.strokeWidth = 5;
series1.segments.template.strokeWidth = 1;


// Add legend
chart.legend = new am4charts.Legend();
chart.legend.itemContainers.template.events.on("over", function(event){
var segments = event.target.dataItem.dataContext.segments;
segments.each(function(segment){
  segment.isHover = true;
})
})

chart.legend.itemContainers.template.events.on("out", function(event){
var segments = event.target.dataItem.dataContext.segments;
segments.each(function(segment){
  segment.isHover = false;
})
})
  },


  beforeDestroy() {
    if (this.chart) {
      this.chart.dispose();
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.hello {
  width: 100%;
  height: 500px;
}
</style>
<template>
  <div class="hello" id="chartdiv" ref="chartdiv">
  </div>
</template>

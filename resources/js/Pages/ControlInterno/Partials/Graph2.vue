<script>
import * as am4core from "@amcharts/amcharts4/core";
import * as am4charts from "@amcharts/amcharts4/charts";
import am4themes_animated from "@amcharts/amcharts4/themes/animated";
import ShowModalInfoCalif from '../Modals/ShowModalInfoCalif.vue'

am4core.useTheme(am4themes_animated);

let chart = null;
let newProcesos = null;
export default {
  data() {
    return {
             modalInfo : false,
             rubros: [],
             valores:[],
             proceso:null,
             mes:null
           }
  },
  props: {
        parametros: Array,
        procesos:Array
    },
  watch:
  {
    parametros(newData, oldData) 
    {
        chart.data = newData; 
    }
    ,
    procesos(newData, oldData) 
    {
       newProcesos = [];
       newProcesos = newData;
       //console.log(newProcesos);
       for (let index2 = 0; index2 < oldData.length; index2++) 
       {
          chart.series.pop();
       }

       for (let index = 0; index < newProcesos.length; index++)
        {
           let proceso = newProcesos[index];
           let funcion = this.openInfoModal;
           this.createSeries(proceso.nombre, proceso.nombre, funcion);
        }

    }
  },
  mounted() {
    // Themes begin
am4core.useTheme(am4themes_animated);
// Themes end

// Create chart instance
chart = am4core.create("chartdiv2", am4charts.XYChart);

// Add data
chart.data = this.parametros;

// Create category axis
var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
categoryAxis.dataFields.category = "mes";
categoryAxis.renderer.opposite = true;

// Create value axis
var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
valueAxis.renderer.inversed = false;
valueAxis.title.text = "Valor";
valueAxis.renderer.minLabelPosition = 0.01;

//series dinamicas
newProcesos = [];
newProcesos = this.procesos;
for (let index = 0; index < newProcesos.length; index++)
 {
    let proceso = newProcesos[index];
    let funcion = this.openInfoModal;
    this.createSeries(proceso.nombre, proceso.nombre, funcion);
}

// Add chart cursor
chart.cursor = new am4charts.XYCursor();
chart.cursor.behavior = "zoomY";

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

  methods:
  {
     openInfoModal(categoria, mes)
     {
         //console.log(categoria);
         this.proceso = categoria;
         this.mes = mes;
         axios.get('/recuperarRubro/'+categoria+'/'+mes).then((response)=> 
          {
                console.log(response.data);
                this.rubros = response.data[0];
                this.valores = response.data[1];
          });
         this.modalInfo = true;
     },
     closeInfoModal ()
     {
        this.modalInfo = false;
        this.rubros = [];
        this.valores = [];
     },
     createSeries(field, name , funcion)
     {
         var series1 = chart.series.push(new am4charts.LineSeries());
         series1.dataFields.valueY = field;
         series1.dataFields.categoryX = "mes";
         series1.name = name;
         var bullet = series1.bullets.push(new am4charts.CircleBullet());
         bullet.events.on("hit", function(ev)
         {
            //console.log(ev.target.dataItem.component.dataFields.valueY);
            //console.log(ev.target.dataItem.categoryX)
            funcion(ev.target.dataItem.component.dataFields.valueY, ev.target.dataItem.categoryX );
         });
         series1.tooltipText = "{name} En {categoryX}: {valueY}";
         series1.legendSettings.valueText = "{valueY}";
         series1.visible  = false;
   
   return series1;
     }
  },

  beforeDestroy() {
    if (this.chart) {
      this.chart.dispose();
    }
  },

  components: {ShowModalInfoCalif}
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
  <div class="hello" id="chartdiv2" ref="chartdiv">
  </div>
  <ShowModalInfoCalif :rubros="rubros" :valores="valores" :mes="mes" :proceso="proceso" @close="closeInfoModal" :show="modalInfo" />
</template>

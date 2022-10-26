<script setup>
import { Inertia } from '@inertiajs/inertia'
import { onMounted, reactive, ref, watch } from 'vue';
//import { puntos, siguiente } from "../../utils/funcionCarrusel";

var props = defineProps({
    noticias:Object
});

  var actual = 0;
  const puntos = (n) =>
  {
     var ptn = document.getElementsByClassName("punto");
  	 for(let i = 0; i<ptn.length; i++)
     {
    	if(ptn[i].className.includes("activo"))
        {
    	   ptn[i].className = ptn[i].className.replace("activo", "");
  		   break;
		}
	 }
  		ptn[n].className += " activo";
	}
  	 const mostrar = (n) =>
     {
    	var imagenes = document.getElementsByClassName("imagen");
        console.log(imagenes);
  		for(let i = 0; i< imagenes.length; i++)
        {
    	  if(imagenes[i].className.includes("actual"))
          {
    		imagenes[i].className = imagenes[i].className.replace("actual", "");
  			break;
		  }
		}
  		actual = n;
  		imagenes[n].className += " actual";
  		puntos(n);
	}

  	 const  siguiente = () =>{
    		actual++;
    		if(actual > 2){
    			actual = 0;
		}
  		mostrar(actual);
	}

  	var velocidad = 2000;
    //setInterval("siguiente()", velocidad);
  	 
</script>
<template>
    
  <div class="contenedor">
       
        <div v-for="noticia in noticias" :key="noticia.id">

            <div class="imagen actual" v-if="noticia.id == 1" >
				<img :src="noticia.image" />
			</div>

            <div class="imagen" >
				<img :src="noticia.image" />
			</div>


        </div>

		<div class="puntos" >
			<span v-for="noticia in noticias" :key="noticia.id" class="punto " @click="mostrar(noticia.id);"></span>
		</div>
	
  </div>
</template>
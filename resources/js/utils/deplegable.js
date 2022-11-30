//Referencias DOM
const bntMenu = document.querySelector('.icono1');
const btnColab = document.querySelector('.icono2');
const dist = document.querySelector('.contenedor-grid');
const svg1 = document.querySelector('#svg1');
const svg2 = document.querySelector('#svg2');
const md = document.querySelector('.MD');
const contBtn = document.querySelector('.contenedor-btns');

//Crear Elementos
const contM = document.createElement('DIV');
const contC = document.createElement('DIV');

//Condicionales
let btn1 = false;
let btn2 =  false;
let i = false;

//Cargar al iniciar el sitio
document.addEventListener('DOMContentLoaded',() =>{
    initApp();
});
export function initApp(){
    menu();
    colab();
}

//Desplegar menu
function menu(){
    bntMenu.addEventListener('mouseover', () =>{
        if(i){
            if(btn1 === false){
                 //Boton Menu
                abrirMenu();
                //Boton colaborador
                btnColab.classList.remove('btn-press');
                contC.remove();
                //Condicional
                btn2 = false;
            }
        }
        else{
            //Template Grid
            btnCerrar();
            grid();
            //Boton Menu
            abrirMenu();
            //Boton colaborador
            btnColab.classList.add('btn-mini');
        }
    });
}
function colab(){
    btnColab.addEventListener('mouseover', () =>{
        if(i){
            if(btn2 === false){
                //Boton Colaborador
                abrirColab();
                //Boton Menu
                bntMenu.classList.remove('btn-press');
                contM.remove();
                //Condicionales
                btn1 = false;
            }
        }
        else{
            //Template grid
            btnCerrar();
            grid();
            //Boton Colaborador
            abrirColab();
            //Boton Menu
            bntMenu.classList.add('btn-mini');
        }
    });
}

//Boton Menu
function abrirMenu(){
    //Contenido
    contenidoMenu(true);
    //Modificaciones
    bntMenu.classList.add('btn-press', 'btn-mini');
    svg1.classList.add('svg-press');
    svg2.classList.remove('svg-press');
    //Condicionales
    i = true;
    btn1 = true;
}
function cerrarMenu(){
    //Template Grid
    contenidoMenu(false);
    grid();
    //Boton Menu
    bntMenu.classList.remove('btn-press', 'btn-mini');
    svg1.classList.remove('svg-press');
    //Boton Colaborador
    btnColab.classList.remove('btn-mini');
    svg2.classList.remove('svg-press');
    //Conicionales
    i = false;
    btn1 = false;
}
function contenidoMenu(a = false){
    if(a){
        contM.classList.add('menu')
        contM.innerHTML = `
            <h3 class="tituloMenu">Menu del Dia</h3>
            <p class="fechaMenu">22-12-22</p>
            <p class="desayuno">
            <span class="desayuno desayunoT">Desayuno. </span>Huevos con nopal en salsa verde, Picadillo, chilaquiles, rijoles, 3 tortillas y café.
            </p>
            <p class="desayuno">
            <span class="desayuno desayunoT">Comida. </span>Bistec de cerdo en salsa guajillo, spagueti verde, sopa de lentejas, nopal asado, 3 Tortillas y agua de jamaica
            </p>
        `;
        md.appendChild(contM);
    }
    else{
        contM.remove();
    }
}

//Boton Colaborador
function abrirColab(){
    //Contenido
    contenidoColab(true);
    //Modifciadores
    btnColab.classList.add('btn-press', 'btn-mini');
    svg2.classList.add('svg-press');
    svg1.classList.remove('svg-press');
    //Condicionales
    i = true;
    btn2 = true;
}
function cerrarColab(){
    //Template Grid
    contenidoColab(false);
    grid();
    //Boton Colaborador
    btnColab.classList.remove('btn-press', 'btn-mini');
    svg2.classList.remove('svg-press');
    //Boton Menu
    bntMenu.classList.remove('btn-mini');
    svg1.classList.remove('svg-press');
    //Condicionales
    i = false;
    btn2 = false;
}
function contenidoColab(a = false){
    if(a){
        contC.classList.add('colaborador');
        contC.innerHTML = `
            <div>
                <h3 class="tituloMenu">Nuestro Colaborador</h3>
                <p class="desayuno">
                <span class="desayuno desayunoT">Nombre. </span>Roberto Saul Pacheco Rivera.
                </p>
                <p class="desayuno">
                <span class="desayuno desayunoT">Puesto. </span>Jefe de Area
                </p>
                <p class="desayuno">
                <span class="desayuno desayunoT">Area. </span>Ingenieria
                </p>
            </div>
            <div class="nomina">
                <h3 class="tituloMenu">Recibos de Nomina</h3>
                <div class="mes">
                    <p class="desayuno"><span class="desayuno desayunoT">1- Enero 2022 </span></p>
                    <p class="desayuno"><span class="desayuno desayunoT">2- Enero 2022 </span></p>
                </div>
                <div class="mes">
                    <p class="desayuno"><span class="desayuno desayunoT">1- Febrero 2022 </span></p>
                    <p class="desayuno"><span class="desayuno desayunoT">2- Febrero 2022 </span></p>
                </div>
            </div>
        `;
        md.appendChild(contC);
    }
    else{
        contC.remove();
    }
}

function btnCerrar(){
    const btnClose = document.createElement('DIV');
    btnClose.classList.add('btn-mini', 'icono', 'btnCerrar');
    contBtn.classList.add('contenedor-btns__abierto');
    btnClose.innerHTML = `
        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-narrow-right" viewBox="0 0 24 24" "fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <line x1="5" y1="12" x2="19" y2="12" />
            <line x1="15" y1="16" x2="19" y2="12" />
            <line x1="15" y1="8" x2="19" y2="12" />
        </svg>
    `;
    contBtn.appendChild(btnClose);
    btnClose.addEventListener('click', () =>{
        btnClose.remove();
        contBtn.classList.remove('contenedor-btns__abierto')
        if(btn2){
            cerrarColab();
        }
        else{
            cerrarMenu();
        }
    });
}

//Template Grid
function grid (){
    dist.classList.toggle('cerrado');
    dist.classList.toggle('abierto');
    md.classList.toggle('MD-open');
}
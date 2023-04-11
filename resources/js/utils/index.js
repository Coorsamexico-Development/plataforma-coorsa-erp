import axios from "axios";

export function ObtenerCurp(_nombre, _apellidoPaterno, _apellidoMaterno, _fechaNacimiento) {

    const vocales = ['A', 'E', 'I', 'O', 'U'];
    //primera letra del primer apellido
    const LETTERS_APELLIDO_PATERNO = _apellidoPaterno.split('')
    const PRIMERALETRAPRIMERAPELLIDO = LETTERS_APELLIDO_PATERNO.length > 0 ? LETTERS_APELLIDO_PATERNO[0] : '';

    //primera vocal del apellido paterno considerando la primer letra
    let VOCALPRIMERAPELLIDO = LETTERS_APELLIDO_PATERNO.find((letter, index) => vocales.includes(letter) && index > 0);
    VOCALPRIMERAPELLIDO = (VOCALPRIMERAPELLIDO === undefined) ? '' : VOCALPRIMERAPELLIDO;


    //primera letra del segundo apellido
    const LETTERS_APELLIDO_MATERNO = _apellidoMaterno.split('')
    const PRIMERALETRASEGUNDOAPELLIDO = LETTERS_APELLIDO_MATERNO.length > 0 ? LETTERS_APELLIDO_MATERNO[0] : '';

    //primera letra del primer nombre
    let PRIMERALETRAPRIMERNOMBRE = '';
    const arregloNombres = (_nombre + ' ').split(' ');

    if (arregloNombres.length > 1) {
        let arrageloNombre = []
        if (arregloNombres[0] === 'MARIA' || arregloNombres[0] === 'JOSE') {
            arrageloNombre = arregloNombres[1].split('')
        } else {
            arrageloNombre = arregloNombres[0].split('')
        }
        if (arrageloNombre.length > 0) {
            PRIMERALETRAPRIMERNOMBRE = arrageloNombre[0]
        }
    }
    //fecha de nacimiento
    const arregloFechaNacimiento = _fechaNacimiento.split('-');
    let FECHANACIMIENTO = '';
    if (arregloFechaNacimiento.length > 2) {
        const year = arregloFechaNacimiento[0].substring(2, 4);

        const mes = arregloFechaNacimiento[1];

        const dia = arregloFechaNacimiento[2];

        FECHANACIMIENTO = (year + mes + dia);
    }
    return PRIMERALETRAPRIMERAPELLIDO + VOCALPRIMERAPELLIDO + PRIMERALETRASEGUNDOAPELLIDO + PRIMERALETRAPRIMERNOMBRE + FECHANACIMIENTO;
}

const DevolverRegex = (_regex) => {
    if (_regex === 'numeros') return /[0-9]|\./;
    else if (_regex === 'fecha') return /^\d{4}\-(0[1-9]|1[012])\-(0[1-9]|[12][0-9]|3[01])$/;
    else if (_regex === 'correo') return /.*/;
    else if (_regex === 'letrasnumeros') return /[a-zA-Z0-9 ]$/;
}

//_regex: Expresión regular que quieras someter a revisión
//_event: Evento que está escuchando la acción en donde estás mandando a llamar esta función
//_cantidadCaracteres: Cantidad de caracteres que están permitidos
export function ValidarInput(_regex, _event, banderaCantidad, _cantidadCaracteres = 0) {
    let llave = '';
    let banderaBackspace = false;
    let banderaCopiarPegar = false;

    if (_event.type === 'paste') {
        banderaCopiarPegar = true;
        llave = _event.clipboardData.getData('text/plain');
    }
    else {
        llave = _event.keyCode || _event.which;
        if (llave === 8) banderaBackspace = true;
        llave = String.fromCharCode(llave);
    }

    if (!DevolverRegex(_regex).test(llave) && !banderaBackspace) {
        _event.returnValue = false;
        _event.preventDefault();
    }

    if (banderaCantidad) {
        if (!banderaCopiarPegar && !banderaBackspace) {
            if ((_event.target.value.length + 1) > _cantidadCaracteres) {
                _event.returnValue = false;
                _event.preventDefault();
            }
        }
    }
}



export function valImageFile(fileName) {
    const re = /(.*?).(jpg|jpeg|png|gif|JPG|JPEG|PNG)$/;
    return re.test(fileName);
}

//Devuelve en formato de número el mes del año que revisa con base en la información que retorna el objeto Date de JavaScript
export function HandlerMes(_mes) {
    switch (_mes) {
        case 'Jan':
            return '01'
            break;
        case 'Feb':
            return '02'
            break;
        case 'Mar':
            return '03'
            break;
        case 'Apr':
            return '04'
            break;
        case 'May':
            return '05'
            break;
        case 'Jun':
            return '06'
            break;
        case 'Jul':
            return '07'
            break;
        case 'Aug':
            return '08'
            break;
        case 'Sep':
            return '09'
            break;
        case 'Oct':
            return '10'
            break;
        case 'Nov':
            return '11'
            break;
        case 'Dec':
            return '12'
            break;
    }
}

export function HandlerBuscador(tipo, valor) {
    let final = '';
    if (valor === '') final = tipo + "-asc";
    else if (valor === (tipo + "-asc")) final = tipo + "-desc";
    else if (valor === (tipo + "-desc")) final = "";
    else final = tipo + "-asc";
    return final;
}

export async function HandlerQueryBuscador(_cadenaBusqueda, _ballesta, _tipoBusqueda) {
    let response = null;

    if (_tipoBusqueda !== '') response = await axios.get(_cadenaBusqueda + `/${_ballesta}?orden=${_tipoBusqueda}`);
    else response = await axios.get(_cadenaBusqueda + `/${_ballesta}`);

    return { _ballesta, response }
}

export function AtraparError(error) {
    if (error.response) {
        // The request was made and the server responded with a status code
        // that falls out of the range of 2xx
        console.log(error.response.data);
        console.log(error.response.status);
        console.log(error.response.headers);
    } else if (error.request) {
        // The request was made but no response was received
        // `error.request` is an instance of XMLHttpRequest in the browser and an instance of
        // http.ClientRequest in node.js
        console.log(error.request);
    } else {
        // Something happened in setting up the request that triggered an Error
        console.log('Error', error.message);
    }
    console.log(error.config);
}

export const regexNumeros = /[0-9]|\./;
export const regexFecha = /^\d{4}\-(0[1-9]|1[012])\-(0[1-9]|[12][0-9]|3[01])$/;
export const regexCorreo = /(?:[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*|"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:(2(5[0-5]|[0-4][0-9])|1[0-9][0-9]|[1-9]?[0-9]))\.){3}(?:(2(5[0-5]|[0-4][0-9])|1[0-9][0-9]|[1-9]?[0-9])|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])/;
export const regexLetrasNumeros = /^[a-zA-Z0-9 ]*$/;



export function removeClass(ele, cls) {
    if (hasClass(ele, cls)) {
        var reg = new RegExp('(\\s|^)' + cls + '(\\s|$)');
        ele.className = ele.className.replace(reg, ' ');
    }
}

export function selectElement(ele) {
    removeClass(ele, 'hover:bg-gray-200');
    removeClass(ele, 'text-gray-500');
    ele.classList.add("bg-gray-500");
    ele.classList.add("text-white");
    ele.querySelector('td:first-child').style.backgroundColor = "#6B7280";
}

export function removeSelect(ele) {
    removeClass(ele, 'bg-gray-500');
    removeClass(ele, 'text-white');
    ele.classList.add("hover:bg-gray-200");
    ele.classList.add("text-gray-500");
    ele.querySelector('td:first-child').removeAttribute("style")
}

function hasClass(ele, cls) {
    return ele.className.match(new RegExp('(\\s|^)' + cls + '(\\s|$)'));
}

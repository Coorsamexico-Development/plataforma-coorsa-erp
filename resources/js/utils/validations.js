
export function valEmail(email) {
    const re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}


export function valImageFile(fileName) {
    const re = /(.*?).(jpg|jpeg|png|gif|JPG|JPEG|PNG)$/;
    return re.test(fileName);
}

export function valFileEvi(fileName) {
    const re = /(.*?).(pdf|jpg|jpeg|png|gif|JPG|JPEG|PNG)$/;
    return re.test(fileName);
}
export function valExcelFile(fileName) {
    const re = /(.*?).(cvs|xlsx|xls)$/;
    return re.test(fileName);
}


export function valNumber(number) {
    const re = /[+-]?[0-9]+(\.[0-9]+)?$/;
    return re.test(number);
}

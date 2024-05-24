export function formatMoney(e) {
    const mxnFormat = new Intl.NumberFormat("es-MX", {
        style: "currency",
        currency: "MXN",
    });
    e = mxnFormat.format(e);
    return e;
}

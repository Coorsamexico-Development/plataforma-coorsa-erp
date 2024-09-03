export function formatPhoneNumber(phoneNumberString) {
    const length = phoneNumberString.length;
    var cleaned = ("" + phoneNumberString).replace(/\D/g, "");
    if (length <= 10) var match = cleaned.match(/^(\\d{3})(\\d{3})(\\d{4})$/);
    else if (length <= 12)
        var match = cleaned.match(/^(\d{1})?(\d{3})(\d{3})(\d{4})$/);
    else if (length <= 13)
        var match = cleaned.match(/^(\d{2})?(\d{3})(\d{3})(\d{4})$/);
    else if (length <= 14)
        var match = cleaned.match(/^(\d{3})?(\d{3})(\d{3})(\d{4})$/);
    if (match) {
        var intlCode = match[1] ? `+${match[1]}` : "";
        return [intlCode, " (", match[2], ") ", match[3], "-", match[4]].join(
            ""
        );
    }
    return null;
}

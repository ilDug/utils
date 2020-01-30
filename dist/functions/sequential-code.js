/**
 * genera un codice casuale sempre sequenziale in base al timestamp
 * @param length lunghezz edlla stringe,  al MAX 8 caratteri
 * @param prefix stringa prefisso
 * @param suffix stringa suffisso
 */
export var sequentialCode = function (length, prefix, suffix) {
    var max = 8;
    if (length > max)
        throw new Error("la lunghezza massima consentita \u00E8 " + max);
    if (length <= 0)
        throw new Error("la lunghezza deve essere maggiore di 0");
    length = length > max ? max : (length < 0 ? 0 : length);
    var l = max - length;
    return prefix + (new Date().getTime().toString(36).substring(l)) + suffix;
};
/**
 * genera un codice bassato sul timestamp di 7 caratteri anteceduto da ilnumero dell'anno
 * @param prefix stringa prefisso
 * @param suffix stringa suffisso
 */
export var annualCode = function (prefix, suffix) {
    var pre = prefix ? prefix + "." : '';
    pre += new Date().getFullYear().toString().substr(2) + ".";
    var post = suffix ? "." + suffix : '';
    return sequentialCode(7, pre, post);
};

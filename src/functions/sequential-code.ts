
/**
 * genera un codice casuale sempre sequenziale in base al timestamp
 * @param length lunghezz edlla stringe,  al MAX 8 caratteri
 * @param prefix stringa prefisso
 * @param suffix stringa suffisso
 */
export const sequentialCode = function(length: number, prefix?: string, suffix?: string){
    const max = 8;

    if (length > max) throw new Error(`la lunghezza massima consentita è ${max}`);
    if (length <= 0) throw new Error(`la lunghezza deve essere maggiore di 0`);

    length = length > max ? max : (length < 0 ? 0 : length)
    const l: number = max - length;
    return prefix + (new Date().getTime().toString(36).substring(l)) + suffix;
}

/**
 * genera un codice bassato sul timestamp di 7 caratteri anteceduto da ilnumero dell'anno
 * @param prefix stringa prefisso
 * @param suffix stringa suffisso
 */
export const annualCode = (prefix: string, suffix?: string): string => {
    let pre = prefix ? `${prefix}.` : '';
    pre += `${new Date().getFullYear().toString().substr(2)}.`;

    let post = suffix ? `.${suffix}` : '';

    return sequentialCode(7, pre, post)
}

/**
 * genera un codice casuale sempre sequenziale in base al timestamp
 * @param length lunghezz edlla stringe,  al MAX 8 caratteri
 */
export const sequentialCode = (length: number) => {
    const max = 8;

    if (length > max) throw new Error(`la lunghezza massima consentita è ${max}`);
    if (length <= 0) throw new Error(`la lunghezza deve essere maggiore di 0`);

    length = length > max ? max : (length < 0 ? 0 : length)
    const l: number = max - length;
    return new Date().getTime().toString(36).substring(l);
}

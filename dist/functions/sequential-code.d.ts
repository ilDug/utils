/**
 * genera un codice casuale sempre sequenziale in base al timestamp
 * @param length lunghezz edlla stringe,  al MAX 8 caratteri
 * @param prefix stringa prefisso
 * @param suffix stringa suffisso
 */
export declare const sequentialCode: (length: number, prefix?: string, suffix?: string) => string;
/**
 * genera un codice bassato sul timestamp di 7 caratteri anteceduto da ilnumero dell'anno
 * @param prefix stringa prefisso
 * @param suffix stringa suffisso
 */
export declare const annualCode: (prefix: string, suffix?: string) => string;

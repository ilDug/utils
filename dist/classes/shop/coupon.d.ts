export declare class Promo {
    constructor(c: any);
    coupon: string;
    /** nome della campagna promozionale  */
    campaign: string;
    /** tipologia della prompzione ( 3x2, buono, sconto) */
    type: string;
    /** se il codice passato ha ritrovato una corrispondenza corretta tra le promozioni in corso */
    valid: boolean;
    /** se la promozione seppur trovata, non è più valida */
    expired: boolean;
    /** valore del buono */
    value: any;
    /** etichetta descrittiva della promozione */
    label: string;
    /** data di partenza della promozione */
    since: number;
    /** data di scadenza della promozione */
    until: number;
}

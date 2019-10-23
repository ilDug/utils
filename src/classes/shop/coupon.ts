export class Promo{

    constructor(c:any){
        /** assegna le proprietà */
        Object.assign(this, c);
    }

    public coupon: string;

    /** nome della campagna promozionale  */
    public campaign: string;

    /** tipologia della prompzione ( 3x2, buono, sconto) */
    public type: string;

    /** se il codice passato ha ritrovato una corrispondenza corretta tra le promozioni in corso */
    public valid : boolean;

    /** se la promozione seppur trovata, non è più valida */
    public expired: boolean;

    /** valore del buono */
    public value: any;

    /** etichetta descrittiva della promozione */
    public label:string;

    /** data di partenza della promozione */
    public since: number;

    /** data di scadenza della promozione */
    public until: number;

}

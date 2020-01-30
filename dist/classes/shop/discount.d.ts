export declare class Discount {
    constructor(d: Partial<Discount>);
    /** valore in euro da detrarre all'elemento applicato  */
    get bonus(): number;
    set bonus(b: number);
    private _bonus;
    /**
     * imposta il bonus
     */
    setBonus(b: number): Discount;
    /** valore percentuale da sottrarre all'elemento applicato */
    get rate(): number;
    set rate(r: number);
    private _rate;
    /**
     * imposta lo socnto percentuale
     */
    setRate(r: number): Discount;
    /** etichetta da mostrare nella pagina del prodotto */
    label: string;
}

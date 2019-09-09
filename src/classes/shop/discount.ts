export class Discount {

    constructor(d: Partial<Discount>) {
        Object.assign(this, d);
    }

    /** valore in euro da detrarre all'elemento applicato  */
    get bonus():number{ return this._bonus; }
    set bonus(b: number) { this.setBonus(b) }
    private _bonus: number = 0;

    /**
     * imposta il bonus
     */
    public setBonus(b: number): Discount {
        /** azzera prima eventuale sconto percentuale esistente */
        this._rate = 0;
        this._bonus = b;
        return this;
    }
    


    
    /** valore percentuale da sottrarre all'elemento applicato */
    get rate(): number { return this._rate; }
    set rate(r: number) { this.setRate(r) }
    private _rate: number = 0;

    /**
     * imposta lo socnto percentuale
     */
    public setRate(r: number): Discount {
        /** azzera prima eventuale bonus esistente */
        this._bonus = 0;
        /** limita il valore dello sconto al 100% */
        this._rate = r >= 1 ? 1 : r;
        return this;
    }


    

    /** etichetta da mostrare nella pagina del prodotto */
    public label: string = null;

    
}


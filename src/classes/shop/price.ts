import { Discount } from "./discount";

export class Price {

    constructor(p: Partial<Price>) {
        p.discount = p.discount ? new Discount(p.discount) : new Discount({});
        Object.assign(this, p);
    }

    /** valore comprensivo di iva */
    public price: number = null;

    /** aliquota IVA preimpostata al 22% */
    private vat: number = 0.22;

    /** non può esistere uno sconto sia come bonus sia come percentuale */
    public discount: Discount;


    /**
     * ritorna l'importo scontato e comprensivo di IVA
     */
    get amount(): number {
        //return ( (this.value - (this.discount.bonus / (1 + this.vat) ) ) * (1 - this.discount.rate) * (1 + this.vat) );
        return ((this.price - this.discount.bonus) * (1 - this.discount.rate));
    }


    /**
     * rutorna il valore al netto dell'IVa
     */
    get value(): number { return (this.amount / (1 + this.vat)); }




    /**
     * permette di impostare un bonus di sconto
     */
    public setDiscountBonus(b: number): Price {
        /** se il bonus è maggiore del valore dellItem allora lo limita allo stesso valore */
        b = b >= this.price ? this.price : b;
        this.discount.setBonus(b);
        return this;
    }


    /**
     * permette di impostare uno sconto percentuale
     */
    public setDiscountRate(r: number): Price {
        this.discount.setRate(r);
        return this;
    }
}

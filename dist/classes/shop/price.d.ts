import { Discount } from "./discount";
export declare class Price {
    constructor(p: Partial<Price>);
    /** valore comprensivo di iva */
    price: number;
    /** aliquota IVA preimpostata al 22% */
    private vat;
    /** non può esistere uno sconto sia come bonus sia come percentuale */
    discount: Discount;
    /** costo di spedizione da calcolare esternamente */
    shippingCost: number;
    /**
     * ritorna l'importo scontato e comprensivo di IVA
     */
    get amount(): number;
    /**
     * rutorna il valore scontato,  al netto dell'IVa
     */
    get value(): number;
    /**
     * permette di impostare un bonus di sconto
     */
    setDiscountBonus(b: number): Price;
    /**
     * permette di impostare uno sconto percentuale
     */
    setDiscountRate(r: number): Price;
}

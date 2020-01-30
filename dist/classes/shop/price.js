import { Discount } from "./discount";
var Price = /** @class */ (function () {
    function Price(p) {
        /** valore comprensivo di iva */
        this.price = null;
        /** aliquota IVA preimpostata al 22% */
        this.vat = 0.22;
        /** costo di spedizione da calcolare esternamente */
        this.shippingCost = 0;
        p.discount = p.discount ? new Discount(p.discount) : new Discount({});
        Object.assign(this, p);
    }
    Object.defineProperty(Price.prototype, "amount", {
        /**
         * ritorna l'importo scontato e comprensivo di IVA
         */
        get: function () {
            //return ( (this.value - (this.discount.bonus / (1 + this.vat) ) ) * (1 - this.discount.rate) * (1 + this.vat) );
            return ((this.price - this.discount.bonus) * (1 - this.discount.rate));
        },
        enumerable: true,
        configurable: true
    });
    Object.defineProperty(Price.prototype, "value", {
        /**
         * rutorna il valore scontato,  al netto dell'IVa
         */
        get: function () { return (this.amount / (1 + this.vat)); },
        enumerable: true,
        configurable: true
    });
    /**
     * permette di impostare un bonus di sconto
     */
    Price.prototype.setDiscountBonus = function (b) {
        /** se il bonus è maggiore del valore dellItem allora lo limita allo stesso valore */
        b = b >= this.price ? this.price : b;
        this.discount.setBonus(b);
        return this;
    };
    /**
     * permette di impostare uno sconto percentuale
     */
    Price.prototype.setDiscountRate = function (r) {
        this.discount.setRate(r);
        return this;
    };
    return Price;
}());
export { Price };

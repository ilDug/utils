var Discount = /** @class */ (function () {
    function Discount(d) {
        this._bonus = 0;
        this._rate = 0;
        /** etichetta da mostrare nella pagina del prodotto */
        this.label = null;
        Object.assign(this, d);
    }
    Object.defineProperty(Discount.prototype, "bonus", {
        /** valore in euro da detrarre all'elemento applicato  */
        get: function () { return this._bonus; },
        set: function (b) { this.setBonus(b); },
        enumerable: true,
        configurable: true
    });
    /**
     * imposta il bonus
     */
    Discount.prototype.setBonus = function (b) {
        /** azzera prima eventuale sconto percentuale esistente */
        this._rate = 0;
        this._bonus = b;
        return this;
    };
    Object.defineProperty(Discount.prototype, "rate", {
        /** valore percentuale da sottrarre all'elemento applicato */
        get: function () { return this._rate; },
        set: function (r) { this.setRate(r); },
        enumerable: true,
        configurable: true
    });
    /**
     * imposta lo socnto percentuale
     */
    Discount.prototype.setRate = function (r) {
        /** azzera prima eventuale bonus esistente */
        this._bonus = 0;
        /** limita il valore dello sconto al 100% */
        this._rate = r >= 1 ? 1 : r;
        return this;
    };
    return Discount;
}());
export { Discount };

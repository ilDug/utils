/**
 * code         [base36]            3 digits codice prodotto diverso per marca, modello, talgia, colore, materiale, ecc
 * batch        year [numbers]      2 digits
 * bathc num    [base 36]           2 digits
 * item num      [number]           variable digits starting from 1, sequential
 * id database  [number]            variable digit starting from 1
 *
 * PO.001.1901.1.1
 *
 */
var WarehouseArticle = /** @class */ (function () {
    function WarehouseArticle(wa) {
        this.articleId = null;
        this.productId = null;
        this.sku = null;
        this.batch = null;
        this.item = null;
        this.available = null;
        this.dateIn = null;
        this.dateOut = null;
        this.dateExpiry = null;
        this.status = "STOCK";
        Object.assign(this, wa);
    }
    Object.defineProperty(WarehouseArticle.prototype, "code", {
        /**
         * STOCK
         * EXPIRED
         * SOLD
         * GIFT
         * OBSOLETE
         * LOST
         */
        get: function () {
            return this.sku + "." + this.batch + "." + this.item + "." + this.articleId;
        },
        enumerable: true,
        configurable: true
    });
    return WarehouseArticle;
}());
export { WarehouseArticle };

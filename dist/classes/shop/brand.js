var __spreadArrays = (this && this.__spreadArrays) || function () {
    for (var s = 0, i = 0, il = arguments.length; i < il; i++) s += arguments[i].length;
    for (var r = Array(s), k = 0, i = 0; i < il; i++)
        for (var a = arguments[i], j = 0, jl = a.length; j < jl; j++, k++)
            r[k] = a[j];
    return r;
};
var Brand = /** @class */ (function () {
    function Brand(products) {
        this.products = [];
        this.products = __spreadArrays(products);
    }
    Object.defineProperty(Brand.prototype, "name", {
        get: function () {
            if (this.products.length === 0)
                return 'undefined unit name';
            return this.products[0].brand;
        },
        enumerable: true,
        configurable: true
    });
    Brand.prototype.addProduct = function (p) {
        this.products.push(p);
        return this;
    };
    return Brand;
}());
export { Brand };

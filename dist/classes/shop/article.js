var __extends = (this && this.__extends) || (function () {
    var extendStatics = function (d, b) {
        extendStatics = Object.setPrototypeOf ||
            ({ __proto__: [] } instanceof Array && function (d, b) { d.__proto__ = b; }) ||
            function (d, b) { for (var p in b) if (b.hasOwnProperty(p)) d[p] = b[p]; };
        return extendStatics(d, b);
    };
    return function (d, b) {
        extendStatics(d, b);
        function __() { this.constructor = d; }
        d.prototype = b === null ? Object.create(b) : (__.prototype = b.prototype, new __());
    };
})();
import { Product } from "./product";
/**
 * elemento componente l'ordine
 */
var Article = /** @class */ (function (_super) {
    __extends(Article, _super);
    function Article(a, orderId) {
        var _this = _super.call(this, a) || this;
        /**
         * ID del database WAREHOUSE
         * da assegnare quando l'ordine è gia stato eseguito, ma prima della spedizione
        */
        _this.articleIds = [];
        _this._quantity = 1;
        /** assegna le proprietà */
        Object.assign(_this, a);
        return _this;
    }
    Object.defineProperty(Article.prototype, "quantity", {
        get: function () { return this.quantity; },
        enumerable: true,
        configurable: true
    });
    ;
    Article.prototype.increment = function () { this._quantity = this._quantity + 1; };
    Article.prototype.decrement = function () { this._quantity = this._quantity > 0 ? this._quantity - 1 : 0; };
    return Article;
}(Product));
export { Article };

import { ReplaySubject } from "rxjs";
import { Cookie } from "../cookie";
var ShoppingCartItem = /** @class */ (function () {
    function ShoppingCartItem(item) {
        this._quantity = 1;
        Object.assign(this, item);
    }
    Object.defineProperty(ShoppingCartItem.prototype, "quantity", {
        get: function () { return this.quantity; },
        enumerable: true,
        configurable: true
    });
    ;
    ShoppingCartItem.prototype.increment = function () { this._quantity = this._quantity + 1; };
    ShoppingCartItem.prototype.decrement = function () { this._quantity = this._quantity > 0 ? this._quantity - 1 : 0; };
    return ShoppingCartItem;
}());
export { ShoppingCartItem };
var ShoppingCartService = /** @class */ (function () {
    function ShoppingCartService() {
        this.cookies = new Cookie();
        /** configurazioni iniziali del cookies */
        this.cookie = {
            name: "shca",
            path: "/",
        };
        /** subject emesso al momento della scrittura / eliminazione del cookie */
        this.cartEvent = new ReplaySubject(1);
        var sc = this.readCart();
        this.cartEvent.next(sc);
    }
    Object.defineProperty(ShoppingCartService.prototype, "cart$", {
        get: function () { return this.cartEvent.asObservable(); },
        enumerable: true,
        configurable: true
    });
    /** aggiunge un elemento al carrello e lo scrive nei cookies */
    ShoppingCartService.prototype.addItem = function (item) {
        var sc = this.readCart();
        var i = sc.findIndex(function (x) { return x.productId === item.productId; });
        if (i >= 0) {
            sc[i].increment();
        }
        else {
            sc.push(item);
        }
        this.writeCart(sc);
    };
    /** rimuove un elemento nel carrello e aggiurna il cookies */
    ShoppingCartService.prototype.removeItem = function (item) {
        var sc = this.readCart();
        var i = sc.findIndex(function (x) { return x.productId === item.productId; });
        if (i >= 0)
            sc.splice(i, 1);
        this.writeCart(sc);
    };
    /** legge i cookies e genera un carrello */
    ShoppingCartService.prototype.readCart = function () {
        var code = this.cookies.get(this.cookie.name);
        var sc = this.decode(code);
        return sc;
    };
    /** scrive il carrello nei cookies */
    ShoppingCartService.prototype.writeCart = function (sc) {
        var code = this.encode(sc);
        this.cookies.set(this.cookie.name, code);
        this.cartEvent.next(sc);
    };
    /** utility per la codifica dei file JSON in base64 */
    ShoppingCartService.prototype.encode = function (sc) {
        return btoa(JSON.stringify(sc));
    };
    /** utility per la decodifica del codice del cookies da Base64 in JSON */
    ShoppingCartService.prototype.decode = function (code) {
        var list = code ? JSON.parse(atob(code)) : [];
        if (list.length == 0)
            return [];
        var cart = [];
        for (var _i = 0, list_1 = list; _i < list_1.length; _i++) {
            var item = list_1[_i];
            cart.push((new ShoppingCartItem(item)));
        }
        return cart;
    };
    return ShoppingCartService;
}());
export { ShoppingCartService };

var __awaiter = (this && this.__awaiter) || function (thisArg, _arguments, P, generator) {
    function adopt(value) { return value instanceof P ? value : new P(function (resolve) { resolve(value); }); }
    return new (P || (P = Promise))(function (resolve, reject) {
        function fulfilled(value) { try { step(generator.next(value)); } catch (e) { reject(e); } }
        function rejected(value) { try { step(generator["throw"](value)); } catch (e) { reject(e); } }
        function step(result) { result.done ? resolve(result.value) : adopt(result.value).then(fulfilled, rejected); }
        step((generator = generator.apply(thisArg, _arguments || [])).next());
    });
};
var __generator = (this && this.__generator) || function (thisArg, body) {
    var _ = { label: 0, sent: function() { if (t[0] & 1) throw t[1]; return t[1]; }, trys: [], ops: [] }, f, y, t, g;
    return g = { next: verb(0), "throw": verb(1), "return": verb(2) }, typeof Symbol === "function" && (g[Symbol.iterator] = function() { return this; }), g;
    function verb(n) { return function (v) { return step([n, v]); }; }
    function step(op) {
        if (f) throw new TypeError("Generator is already executing.");
        while (_) try {
            if (f = 1, y && (t = op[0] & 2 ? y["return"] : op[0] ? y["throw"] || ((t = y["return"]) && t.call(y), 0) : y.next) && !(t = t.call(y, op[1])).done) return t;
            if (y = 0, t) op = [op[0] & 2, t.value];
            switch (op[0]) {
                case 0: case 1: t = op; break;
                case 4: _.label++; return { value: op[1], done: false };
                case 5: _.label++; y = op[1]; op = [0]; continue;
                case 7: op = _.ops.pop(); _.trys.pop(); continue;
                default:
                    if (!(t = _.trys, t = t.length > 0 && t[t.length - 1]) && (op[0] === 6 || op[0] === 2)) { _ = 0; continue; }
                    if (op[0] === 3 && (!t || (op[1] > t[0] && op[1] < t[3]))) { _.label = op[1]; break; }
                    if (op[0] === 6 && _.label < t[1]) { _.label = t[1]; t = op; break; }
                    if (t && _.label < t[2]) { _.label = t[2]; _.ops.push(op); break; }
                    if (t[2]) _.ops.pop();
                    _.trys.pop(); continue;
            }
            op = body.call(thisArg, _);
        } catch (e) { op = [6, e]; y = 0; } finally { f = t = 0; }
        if (op[0] & 5) throw op[1]; return { value: op[0] ? op[1] : void 0, done: true };
    }
};
import { annualCode } from '../../functions';
import { Article } from './article';
import { isObservable, of } from 'rxjs';
import { map } from 'rxjs/operators';
/**
 * ordine
 */
var Order = /** @class */ (function () {
    function Order(o) {
        var _this = this;
        /***********************************************************************+ */
        /** ID del database */
        this.orderId = null;
        /** codie utente  */
        this.uid = null;
        /***********************************************************************+ */
        /** elenco di OrderItems */
        this.articles = [];
        /** calcola i costi di spedizione in base a un servizio esterno */
        this.shippingCost = function (service) { return service.cost(_this); };
        /** note per la spedizione */
        this.shippingNote = undefined;
        /** indirizzo di spedizione */
        this.shippingAddress = undefined;
        /** indirizzo di fatturazione  */
        this.billingAddress = undefined;
        /** oggetto che raccoglie le informazioni sul pagamento retituite da paypal */
        this.payment = null;
        /** stato dell'ordine
         * SHOPPING
         * WORKING
         * SHIPPED
         * DELIVERED
         * REJECTED
         * RETURNED
         * REFOUNDED
         * CLOSED
         * CANCELLED
        */
        this.status = 'SHOPPING';
        this.dates = {
            "creationDate": undefined,
            "paymentDate": undefined,
            "shippingDate": undefined,
            "closingDate": undefined
        };
        /** genera un ID solo se non è passato nel contructor */
        o.orderId = o.orderId || annualCode('o');
        o.articles = o.articles ? o.articles.map(function (oi) { return new Article(oi); }) : [];
        /** assegna le proprietà */
        Object.assign(this, o);
    }
    Object.defineProperty(Order.prototype, "value", {
        /** importo al netto della spedizione, scontatoe  ma  senza iva */
        get: function () {
            return this.articles.reduce(function (prev, item) { return prev + item.price.value * item.quantity; }, 0);
        },
        enumerable: true,
        configurable: true
    });
    Object.defineProperty(Order.prototype, "subtotal", {
        /** importo al netto della spedizione, senza sconto individuale, comprensivo di iva */
        get: function () {
            return this.articles.reduce(function (prev, item) { return prev + item.price.price * item.quantity; }, 0);
        },
        enumerable: true,
        configurable: true
    });
    Object.defineProperty(Order.prototype, "total", {
        /** importo al netto della spedizione ma con gli elementi scontati individualmente, comprensivo di iva */
        get: function () {
            return this.articles.reduce(function (prev, item) { return prev + item.price.amount * item.quantity; }, 0);
        },
        enumerable: true,
        configurable: true
    });
    /** importo finale da pagare comprensivo delle spese di spedizione e dello sconto sugli elementi  e dell'iva */
    Order.prototype.amount = function (service) {
        var _this = this;
        var operation = function (sc) { return sc + _this.total; };
        var sc = this.shippingCost(service);
        return isObservable(sc) ? sc.pipe(map(operation)) : of(operation(sc));
    };
    Object.defineProperty(Order.prototype, "processingTime", {
        /** il massimo dei giorni di elaborazione tra tutti gl iitems  */
        get: function () {
            return this.articles.reduce(function (prev, item) { return Math.max(prev, item.processingTime); }, 0);
        },
        enumerable: true,
        configurable: true
    });
    /**
     * aggiunge all'elenco dell'ordine
     */
    Order.prototype.addArticle = function (p) {
        /** controlla che esista già nell'ordine lo stesso prodotto */
        var i = this.articles.findIndex(function (item) { return item.productId === p.productId; });
        if (i >= 0) {
            this.articles[i].increment();
        }
        else {
            /** trasforma il Product In un Article e lo aggiunge */
            var item = new Article(p, this.orderId);
            this.articles.push(item);
        }
        return this;
    };
    /** rimuove un prodotto dall'ordine */
    Order.prototype.removeItem = function (p) {
        /** controlla che esista già nell'ordine lo stesso prodotto */
        var i = this.articles.findIndex(function (item) { return item.productId === p.productId; });
        if (i >= 0)
            this.articles.splice(i, 1);
        return this;
    };
    /**
     * metodi che posrtano alle rispettive fasi dell'ordine
     * open --> Shopping                        [SHOPPING]
     * buy  --> Working                         [WORKING]
     * ship  --> Delivering                     [SHIPPED]
     * close --> Closing (positive)             [CLOSED]
     *
     * reject  --> Returning                    [REJECTED]
     * retake --> Refounding                    [RETURNED]
     * refound --> close --> Closing (negative) [REFOUNDED]
     */
    /**
      * SHOPPING - inizializza l'ordine in fase di apertura
      */
    Order.prototype.open = function () {
        this.dates.creationDate = new Date().getTime();
        this.status = "SHOPPING";
        return this;
    };
    /**
     * WORKING - imposta come pagato e confermato l'ordine ,  in attesa di conclusione
     * inoltre salvare l'ordine sul server e impostare il finalPrice
     */
    Order.prototype.buy = function (service) {
        var _this = this;
        this.dates.paymentDate = new Date().getTime();
        this.status = "WORKING";
        this.articles.forEach(function (article) { return __awaiter(_this, void 0, void 0, function () {
            var _a;
            return __generator(this, function (_b) {
                switch (_b.label) {
                    case 0:
                        _a = article;
                        return [4 /*yield*/, service.price(article, this)];
                    case 1:
                        _a.finalPrice = _b.sent();
                        return [2 /*return*/];
                }
            });
        }); });
        return this;
    };
    /**
     * DELIVERING - pacco in consegna
     */
    Order.prototype.ship = function () {
        this.dates.shippingDate = new Date().getTime();
        this.status = "SHIPPED";
        return this;
    };
    /**
     *
     */
    Order.prototype.close = function (positive) {
        this.dates.closingDate = new Date().getTime();
        this.status = "CLOSED";
        return this;
    };
    /**
     *
     */
    Order.prototype.cancel = function () {
        this.status = "CANCELLED";
        return this;
    };
    /**
     * da definire
     */
    Order.prototype.reject = function () {
        this.status = "REJECTED";
        return this;
    };
    /**
     * da definire
     */
    Order.prototype.retake = function () {
        this.status = "RETURNED";
        return this;
    };
    /**
     * da definire
     */
    Order.prototype.refound = function () {
        this.status = "REFOUNDED";
        return this;
    };
    return Order;
}());
export { Order };

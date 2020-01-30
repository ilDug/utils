import { ReplaySubject } from 'rxjs';
import { map, filter, tap } from 'rxjs/operators';
var RestService = /** @class */ (function () {
    /**
     * @param baseUrl url base dove inviare le richieste http,  nella forma https://api.sito.lan/items/
     */
    function RestService(baseUrl, http) {
        this.http = http;
        this.items = new ReplaySubject(1);
        this.baseUrl = baseUrl;
        this.call();
    }
    Object.defineProperty(RestService.prototype, "items$", {
        get: function () { return this.items.asObservable(); },
        enumerable: true,
        configurable: true
    });
    /**
     * carica la lista di oggetti e resituisce una Promise
     * chiamato nel constructor
     */
    RestService.prototype.call = function () {
        return this.list().toPromise();
    };
    /** carica la lista di oggetti */
    RestService.prototype.list = function () {
        var _this = this;
        return this.http.get(this.baseUrl)
            .pipe(filter(function (x) { return x !== null; }), map(function (list) { return _this.listParser(list); }), tap(function (list) { return _this.items.next(list); }));
    };
    /** load a singlo object by ID */
    RestService.prototype.load = function (id) {
        var _this = this;
        return this.http.get(this.baseUrl + id)
            .pipe(map(function (item) { return _this.itemParser(item); }));
    };
    return RestService;
}());
export { RestService };

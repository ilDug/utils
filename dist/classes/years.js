import { Subject } from 'rxjs';
var Years = /** @class */ (function () {
    function Years(base) {
        this.BASE = 2016;
        this.change = new Subject();
        this.BASE = base || this.BASE;
        this._activeYear = this.currentYear;
    }
    Object.defineProperty(Years.prototype, "change$", {
        /** emette uno stream ogni volta che l'anno viene cambiato { prev: number, next: number } */
        get: function () { return this.change.asObservable(); },
        enumerable: true,
        configurable: true
    });
    Object.defineProperty(Years.prototype, "activeYear", {
        get: function () { return this._activeYear; },
        set: function (y) {
            this.change.next({ prev: this._activeYear, next: y });
            this._activeYear = y;
        },
        enumerable: true,
        configurable: true
    });
    Object.defineProperty(Years.prototype, "years", {
        /** la lista degli anni da quello attuale fino al numero di base */
        get: function () {
            var current = new Date().getFullYear();
            var years = [];
            for (var i = this.BASE; i <= current; i++) {
                years.push(i);
            }
            years.reverse();
            return years;
        },
        enumerable: true,
        configurable: true
    });
    Object.defineProperty(Years.prototype, "currentYear", {
        /** l'anno in corso */
        get: function () { return new Date().getFullYear(); },
        enumerable: true,
        configurable: true
    });
    return Years;
}());
export default Years;

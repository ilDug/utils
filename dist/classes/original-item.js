import * as _ from 'lodash';
var OriginalItem = /** @class */ (function () {
    function OriginalItem(item) {
        this.current = item;
        this.original = _.cloneDeep(item);
    }
    Object.defineProperty(OriginalItem.prototype, "equal", {
        get: function () {
            if (!this.original)
                return false;
            return _.isEqual(this.original, this.current);
        },
        enumerable: true,
        configurable: true
    });
    Object.defineProperty(OriginalItem.prototype, "changed", {
        get: function () {
            return !this.equal;
        },
        enumerable: true,
        configurable: true
    });
    /**
     * ritorna una copia clonata del contenuto
     * @param type sceglere  se ritornale "original" oppure "current"
     */
    OriginalItem.prototype.clone = function (type) {
        switch (type) {
            case 'original':
                return _.cloneDeep(this.original);
                break;
            case 'current':
                return _.cloneDeep(this.current);
                break;
            default:
                break;
        }
    };
    return OriginalItem;
}());
export default OriginalItem;

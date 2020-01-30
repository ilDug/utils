var __assign = (this && this.__assign) || function () {
    __assign = Object.assign || function(t) {
        for (var s, i = 1, n = arguments.length; i < n; i++) {
            s = arguments[i];
            for (var p in s) if (Object.prototype.hasOwnProperty.call(s, p))
                t[p] = s[p];
        }
        return t;
    };
    return __assign.apply(this, arguments);
};
;
var Cookie = /** @class */ (function () {
    function Cookie() {
        this.defaults = {};
    }
    Cookie.prototype.decode = function (s) {
        return s.replace(/(%[0-9A-Z]{2})+/g, decodeURIComponent);
    };
    Cookie.prototype.set = function (key, value, attributes) {
        if (typeof document === 'undefined')
            return;
        attributes = __assign(__assign({ path: '/' }, this.defaults), attributes);
        if (typeof attributes.expires === 'number') {
            attributes.expires = new Date(+(new Date()) * 1 + attributes.expires * 864e+5);
        }
        // We're using "expires" because "max-age" is not supported by IE
        attributes.expires = attributes.expires ? attributes.expires.toUTCString() : '';
        /** controlla se sia o meno un oggetto o un array e lo ocnverte in testo*/
        try {
            var result = JSON.stringify(value);
            if (/^[\{\[]/.test(result)) {
                value = result;
            }
        }
        catch (e) { }
        value = encodeURIComponent(String(value))
            .replace(/%(23|24|26|2B|3A|3C|3E|3D|2F|3F|40|5B|5D|5E|60|7B|7D|7C)/g, decodeURIComponent);
        key = encodeURIComponent(String(key))
            .replace(/%(23|24|26|2B|5E|60|7C)/g, decodeURIComponent)
            .replace(/[\(\)]/g, escape);
        var stringifiedAttributes = '';
        for (var attributeName in attributes) {
            if (!attributes[attributeName]) {
                continue;
            }
            stringifiedAttributes += '; ' + attributeName;
            if (attributes[attributeName] === true) {
                continue;
            }
            // Considers RFC 6265 section 5.2:
            // ...
            // 3.  If the remaining unparsed-attributes contains a %x3B (";")
            //     character:
            // Consume the characters of the unparsed-attributes up to,
            // not including, the first %x3B (";") character.
            // ...
            stringifiedAttributes += '=' + attributes[attributeName].split(';')[0];
        }
        return (document.cookie = key + '=' + value + stringifiedAttributes);
    };
    Cookie.prototype.get = function (key, json) {
        if (typeof document === 'undefined')
            return;
        var jar = {};
        // To prevent the for loop in the first place assign an empty array
        // in case there are no cookies at all.
        var cookies = document.cookie ? document.cookie.split('; ') : [];
        for (var i = 0; i < cookies.length; i++) {
            var parts = cookies[i].split('=');
            var cookie = parts.slice(1).join('=');
            if (!json && cookie.charAt(0) === '"') {
                cookie = cookie.slice(1, -1);
            }
            try {
                var name_1 = this.decode(parts[0]);
                cookie = this.decode(cookie);
                if (json) {
                    try {
                        cookie = JSON.parse(cookie);
                    }
                    catch (e) { }
                }
                jar[name_1] = cookie;
                if (key === name_1) {
                    break;
                }
            }
            catch (e) { }
        }
        return key ? jar[key] : jar;
    };
    /**
     * ritona in formato JSON
     */
    Cookie.prototype.getJson = function (key) {
        return this.get(key, true);
    };
    /**
    * Delete cookie
    * IMPORTANT! When deleting a cookie and you're not relying on the default attributes,
    * you must pass the exact same path and domain attributes that were used to set the cookie:
    * Cookies.remove('name', { path: '', domain: '.yourdomain.com' });
    */
    Cookie.prototype.remove = function (key, attributes) {
        this.set(key, '', __assign(__assign({}, attributes), { expires: -1 }));
    };
    return Cookie;
}());
export { Cookie };

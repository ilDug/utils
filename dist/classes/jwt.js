// tslint:disable:no-bitwise
/**
 * operazioni di decodifica e controllo delle informazioni di un token JWT
 * verione aggiornata a settembre 2019
 * durante l'istanza dell'oggetto viene passato il token grezzo
 */
var Jwt = /** @class */ (function () {
    function Jwt(jwt) {
        /** carica il contenito decodificandolo */
        this.claims = this.decodeToken(jwt);
        /** salva il token grezzo */
        this.token = jwt;
    }
    Object.defineProperty(Jwt.prototype, "expired", {
        /** controlla se il token è scaduto */
        get: function () { return this.isTokenExpired(this.token); },
        enumerable: true,
        configurable: true
    });
    Object.defineProperty(Jwt.prototype, "expirationDate", {
        /** restituisce la data di scadenza el token icome oggetto Date */
        get: function () { return this.getTokenExpirationDate(this.token); },
        enumerable: true,
        configurable: true
    });
    Jwt.prototype.urlBase64Decode = function (str) {
        var output = str.replace(/-/g, '+').replace(/_/g, '/');
        switch (output.length % 4) {
            case 0: {
                break;
            }
            case 2: {
                output += '==';
                break;
            }
            case 3: {
                output += '=';
                break;
            }
            default: {
                throw 'Illegal base64url string!';
            }
        }
        return this.b64DecodeUnicode(output);
    };
    // credits for decoder goes to https://github.com/atk
    Jwt.prototype.b64decode = function (str) {
        var chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';
        var output = '';
        str = String(str).replace(/=+$/, '');
        if (str.length % 4 === 1) {
            throw new Error("'atob' failed: The string to be decoded is not correctly encoded.");
        }
        for (
        // initialize result and counters
        var bc = 0, bs = void 0, buffer = void 0, idx = 0; 
        // get next character
        (buffer = str.charAt(idx++)); 
        // character found in table? initialize bit storage and add its ascii value;
        ~buffer &&
            ((bs = bc % 4 ? bs * 64 + buffer : buffer),
                // and if not first of each 4 characters,
                // convert the first 8 bits to one ascii character
                bc++ % 4)
            ? (output += String.fromCharCode(255 & (bs >> ((-2 * bc) & 6))))
            : 0) {
            // try to find character in table (0-63, not found => -1)
            buffer = chars.indexOf(buffer);
        }
        return output;
    };
    Jwt.prototype.b64DecodeUnicode = function (str) {
        return decodeURIComponent(Array.prototype.map
            .call(this.b64decode(str), function (c) {
            return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
        })
            .join(''));
    };
    /**
    * restituisce il token decodificato (tipo any) o null
    */
    Jwt.prototype.decodeToken = function (token) {
        if (token == null || token === '') {
            return null;
        }
        var parts = token.split('.');
        if (parts.length !== 3) {
            throw new Error('The inspected token doesn\'t appear to be a JWT. Check to make sure it has three parts and see https://jwt.io for more.');
        }
        var decoded = this.urlBase64Decode(parts[1]);
        if (!decoded) {
            throw new Error('Cannot decode the token.');
        }
        return JSON.parse(decoded);
    };
    /**
     * resituisce un ofggetto DATE contenenta la data di scadenza del token ('exp')
     */
    Jwt.prototype.getTokenExpirationDate = function (token) {
        var decoded;
        decoded = this.decodeToken(token);
        if (!decoded || !decoded.hasOwnProperty('exp')) {
            return null;
        }
        var date = new Date(0);
        date.setUTCSeconds(decoded.exp);
        return date;
    };
    /**
     * @return boolean
     * verifica che il token non sia scaduto
     */
    Jwt.prototype.isTokenExpired = function (token, offsetSeconds) {
        if (token == null || token === '') {
            return true;
        }
        var date = this.getTokenExpirationDate(token);
        offsetSeconds = offsetSeconds || 0;
        if (date === null) {
            return false;
        }
        return !(date.valueOf() > new Date().valueOf() + offsetSeconds * 1000);
    };
    return Jwt;
}());
export { Jwt };

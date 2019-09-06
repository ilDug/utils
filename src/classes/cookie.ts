type CookieWriteConverter = (value: any, name: string) => string;
type CookieReadConverter = (value: string, name: string) => string;
interface CookieConverter extends CookieReadConverter { write?: CookieWriteConverter, read?: CookieReadConverter };
interface CookieAttributes {
    /**
     * Define when the cookie will be removed. Value can be a Number
     * which will be interpreted as days from time of creation or a
     * Date instance. If omitted, the cookie becomes a session cookie.
     */
    expires?: any;

    /**
     * Define the path where the cookie is available. Defaults to '/'
     */
    path?: string;

    /**
     * Define the domain where the cookie is available. Defaults to
     * the domain of the page where the cookie was created.
     */
    domain?: string;

    /**
     * A Boolean indicating if the cookie transmission requires a
     * secure protocol (https). Defaults to false.
     */
    secure?: boolean;
}



export class Cookie {

    private defaults: CookieAttributes = {};


    private decode(s: string): string {
        return s.replace(/(%[0-9A-Z]{2})+/g, decodeURIComponent);
    }


    public set(key: string, value: string | Object, attributes?: CookieAttributes): string {

        if (typeof document === 'undefined') return;

        attributes = { path: '/', ...this.defaults, ...attributes };

        if (typeof attributes.expires === 'number') {
            attributes.expires = new Date(+(new Date()) * 1 + attributes.expires * 864e+5);
        }

        // We're using "expires" because "max-age" is not supported by IE
        attributes.expires = attributes.expires ? attributes.expires.toUTCString() : '';



        /** controlla se sia o meno un oggetto o un array e lo ocnverte in testo*/
        try {
            let result = JSON.stringify(value);
            if (/^[\{\[]/.test(result)) {
                value = result;
            }
        } catch (e) { }

        value = encodeURIComponent(String(value))
            .replace(/%(23|24|26|2B|3A|3C|3E|3D|2F|3F|40|5B|5D|5E|60|7B|7D|7C)/g, decodeURIComponent);


        key = encodeURIComponent(String(key))
            .replace(/%(23|24|26|2B|5E|60|7C)/g, decodeURIComponent)
            .replace(/[\(\)]/g, escape);

        let stringifiedAttributes = '';
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
    }


    public get(key?: string, json?: boolean): string | { [key: string]: string } | undefined {
        if (typeof document === 'undefined') return;

        let jar = {};
        // To prevent the for loop in the first place assign an empty array
        // in case there are no cookies at all.
        let cookies = document.cookie ? document.cookie.split('; ') : [];

        for (let i = 0; i < cookies.length; i++) {
            var parts = cookies[i].split('=');
            var cookie = parts.slice(1).join('=');

            if (!json && cookie.charAt(0) === '"') {
                cookie = cookie.slice(1, -1);
            }

            try {
                let name = this.decode(parts[0]);
                cookie = this.decode(cookie);

                if (json) {
                    try {
                        cookie = JSON.parse(cookie);
                    } catch (e) { }
                }

                jar[name] = cookie;

                if (key === name) {
                    break;
                }
            } catch (e) { }
        }

        return key ? jar[key] : jar;
    }





    /**
     * ritona in formato JSON
     */
    public getJson(key?: string) {
        return this.get(key, true)
    }







    /**
    * Delete cookie
    * IMPORTANT! When deleting a cookie and you're not relying on the default attributes, 
    * you must pass the exact same path and domain attributes that were used to set the cookie:
    * Cookies.remove('name', { path: '', domain: '.yourdomain.com' });
    */
    public remove(key: string, attributes: any): void {
        this.set(key, '', { ...attributes, expires: -1 })
    }
}


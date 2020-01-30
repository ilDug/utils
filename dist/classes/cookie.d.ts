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
export declare class Cookie {
    private defaults;
    private decode;
    set(key: string, value: string | Object, attributes?: CookieAttributes): string;
    get(key?: string, json?: boolean): string | {
        [key: string]: string;
    } | undefined;
    /**
     * ritona in formato JSON
     */
    getJson(key?: string): string | {
        [key: string]: string;
    };
    /**
    * Delete cookie
    * IMPORTANT! When deleting a cookie and you're not relying on the default attributes,
    * you must pass the exact same path and domain attributes that were used to set the cookie:
    * Cookies.remove('name', { path: '', domain: '.yourdomain.com' });
    */
    remove(key: string, attributes: any): void;
}
export {};

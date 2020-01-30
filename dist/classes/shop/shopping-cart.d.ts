import { Observable } from "rxjs";
export declare type ShoppingCart = ShoppingCartItem[];
export declare class ShoppingCartItem {
    constructor(item: Partial<ShoppingCartItem>);
    productId: string;
    image: string;
    title: string;
    private _quantity;
    get quantity(): number;
    increment(): void;
    decrement(): void;
}
export declare class ShoppingCartService {
    constructor();
    private cookies;
    /** configurazioni iniziali del cookies */
    private cookie;
    /** subject emesso al momento della scrittura / eliminazione del cookie */
    private cartEvent;
    get cart$(): Observable<ShoppingCart>;
    /** aggiunge un elemento al carrello e lo scrive nei cookies */
    addItem(item: ShoppingCartItem): void;
    /** rimuove un elemento nel carrello e aggiurna il cookies */
    removeItem(item: ShoppingCartItem): void;
    /** legge i cookies e genera un carrello */
    private readCart;
    /** scrive il carrello nei cookies */
    private writeCart;
    /** utility per la codifica dei file JSON in base64 */
    private encode;
    /** utility per la decodifica del codice del cookies da Base64 in JSON */
    private decode;
}

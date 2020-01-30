import { Subject, Observable, ReplaySubject } from "rxjs";
import { Cookie } from "../cookie";


export type ShoppingCart = ShoppingCartItem[];


export class ShoppingCartItem {
    constructor(item:Partial<ShoppingCartItem>){
        Object.assign(this, item)
    }

    public productId: string;

    public image: string;

    public title: string;

    private _quantity: number = 1;
    get quantity(): number { return this._quantity };


    public increment() { this._quantity = this._quantity + 1 }
    public decrement() { this._quantity = this._quantity > 0 ? this._quantity - 1 : 0 }


}



export class ShoppingCartService {

    
    constructor() { 
        let sc = this.readCart() 
        this.cartEvent.next(sc)
    }


    private cookies = new Cookie();

    /** configurazioni iniziali del cookies */
    private cookie = {
        name: "shca",
        path: "/",
    }

    /** subject emesso al momento della scrittura / eliminazione del cookie */
    private cartEvent: ReplaySubject<ShoppingCart> = new ReplaySubject(1);
    get cart$(): Observable<ShoppingCart> { return this.cartEvent.asObservable() }

    /** aggiunge un elemento al carrello e lo scrive nei cookies */
    public addItem(item:ShoppingCartItem) {
        let sc:ShoppingCart = this.readCart();
        const i = sc.findIndex(x => x.productId === item.productId)

        if(i >=0){
            sc[i].increment();
        } else {
            sc.push(item)
        }

        this.writeCart(sc);
    }

    /** rimuove un elemento nel carrello e aggiurna il cookies */
    public removeItem(item: ShoppingCartItem) {
        let sc: ShoppingCart = this.readCart();
        const i = sc.findIndex(x => x.productId === item.productId)
        if(i >=0) sc.splice(i,1);
        this.writeCart(sc);
    }

    /** legge i cookies e genera un carrello */
    private readCart(): ShoppingCart {
        const code: any = this.cookies.get(this.cookie.name);
        let sc = this.decode(code)
        return sc;
    }

    /** scrive il carrello nei cookies */
    private writeCart(sc:ShoppingCart): void {
        const code = this.encode(sc);
        this.cookies.set(this.cookie.name, code)
        this.cartEvent.next(sc)
    }

    /** utility per la codifica dei file JSON in base64 */
    private encode(sc:ShoppingCart): string {
        return btoa(JSON.stringify(sc));
    }

    /** utility per la decodifica del codice del cookies da Base64 in JSON */
    private decode(code: string): ShoppingCart {
        let list:any[]  =  code ? JSON.parse(atob(code)) : [];
        if(list.length == 0) return []

        let cart:ShoppingCart = []
        for (const item of list) {
            cart.push((new ShoppingCartItem(item)))
        }
        return cart
    }
}
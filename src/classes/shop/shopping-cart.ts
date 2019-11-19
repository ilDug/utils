import { annualCode } from "../../functions";
import { Product } from "./product";


export class ShoppingCart {

    constructor(sc: Partial<ShoppingCart>) {
        // sc.cartId = sc.cartId || annualCode('sc');

        // /** assegna le proprietà */
        // Object.assign(this, sc);
    }


    /** ID  */
    public cartId: string = null


    public items: ShoppingCartItem[] = [];


    public addItem(){
        // copia da order
    }


    public removeItem(){
        // copia da order
    }


    public encode() {

    }


    public decode(code: string) {
        const decodedObject = {}
        Object.assign(this, decodedObject)
    }

}

export class ShoppingCartItem {

    public productId: string;

    public image: string;

    public title: string;

    private _quantity: number = 1;
    get quantity(): number { return this.quantity };


    public increment() { this._quantity = this._quantity + 1 }
    public decrement() { this._quantity = this._quantity > 0 ? this._quantity - 1 : 0 }

 
}
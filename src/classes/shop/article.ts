import { Product } from "./product";
import { annualCode } from "../../functions";


/**
 * elemento componente l'ordine
 */
export class Article extends Product{

    constructor(a: Partial<Article>, orderId?: string) {
        super(a);

        /** assegna le proprietà */
        Object.assign(this, a);
    }

    /** 
     * ID del database WAREHOUSE
     * da assegnare quando l'ordine è gia stato eseguito, ma prima della spedizione
    */
    public articleIds: number[] = [];
    
    /**
    * prezzo finale da salvare sul server nel momento del pagamento che contiene la ripartizione degli sconti
    * tra gli elementi dell'ordine. Assegnato da un service
    */
    public finalPrice: number;

    private _quantity: number = 1;
    get quantity(): number { return this.quantity };


    public increment() { this._quantity = this._quantity + 1 }
    public decrement() { this._quantity = this._quantity > 0 ? this._quantity - 1 : 0 }
}

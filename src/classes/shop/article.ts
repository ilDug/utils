import { Product } from "./product";
import { annualCode } from "../../functions";


/**
 * elemento componente l'ordine
 */
export class Article extends Product{

    constructor(a: Partial<Article>, orderId?: string) {
        super(a);
        a.articleId = a.articleId || annualCode('a');
        a.orderId = a.orderId || orderId || undefined;

        /** assegna le proprietà */
        Object.assign(this, a);

    }

    /** ID del database */
    public articleId: string = null;


    /** codice univoco dell'Ordine */
    public orderId : string = undefined;
    
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

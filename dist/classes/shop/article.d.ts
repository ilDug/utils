import { Product } from "./product";
/**
 * elemento componente l'ordine
 */
export declare class Article extends Product {
    constructor(a: Partial<Article>, orderId?: string);
    /**
     * ID del database WAREHOUSE
     * da assegnare quando l'ordine è gia stato eseguito, ma prima della spedizione
    */
    articleIds: number[];
    /**
    * prezzo finale da salvare sul server nel momento del pagamento che contiene la ripartizione degli sconti
    * tra gli elementi dell'ordine. Assegnato da un service
    */
    finalPrice: number;
    private _quantity;
    get quantity(): number;
    increment(): void;
    decrement(): void;
}

import { Product } from "./product";
import { annualCode } from "../../functions";


/**
 * elemento componente l'ordine
 */
export class OrderItem extends Product{

    constructor(oi: Partial<OrderItem>, orderId?: string) {
        super(oi);
        oi.oitemID = oi.oitemID || annualCode('oi');
        oi.orderId = oi.orderId || orderId || undefined;

        /** assegna le proprietà */
        Object.assign(this, oi);

    }

    /** ID del database */
    public oitemID: string = null;


    /** codice univoco dell'Ordine */
    public orderId : string = undefined;


    private _quantity: number = 1;
    get quantity(): number { return this.quantity };


    public increment() { this._quantity = this._quantity + 1 }
    public decrement() { this._quantity = this._quantity > 0 ? this._quantity - 1 : 0 }

//     /**
//     * prezzo finale da salvare sul server nel momento del pagamento che contiene la ripartizione degli sconti
//     * tra gli elementi dell'ordine. Assegnato da un service
//     */
//     public finalPrice: number;


    /** stato dell'ordiene: definisce se è pagato, spedito, consegnato ,  reso, accettato, in fase di... */
    // public status: OrderStatus;


//     /** codice della stampa/lotto assegnato in fase di lavorazione */
//     public printCodes: string[];

//     /** numbero di traking */
//     public trackingNumber: string ;

//     /** valore al netto degli sconti */
//     get rawTotal():number {
//         let price : Price = this.materials.find((m:Material)=>{ return m.selected }).price;
//         return this.quantity * price.price
//     }

//     /** valore scontato */
//     get total():number {
//         let price : Price = this.materials.find((m:Material)=>{ return m.selected }).price;
//         return this.quantity * price.amount
//     }









}

// import { PBItem } from './pb-item';
// import { Discount } from './discount';
// import { OrderStatus } from './order-status';
// import { Price } from './price';
// import { Material } from './material';

// import { AddressBillingI, AddressShippingI } from '../address';



// /**
//  * elemento componente l'ordine
//  */
// export class OrderItem extends PBItem{
//     /** IDdel database */
//     public OID :number;

//     /**
//     * codie utente IDPcode
//     * se non viene specificato,  assume il valore di undefined
//     * per i clienti non registrati
//     */
//     public uid: string;

//     /** codice univoco dell'Ordine */
//     public orderCode : string;


//     public quantity: number;

//     /**
//     * prezzo finale da salvare sul server nel momento del pagamento che contiene la ripartizione degli sconti
//     * tra gli elementi dell'ordine. Assegnato da un service
//     */
//     public finalPrice: number;

//     /** la stringa in formato json perl'archivio delle informazioni del singolo OrderItem */
//     public fingerprint: string

//     /** stato dell'ordiene: definisce se è pagato, spedito, consegnato ,  reso, accettato, in fase di... */
//     public status: OrderStatus;

//     /** costo di spedizione da calcolare esternamente */
//     public shippingCost: number;

//     /** note per la spedizione */
//     public shippingNote: string;

//     /** codice promozionale riferito all'intero ordine */
//     public coupon: string;

//     /** indirizzo di fatturazione  */
//     public billingAddress : AddressBillingI;

//     /** indirizzo di spedizione */
//     public shippingAddress: AddressShippingI;

//     /** oggetto che raccoglie le informazioni sul pagamento retituite da paypal */
//     public payment : any;

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


//     constructor(oi:any){
//         super(oi);

//         this.OID = oi.OID ? oi.OID : null ;
//         this.uid = oi.uid ? oi.uid : undefined ;
//         this.orderCode = oi.orderCode ? oi.orderCode : null ;
//         this.quantity = oi.quantity ? oi.quantity : 1 ;
//         this.finalPrice = oi.finalPrice ? oi.finalPrice : null;
//         this.fingerprint = oi.fingerprint ? oi.fingerprint :  null;
//         this.status = oi.status ? new OrderStatus(oi.status) : new OrderStatus({});
//         this.shippingCost = oi.shippingCost ? oi.shippingCost :  0;
//         this.coupon = oi.coupon ? oi.coupon :  null;
//         this.shippingNote = oi.shippingNote ? oi.shippingNote : null;
//         this.billingAddress = oi.billingAddress ? oi.billingAddress : null;
//         this.shippingAddress = oi.shippingAddress ? oi.shippingAddress : null;
//         this.payment = oi.payment ? oi.payment : {};
//         this.printCodes = oi.printCodes ? oi.printCodes: [];
//         this.trackingNumber = oi.trackingNumber ? oi.trackingNumber: null;

//     }


//     public increment(){ this.quantity = this.quantity + 1 }
//     public decrement(){ this.quantity = this.quantity > 0 ?  this.quantity - 1 : 0}



//     /**
//      * salva nel fingerprint lo snapshot dell'orderitem
//      */
//     public snapshot(){
//         this.fingerprint = null;
//         this.fingerprint = JSON.stringify(this);
//     }
// }

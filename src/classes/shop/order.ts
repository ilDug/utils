// import { OrderItem } from './order-item';
// import { OrderStatus } from './order-status';
// import { PBItem } from './pb-item';
// import { AddressBillingI, AddressShippingI } from '../address';




// /**
//  * ordine
//  */
// export class Order{
//     /** elenco di OrderItems */
//     public items : OrderItem[] = [];


//     /** codie utente IDPcode */
//     private uid: string;
//     public setUid(id:string):Order{ this.items.forEach((i:OrderItem)=>{ i.uid = id }); this.uid = id; return this;}
//     public getUid():string{
//         if(this.items.length == 0 ) return undefined;
//         let item:OrderItem = this.items.find((i:OrderItem)=>{ return i.uid != undefined })
//         return item.uid;
//     }


//     /** codice univoco dell'Ordine */
//     private orderCode : string;
//     public setOrderCode(c:string):Order{ this.items.forEach((i:OrderItem)=>{ i.orderCode = c }); this.orderCode = c; return this; }
//     public getOrderCode():string{
//         if(this.items.length == 0 ) return undefined;
//         let item:OrderItem = this.items.find((i:OrderItem)=>{ return i.orderCode != undefined })
//         return item.orderCode;
//     }


//     /** codice sconto che verrà utilizzato in segito nel servizio esterno per calcolare il totale */
//     // set coupon(c:string){ this.items.map((i:OrderItem)=>{ i.coupon = c }) };
//     // get coupon(){
//     //     let item:OrderItem = this.items.find((i:OrderItem)=>{ return i.coupon != null });
//     //     return item ? item.coupon : null;
//     // }
//     public coupon : string;

//     /** fa passare gli shipping cost per putti gli item e ne restituisce solo quello più alto */
//     get shippingCost():number{
//         let costs: number[] = this.items.map((oi:OrderItem)=>{ return oi.shippingCost });
//         let max :number = Math.max(...costs);
//         return max;
//     }



//     constructor(o:any){
//         this.uid = o.uid ? o.uid : null;
//         this.orderCode = o.orderCode ? o.orderCode : null;
//         this.items = o.items ? o.items.map((i:any)=> {return new OrderItem(i)} ) : [];

//     }




//     /**
//      * metodo che partendo da un pbItem lo trasforma in on OrderItem e lo aggiunge all'elenco dell'odine
//      */
//     public addNewItem(oi: OrderItem):Order{
//         // oi.uid = this.getUid();
//         // oi.orderCode = this.getOrderCode();
//         oi.status.open();
//         this.items.push(oi);
//         return this;
//     }



//     /**
//      * imposta gli indirizzi
//      */
//     public setAddresses(bill:AddressBillingI, ship?:AddressShippingI, note?:string):Order{
//         this.items.forEach((i:OrderItem) => {
//             i.billingAddress = bill;
//             i.shippingAddress = ship? ship : bill;
//             i.shippingNote = note? note : i.shippingNote;
//         });
//         return this;
//     }




//     /**
//      * dopo il pagamento fossilizza tutte  le informazione dell'ordine
//      * in modo da potere salvare la versione definitiva sul server
//      */
//     public fossilize():Order{
//         this.items.map((i:OrderItem)=>{

//             /** fissa il prezzo concordato comprensivo di sconti */
//             i.finalPrice = i.total;

//             /** imposta l'ordine come acquistato */
//             i.status.buy()

//             /** salva copia delle info dell'item come json */
//             i.snapshot();

//             return i;
//         })
//         return this;
//     }




//     /** importo al netto della spedizione e dello sconto individuale */
//     get rawSubtotal():number{
//         let t: number = 0;
//         this.items.forEach((i:OrderItem)=>{ t = t + i.rawTotal});
//         return t;
//     }



//     /** importo al netto della spedizione ma con gli elementi scontati individualmente */
//     get subtotal():number{
//         let t: number = 0;
//         this.items.forEach((i:OrderItem)=>{ t = t + i.total});
//         return t;
//     }



//     /** importo finale da pagare comprensivo delle spese di spedizione e dello sconto sugli elementi*/
//     get total():number{
//         let t: number = this.subtotal;
//         //t= (t - this.discount.bonus) * (1 - this.discount.rate);
//         t = t + this.shippingCost;
//         return t;
//     }


//     /** il massimo dei giorni di elaborazione tra tutti gl iitems  */
//     get processingTime():number{
//         let times = this.items.map((oi:OrderItem)=>{ return oi.processingTime })
//         return Math.max(...times);
//     }


// }
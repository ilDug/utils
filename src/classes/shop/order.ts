import { annualCode } from '../../functions'
import { OrderItem } from './order-item';
import { Product } from './product';
import { IShippingCostService, IBillingAddress, IShippingAddress } from '../../interfaces';
import { Observable, isObservable, of } from 'rxjs';
import { map } from 'rxjs/operators';



/**
 * ordine
 */
export class Order{

    constructor(o: Partial<Order>) {
        /** genera un ID solo se non è passato nel contructor */
        o.orderId = o.orderId || annualCode('o');  
        o.items = o.items ? o.items.map(oi => new OrderItem(oi)) : [];

        /** assegna le proprietà */
        Object.assign(this, o);
    }

    /** ID del database */
    public orderId: string = null;

    /** codie utente  */
    public uid: string = null;

    /** elenco di OrderItems */
    public items : OrderItem[] = [];

    /** calcola i costi di spedizione in base a un servizio esterno */
    public shippingCost = (service: IShippingCostService) => service.cost(this)

    /** note per la spedizione */
    public shippingNote: string = undefined

    /** indirizzo di spedizione */
    public shippingAddress: IShippingAddress = undefined
    
    /** codice promozionale riferito all'intero ordine */
    public coupon: undefined

    /** indirizzo di fatturazione  */
    public billingAddress: IBillingAddress =  undefined

    /** oggetto che raccoglie le informazioni sul pagamento retituite da paypal */
    public payment: any = null;

    /** stato dell'ordine
     * OPEN 
     * CLOSED 
     * WORKING 
     * SHIPPED 
     * RETURNED 
     * REFOUNDED 
     * REJECTED 
    */
    public orderStatus: string = 'OPEN';

    /** importo al netto della spedizione, scontatoe  ma  senza iva */
    get value():number{
        return this.items.reduce((prev, item) => prev + item.price.value * item.quantity, 0)
    }
    
    /** importo al netto della spedizione, senza sconto individuale, comprensivo di iva */
    get subtotal(): number {
        return this.items.reduce((prev, item) => prev + item.price.price * item.quantity, 0)
    }

    /** importo al netto della spedizione ma con gli elementi scontati individualmente, comprensivo di iva */
    get total():number{
        return this.items.reduce((prev, item) => prev + item.price.amount * item.quantity, 0)
    }

    /** importo finale da pagare comprensivo delle spese di spedizione e dello sconto sugli elementi  e dell'iva */
    public amount(service: IShippingCostService): Observable<number> {
        const operation = (sc: number) => sc + this.total;
        const sc = this.shippingCost(service)
        return isObservable<number>(sc) ? sc.pipe(map(operation)) : of(operation(sc)) 
    }


    /** il massimo dei giorni di elaborazione tra tutti gl iitems  */
    get processingTime():number{
        return this.items.reduce((prev, item) => Math.max(prev, item.processingTime), 0)
    }


    /**
     * aggiunge all'elenco dell'ordine
     */
    public addItem(p: Product):Order{
        /** controlla che esista già nell'ordine lo stesso prodotto */
        let i = this.items.findIndex(item => item.productId === p.productId);

        if (i >= 0) {
            this.items[i].increment();
        } else {
            /** trasforma il Product In un Order Item e lo aggiunge */
            let oi = new OrderItem(p, this.orderId)
            this.items.push(oi);
        }
        return this;
    }


    /** rimuove un prodotto dall'ordine */
    public removeItem(p:Product):Order{
        /** controlla che esista già nell'ordine lo stesso prodotto */
        let i = this.items.findIndex(item => item.productId === p.productId);
        if (i >= 0) this.items.splice(i, 1)
        return this;
    }








}

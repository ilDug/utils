import { annualCode } from '../../functions'
import { Article } from './article';
import { Product } from './product';
import { IShippingCostService, IBillingAddress, IShippingAddress, IFinalPriceService } from '../../interfaces';
import { Observable, isObservable, of } from 'rxjs';
import { map } from 'rxjs/operators';



/**
 * ordine
 */
export class Order{

    constructor(o: Partial<Order>) {
        /** genera un ID solo se non è passato nel contructor */
        o.orderId = o.orderId || annualCode('o');  
        o.articles = o.articles ? o.articles.map(oi => new Article(oi)) : [];

        /** assegna le proprietà */
        Object.assign(this, o);
    }


/***********************************************************************+ */

    /** ID del database */
    public orderId: string = null;

    /** codie utente  */
    public uid: string = null;

    /** email usata nella creazione dell'ordine */
    public email:string = null;

/***********************************************************************+ */

    /** elenco di OrderItems */
    public articles : Article[] = [];

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
     * SHOPPING 
     * WORKING 
     * SHIPPED 
     * DELIVERED
     * REJECTED 
     * RETURNED 
     * REFOUNDED 
     * CLOSED 
    */
    public status: string = 'SHOPPING';

    public dates: { [date: string]: number } = {
        "creationDate": undefined,
        "paymentDate": undefined,
        "shippingDate": undefined,
        "closingDate": undefined
    }

    /** numbero di traking */
    public trackingNumber: string ;


    /** importo al netto della spedizione, scontatoe  ma  senza iva */
    get value():number{
        return this.articles.reduce((prev, item) => prev + item.price.value * item.quantity, 0)
    }
    
    /** importo al netto della spedizione, senza sconto individuale, comprensivo di iva */
    get subtotal(): number {
        return this.articles.reduce((prev, item) => prev + item.price.price * item.quantity, 0)
    }

    /** importo al netto della spedizione ma con gli elementi scontati individualmente, comprensivo di iva */
    get total():number{
        return this.articles.reduce((prev, item) => prev + item.price.amount * item.quantity, 0)
    }

    /** importo finale da pagare comprensivo delle spese di spedizione e dello sconto sugli elementi  e dell'iva */
    public amount(service: IShippingCostService): Observable<number> {
        const operation = (sc: number) => sc + this.total;
        const sc = this.shippingCost(service)
        return isObservable<number>(sc) ? sc.pipe(map(operation)) : of(operation(sc)) 
    }


    /** il massimo dei giorni di elaborazione tra tutti gl iitems  */
    get processingTime():number{
        return this.articles.reduce((prev, item) => Math.max(prev, item.processingTime), 0)
    }


    /**
     * aggiunge all'elenco dell'ordine
     */
    public addArticle(p: Product):Order{
        /** controlla che esista già nell'ordine lo stesso prodotto */
        let i = this.articles.findIndex(item => item.productId === p.productId);

        if (i >= 0) {
            this.articles[i].increment();
        } else {
            /** trasforma il Product In un Article e lo aggiunge */
            let item = new Article(p, this.orderId)
            this.articles.push(item);
        }
        return this;
    }


    /** rimuove un prodotto dall'ordine */
    public removeItem(p:Product):Order{
        /** controlla che esista già nell'ordine lo stesso prodotto */
        let i = this.articles.findIndex(item => item.productId === p.productId);
        if (i >= 0) this.articles.splice(i, 1)
        return this;
    }





    /**
     * metodi che posrtano alle rispettive fasi dell'ordine
     * open --> Shopping                        [SHOPPING]
     * buy  --> Working                         [WORKING]
     * ship  --> Delivering                     [SHIPPED]
     * close --> Closing (positive)             [CLOSED]
     *
     * reject  --> Returning                    [REJECTED]
     * retake --> Refounding                    [RETURNED]
     * refound --> close --> Closing (negative) [REFOUNDED]
     */


    /**
      * SHOPPING - inizializza l'ordine in fase di apertura
      */
    public open(): Order {
        this.dates.creationDate = new Date().getTime();
        this.status = "SHOPPING"
        return this;
    }




    /**
     * WORKING - imposta come pagato e confermato l'ordine ,  in attesa di conclusione
     * inoltre salvare l'ordine sul server e impostare il finalPrice
     */
    public buy(service: IFinalPriceService): Order {
        this.dates.paymentDate = new Date().getTime();
        this.status = "WORKING"
        
        this.articles.forEach(async (article: Article) => {
            article.finalPrice = await service.price(article, this)
        });

        return this;
    }



    /**
     * DELIVERING - pacco in consegna
     */
    public ship(): Order {
        this.dates.shippingDate = new Date().getTime();
        this.status = "SHIPPED"
        return this;
    }




    /**
     *
     */
    public close(positive: boolean): Order {
        this.dates.closingDate = new Date().getTime();
        this.status = "CLOSED"
        return this;
    }




    /**
     * da definire
     */
    public reject(): Order {
        this.status = "REJECTED"
        return this;
    }




    /**
     * da definire
     */
    public retake(): Order {
        this.status = "RETURNED"
        return this;
    }




    /**
     * da definire
     */
    public refound(): Order {
        this.status = "REFOUNDED"
        return this;
    }





}

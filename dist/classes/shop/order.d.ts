import { Article } from './article';
import { Product } from './product';
import { IShippingCostService, IBillingAddress, IShippingAddress, IFinalPriceService } from '../../interfaces';
import { Observable } from 'rxjs';
/**
 * ordine
 */
export declare class Order {
    constructor(o: Partial<Order>);
    /***********************************************************************+ */
    /** ID del database */
    orderId: string;
    /** codie utente  */
    uid: string;
    /***********************************************************************+ */
    /** elenco di OrderItems */
    articles: Article[];
    /** calcola i costi di spedizione in base a un servizio esterno */
    shippingCost: (service: IShippingCostService) => number | Observable<number>;
    /** note per la spedizione */
    shippingNote: string;
    /** indirizzo di spedizione */
    shippingAddress: IShippingAddress;
    /** codice promozionale riferito all'intero ordine */
    coupon: undefined;
    /** indirizzo di fatturazione  */
    billingAddress: IBillingAddress;
    /** oggetto che raccoglie le informazioni sul pagamento retituite da paypal */
    payment: any;
    /** stato dell'ordine
     * SHOPPING
     * WORKING
     * SHIPPED
     * DELIVERED
     * REJECTED
     * RETURNED
     * REFOUNDED
     * CLOSED
     * CANCELLED
    */
    status: string;
    dates: {
        [date: string]: number;
    };
    /** numbero di traking */
    trackingNumber: string;
    /** importo al netto della spedizione, scontatoe  ma  senza iva */
    get value(): number;
    /** importo al netto della spedizione, senza sconto individuale, comprensivo di iva */
    get subtotal(): number;
    /** importo al netto della spedizione ma con gli elementi scontati individualmente, comprensivo di iva */
    get total(): number;
    /** importo finale da pagare comprensivo delle spese di spedizione e dello sconto sugli elementi  e dell'iva */
    amount(service: IShippingCostService): Observable<number>;
    /** il massimo dei giorni di elaborazione tra tutti gl iitems  */
    get processingTime(): number;
    /**
     * aggiunge all'elenco dell'ordine
     */
    addArticle(p: Product): Order;
    /** rimuove un prodotto dall'ordine */
    removeItem(p: Product): Order;
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
    open(): Order;
    /**
     * WORKING - imposta come pagato e confermato l'ordine ,  in attesa di conclusione
     * inoltre salvare l'ordine sul server e impostare il finalPrice
     */
    buy(service: IFinalPriceService): Order;
    /**
     * DELIVERING - pacco in consegna
     */
    ship(): Order;
    /**
     *
     */
    close(positive: boolean): Order;
    /**
     *
     */
    cancel(): Order;
    /**
     * da definire
     */
    reject(): Order;
    /**
     * da definire
     */
    retake(): Order;
    /**
     * da definire
     */
    refound(): Order;
}

import { Price } from "./price";
import { Observable, pipe } from 'rxjs'
import { map } from 'rxjs/operators'
import { IStokService } from "../../interfaces/stock-service.interface";
import { IRankService } from "../../interfaces/rank-service.interface";
import { annualCode } from '../../functions'

/** valore di default in giorni  */
const BASE_PROCESSING_TIME = 4;



/** definizione dell'oggetto contentente i dettagli
 * @param detail  KEY identificativo univoco senza spazi dell'attributo
 * @param label {string} etichetta mostrata come indice dell'attributo
 * @param value valore dell'atributo
 */
type Detail = { [detail: string]: { label: string; value: any; }; };



export class Product{

    constructor(p : Partial<Product>){
        /** genera un ID solo se non è passato nel contructor */
        p.productId = p.productId ? p.productId : annualCode('p');
        /** inizializza il prezzo se null */
        p.price = p.price ? new Price(p.price) : new Price({});
        /** assegna le proprietà */
        Object.assign(this, p);
    }


/***********************************************************************+ */

    /** ID del database */
    public productId: string = null;

    /** codice identificatore che individua  il singolo articolo   (singola string asenza spazi) */
    public identifier: string = undefined;
    
    /** product code  - Stock Keeping Unit per la tracciabilità */
    public sku: string = undefined;

    /** GRUPPO O UNITA' che raggruppa tutti gli articoli  classificabili come un solo prodotto */
    public unit: string = null;

    /** descrizione che differenzia tra tutti i prodotti di un singolo UNIT
    * per esempio indicazioni su taglia,  colore e materiale
    */
    public model: string = null;

    /** identifica se visibile sul sito , di default per sicurezza è impostato a true */
    public hidden: boolean = true;


/***********************************************************************+ */

    /** disponibilià */
    // public availabile = async (service: IStokService) => {return await this.stock(service).pipe(map(stock => stock > 0)).toPromise()}
    public  availabile = (service: IStokService) =>  this.stock(service).pipe(map(stock => stock > 0)) 

    get outOfStock() { return !this.availabile }; //ERRORE

    /** titolo di presentazione del prodotto */
    public title : string = undefined

    /** description */
    public description : string = undefined;

    /** ATTRIBUTI
     * dimensions * color * size * wheight * ecc...
     */
    public details: Detail = {}

    /** prezzo */
    public price: Price;


    /** 
     * numero di oggetti disponibili in magazzino
     * se UNDEFINED il valore deve essere considerato come infinito
     * @param service il servizio che gestice le socrte di magazzino e che implementa l'interfaccia IsStockSerice
     */
    public stock = (service: IStokService) => service.stock(this.sku) 

    /** array di percordi per l'immagini del prodotto */
    public images: string[] = [];

    /** numero di giorni massimo in cui il poster può essere spedito */
    public processingTime: number = BASE_PROCESSING_TIME;


    /** valore che permette di ordinare i titoli in base alla loro popolarità */
    public rank : number = 0

    /** 
     * ottiene il valore aggiornat  che permette di ordinare i titoli in base alla loro popolarità.
     * @param service il servizio che gestice le socrte di magazzino e che implementa l'interfaccia IRankService
     */
    public getRank =  (service: IRankService) => service.rank(this)


    /** coefficiente promozionale per il rank */
    public promoFactor: number = 0;

    /** titoli correlati */
    public related: string[] = [];

    /** array di tags */
    public tags: string[] = [];

    /** categoria */
    public categories: string[] = [];

}



import { Price } from "./price";
import { Observable } from 'rxjs';
import { IStokService } from "../../interfaces/stock-service.interface";
import { IRankService } from "../../interfaces/rank-service.interface";
/** definizione dell'oggetto contentente i dettagli
 * @param detail  KEY identificativo univoco senza spazi dell'attributo
 * @param label {string} etichetta mostrata come indice dell'attributo
 * @param value valore dell'atributo
 */
declare type Detail = {
    [detail: string]: {
        label: string;
        value: any;
    };
};
export declare class Product {
    constructor(p: Partial<Product>);
    /***********************************************************************+ */
    /** ID del database */
    productId: string;
    /** codice identificatore che individua  il singolo articolo   (singola string asenza spazi) */
    identifier: string;
    /** product code  - Stock Keeping Unit per la tracciabilità */
    sku: string;
    /** GRUPPO O UNITA' che raggruppa tutti gli articoli  classificabili come un solo prodotto */
    brand: string;
    /** identifica se visibile sul sito , di default per sicurezza è impostato a true */
    hidden: boolean;
    /***********************************************************************+ */
    /** disponibilià */
    availabile: (service: IStokService) => Observable<boolean>;
    get outOfStock(): boolean;
    /** titolo di presentazione del prodotto */
    title: string;
    /** description uguale per tutti gli articoli di un unico BRAND */
    description: string;
    /** descrizione che differenzia tra tutti i prodotti di un singolo BRAND
    * per esempio indicazioni su taglia,  colore e materiale
    */
    model: string;
    /** ATTRIBUTI
     * dimensions * color * size * wheight * ecc...
     */
    details: Detail;
    /** prezzo */
    price: Price;
    /**
     * numero di oggetti disponibili in magazzino
     * se UNDEFINED il valore deve essere considerato come infinito
     * @param service il servizio che gestice le socrte di magazzino e che implementa l'interfaccia IsStockSerice
     */
    stock: (service: IStokService) => Observable<number>;
    /** array di percordi per l'immagini del prodotto */
    images: string[];
    /** numero di giorni massimo in cui il poster può essere spedito */
    processingTime: number;
    /** valore che permette di ordinare i titoli in base alla loro popolarità */
    rank: number;
    /**
     * ottiene il valore aggiornat  che permette di ordinare i titoli in base alla loro popolarità.
     * @param service il servizio che gestice le socrte di magazzino e che implementa l'interfaccia IRankService
     */
    getRank: (service: IRankService) => number | Observable<number>;
    /** coefficiente promozionale per il rank */
    promoFactor: number;
    /** titoli correlati */
    related: string[];
    /** array di tags */
    tags: string[];
    /** categoria */
    categories: string[];
}
export {};

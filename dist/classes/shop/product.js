import { Price } from "./price";
import { map } from 'rxjs/operators';
import { annualCode } from '../../functions';
/** valore di default in giorni  */
var BASE_PROCESSING_TIME = 4;
var Product = /** @class */ (function () {
    function Product(p) {
        var _this = this;
        /***********************************************************************+ */
        /** ID del database */
        this.productId = null;
        /** codice identificatore che individua  il singolo articolo   (singola string asenza spazi) */
        this.identifier = undefined;
        /** product code  - Stock Keeping Unit per la tracciabilità */
        this.sku = undefined;
        /** GRUPPO O UNITA' che raggruppa tutti gli articoli  classificabili come un solo prodotto */
        this.brand = null;
        /** identifica se visibile sul sito , di default per sicurezza è impostato a true */
        this.hidden = true;
        /***********************************************************************+ */
        /** disponibilià */
        // public availabile = async (service: IStokService) => {return await this.stock(service).pipe(map(stock => stock > 0)).toPromise()}
        this.availabile = function (service) { return _this.stock(service).pipe(map(function (stock) { return stock > 0; })); };
        /** titolo di presentazione del prodotto */
        this.title = undefined;
        /** description uguale per tutti gli articoli di un unico BRAND */
        this.description = undefined;
        /** descrizione che differenzia tra tutti i prodotti di un singolo BRAND
        * per esempio indicazioni su taglia,  colore e materiale
        */
        this.model = null;
        /** ATTRIBUTI
         * dimensions * color * size * wheight * ecc...
         */
        this.details = {};
        /**
         * numero di oggetti disponibili in magazzino
         * se UNDEFINED il valore deve essere considerato come infinito
         * @param service il servizio che gestice le socrte di magazzino e che implementa l'interfaccia IsStockSerice
         */
        this.stock = function (service) { return service.stock(_this.sku); };
        /** array di percordi per l'immagini del prodotto */
        this.images = [];
        /** numero di giorni massimo in cui il poster può essere spedito */
        this.processingTime = BASE_PROCESSING_TIME;
        /** valore che permette di ordinare i titoli in base alla loro popolarità */
        this.rank = 0;
        /**
         * ottiene il valore aggiornat  che permette di ordinare i titoli in base alla loro popolarità.
         * @param service il servizio che gestice le socrte di magazzino e che implementa l'interfaccia IRankService
         */
        this.getRank = function (service) { return service.rank(_this); };
        /** coefficiente promozionale per il rank */
        this.promoFactor = 0;
        /** titoli correlati */
        this.related = [];
        /** array di tags */
        this.tags = [];
        /** categoria */
        this.categories = [];
        /** genera un ID solo se non è passato nel contructor */
        p.productId = p.productId ? p.productId : annualCode('p');
        /** inizializza il prezzo se null */
        p.price = p.price ? new Price(p.price) : new Price({});
        /** assegna le proprietà */
        Object.assign(this, p);
    }
    Object.defineProperty(Product.prototype, "outOfStock", {
        get: function () { return !this.availabile; },
        enumerable: true,
        configurable: true
    });
    ; //ERRORE
    return Product;
}());
export { Product };

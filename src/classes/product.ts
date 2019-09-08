

class Price {

    constructor(p: Partial<Price>){
        Object.assign(this, p);
    }

    /** il prezzo assegnato */
    base: number = undefined;

    /** sconto percentuale  */
    discount: number = 1;

    /** ERRORE bisogna calcolarlo vedi POSTERBOOK */
    get price(): number{
        return this.base * this.discount
    };
};





export class Product{

    constructor(p : Partial<Product>){

        p.price = p.price ? new Price(p.price) : new Price({});

        Object.assign(this, p);
    }

    /** ID del database */
    productID: string = null;

    /** identificatore che individua  il singolo articolo  (singola string asenza spazi) */
    article: string = undefined;

    /** unità che raggruppa tutti gli articoli  classificabili come un solo prodotto */
    unit: string = null;

    /** product code  - Sock Keeping Unit per la tracciabilità */
    sku: string = undefined;

    /** ATTRIBUTI
     * dimensions * color * size * wheight * ecc...
     */
    attrs: { [key: string]: any } = {}

    /** prezzo */
    price: Price

    /** disponibilià */
    availabile : boolean = true;

    

}
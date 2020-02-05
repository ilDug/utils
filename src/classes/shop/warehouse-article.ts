
/**
 * code         [base36]            3 digits codice prodotto diverso per marca, modello, talgia, colore, materiale, ecc
 * batch        year [numbers]      2 digits
 * bathc num    [base 36]           2 digits
 * item num      [number]           variable digits starting from 1, sequential
 * id database  [number]            variable digit starting from 1
 * 
 * PO.001.1901.1.1
 * 
 */




export class WarehouseArticle{

    constructor(wa: Partial<WarehouseArticle>){
        
        if(!wa.productId) throw new Error("il productId è obbligatorio");
        if(!wa.sku) throw new Error("il SKU è obbligatorio");
        
        Object.assign(this, wa);
    }

    /** id del database generato in automatico dal database */
    public articleId: string = null;

    /** riferimento all'ID del prodotto (OBBLIGATORIO) */
    public productId: string = null;

    /** riferimento all'SKU del prodotto (OBBLIGATORIO) 
     * nella forma XXX
    */
    public sku: string = null;

    /** 
     * codice della fornitua 
     * generato in automatico dal database
     * nella forma 20xx
     */
    public batch: string = null;

    /** codice consecutivo per ogni batch in base alla quantità */
    public item: number = null;

    public available: number = null;

    public dateIn: number = null;

    public dateOut: number = null;

    public dateExpiry: number = null;

    public status: string = "STOCK";

    /**
     * STOCK
     * EXPIRED
     * SOLD
     * GIFT
     * OBSOLETE
     * LOST
     */

     get code(){
         return `${this.sku}.${this.batch}.${this.item}}`
     }
}
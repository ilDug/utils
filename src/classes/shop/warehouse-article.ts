
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
        Object.assign(this, wa);
    }

    public articleId: string = null;

    public productId: string = null;

    public sku: string = null;

    public batch: string = null;

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
         return `${this.sku}.${this.batch}.${this.item}.${this.articleId}`
     }
}
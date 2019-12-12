
/**
 * type         [CAPITAL LETTERS]   2-4 digits
 * code         [base20]            3 digits
 * batch        year [numbers]      2 digits
 * bathc num    [base 20]           2 digits
 * item num      [number]            variable digits starting from 1
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

    public status: string = "STOCK"

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
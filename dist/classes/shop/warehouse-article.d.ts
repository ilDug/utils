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
export declare class WarehouseArticle {
    constructor(wa: Partial<WarehouseArticle>);
    articleId: string;
    productId: string;
    sku: string;
    batch: string;
    item: number;
    available: number;
    dateIn: number;
    dateOut: number;
    dateExpiry: number;
    status: string;
    /**
     * STOCK
     * EXPIRED
     * SOLD
     * GIFT
     * OBSOLETE
     * LOST
     */
    get code(): string;
}

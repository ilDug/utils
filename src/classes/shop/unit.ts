import { Product } from "./product";

export class Unit {
    constructor(products: Product[]) {
        this.products = [...products];
    }



    public products: Product[] = [];



    get name():string {
        if (this.products.length === 0) return 'undefined unit name';
        return this.products[0].unit;
    }



    public addProduct(p: Product): Unit {
        this.products.push(p);
        return this;
    }
}

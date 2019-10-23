import { Product } from "./product";

export class Brand {
    constructor(products: Product[]) {
        this.products = [...products];
    }

    public products: Product[] = [];


    get name():string {
        if (this.products.length === 0) return 'undefined unit name';
        return this.products[0].brand;
    }



    public addProduct(p: Product): Brand {
        this.products.push(p);
        return this;
    }
}

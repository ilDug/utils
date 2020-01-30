import { Product } from "./product";
export declare class Brand {
    constructor(products: Product[]);
    products: Product[];
    get name(): string;
    addProduct(p: Product): Brand;
}

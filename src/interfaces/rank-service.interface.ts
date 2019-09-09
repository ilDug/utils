import { Observable } from 'rxjs';
import { Product } from "../classes/shop/product";

export interface IRankService {
    /**
     * calcola e restituisce il valore di posizionamento in base all'algoritmo della popolarità
     * @param product 
     */
    rank(product: Product): number | Observable<number>;
}

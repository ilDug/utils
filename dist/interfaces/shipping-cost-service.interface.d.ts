import { Order } from '../classes/shop/order';
import { Observable } from 'rxjs';
export interface IShippingCostService {
    /**
     * ritorn il costo di spedizon in bae agl iindirizzi ed algi ordini
     * @param order ordine completo di indirizzi
     */
    cost(order: Order): Observable<number> | number;
}

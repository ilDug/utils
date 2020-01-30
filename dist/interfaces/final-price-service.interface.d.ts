import { Order, Article } from "../classes/shop";
export interface IFinalPriceService {
    /**
     * restituisce il prezzo finale di ogni articolo
     */
    price(article: Article, o: Order): Promise<number> | number;
}

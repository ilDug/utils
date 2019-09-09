import { Observable } from 'rxjs';

export interface IStokService {
    /**
     * metodo che contatta il database  per sapere quanti colli ci sono 
     * di un determinato prodotto identificato con il SKU
     * @param sku codice prodotto {string}
     */
    stock(sku: string): Observable<number> | number | undefined;
}

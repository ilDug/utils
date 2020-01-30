import { Observable } from 'rxjs';
import { HttpClient } from '@angular/common/http';
export declare abstract class RestService<T> {
    protected http: HttpClient;
    /**
     * @param baseUrl url base dove inviare le richieste http,  nella forma https://api.sito.lan/items/
     */
    constructor(baseUrl: string, http: HttpClient);
    private baseUrl;
    private items;
    get items$(): Observable<T[]>;
    /**
     * carica la lista di oggetti e resituisce una Promise
     * chiamato nel constructor
     */
    call(): Promise<T[]>;
    /** carica la lista di oggetti */
    list(): Observable<T[]>;
    /** load a singlo object by ID */
    load(id: string): Observable<T>;
    /** elabora le liste in arrivo dal server */
    abstract listParser(list: T[]): T[];
    /** elabora l'oggetto in arrivo dal server */
    abstract itemParser(item: T): T;
    /** method to INSERT object to server */
    abstract add(object: T): Observable<T>;
    /** method to UPDATE object to server */
    abstract edit(object: T): Observable<T>;
    /** method to DELETE object from server */
    abstract remove(object: T): Observable<boolean>;
}

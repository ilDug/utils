import { ReplaySubject, Observable, pipe } from 'rxjs'
import { map, filter, tap } from 'rxjs/operators'
import { HttpClient } from '@angular/common/http'

export abstract class RestService<T>{
    /**
     * @param endpoint url base dove inviare le richieste http,  nella forma https://api.sito.lan/items/
     */
    constructor(
        endpoint: string,
        protected http: HttpClient
    ){
        this.endpoint = endpoint
        this.call()
    }

    private endpoint:string
    private items: ReplaySubject<T[]> = new ReplaySubject(1)
    get items$(): Observable<T[]> { return this.items.asObservable() }


    /** 
     * carica la lista di oggetti e resituisce una Promise
     * chiamato nel constructor
     */
    call():Promise<T[]>{
        return this.list().toPromise()
    }

    /** carica la lista di oggetti */
    public list():Observable<T[]>{
        return this.http.get<T[]>(this.endpoint)
                .pipe(
                    filter(x => x !== null),
                    map(list => this.listParser(list)),
                    tap(list => this.items.next(list))
                )
    }
    
    /** load a singlo object by ID */
    public load(id: string): Observable<T>{
        return this.http.get<T>(this.endpoint + id)
            .pipe(
                map(item => this.itemParser(item)),
            )
    }
        
    /** elabora le liste in arrivo dal server */
    abstract listParser(list: T[]): T[] 

    /** elabora l'oggetto in arrivo dal server */
    abstract itemParser(item: T): T 

    /** method to INSERT object to server */
    abstract add(object: T): Observable<T>

    /** method to UPDATE object to server */
    abstract edit(object: T): Observable<T>

    /** method to DELETE object from server */
    abstract remove(object: T): Observable<boolean>

   
}



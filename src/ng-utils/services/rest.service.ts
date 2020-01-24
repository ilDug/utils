import { ReplaySubject, Observable, pipe } from 'rxjs'
import { map } from 'rxjs/operators'
import { HttpClient } from '@angular/common/http'

export abstract class RestService<T>{

    constructor(
        protected http: HttpClient
    ){}

    private items: ReplaySubject<T[]> = new ReplaySubject(1)

    get items$(): Observable<T[]> { return this.items.asObservable() }

    list():Promise<T[]>{
        return this.http.get<T[]>('url')
                .pipe(this.parser)
                .toPromise()
    }

    abstract parser = (any[]) => OperatorFunction

}


export class ssdsd extends RestService<string>{
    parser: import("rxjs").UnaryFunction<unknown, unknown>
}
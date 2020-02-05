import { HttpEvent, HttpInterceptor, HttpHandler, HttpRequest, HttpResponse, HttpErrorResponse } from '@angular/common/http';
import { Observable, throwError } from 'rxjs';
import { map, tap, finalize, catchError } from 'rxjs/operators';
import { Injectable } from '@angular/core';
import { OptimisticConcurrencyControlService } from './occ.service';



@Injectable()
export class OptimisticConcurrencyControlInterceptor implements HttpInterceptor {
    constructor( private occ: OptimisticConcurrencyControlService){

    }
    intercept(req: HttpRequest<any>, next: HttpHandler): Observable<HttpEvent<any>>{

        let occReq: HttpRequest<any> = req.clone();

        /**
         * imposta l'header con If-Match se trova un ETag salvato nel OptimisticConcurrencyControlService
         */
        if(this.occ.hasToken()){
            /**solo se la richiesta è PUT */
            if(req.method === "PUT"){
                occReq = req.clone({
                    headers: req.headers.set("If-Match", this.occ.getETagAndClear())
                });
                console.log(occReq.headers);
            }
        }


        return next.handle(occReq)
            .pipe(
                tap(event => {
                    /** salva l'etag delal risposta nel service adeguato */
                    if(event instanceof HttpResponse) {
                        if (event.headers.has("ETag")){
                            this.occ
                                .setETag(event.headers.get("ETag"))
                                .setOrigin(event.url)
                        }
                    }
                })
            );
        
    }
}

import { HttpEvent, HttpInterceptor, HttpHandler, HttpRequest, HttpResponse, HttpErrorResponse, HttpHeaders } from '@angular/common/http';
import { Observable } from 'rxjs';
import { map, tap, finalize } from 'rxjs/operators';
import { Injectable } from '@angular/core';
import { Authentication } from '../services';



@Injectable()
export class AuthInterceptor implements HttpInterceptor {


    constructor(private auth: Authentication) { }



    intercept(req: HttpRequest<any>, next: HttpHandler): Observable<HttpEvent<any>> {
        // Get the auth token from the service.
        const authToken = "Bearer " + this.auth.jwt;

        
        // Clone the request and replace the original headers with
        // cloned headers, updated with the authorization.
        const authReq: HttpRequest<any>= req.clone({
          headers: req.headers
            .set("Content-Type", "application/json")
            .append("Authorization", authToken)
        });

        // send cloned request with header to the next handler.
        return next.handle(authReq);
    }
}

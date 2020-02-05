import { HTTP_INTERCEPTORS, HttpHeaders } from "@angular/common/http";
import { LogInterceptor } from './log.interceptor';
import { AuthInterceptor } from './auth.interceptor';
import { OptimisticConcurrencyControlInterceptor } from './occ.interceptor';


/** Http interceptor providers in outside-in order */
export const httpInterceptorProviders = [
    { provide: HTTP_INTERCEPTORS, useClass: AuthInterceptor, multi: true },
    { provide: HTTP_INTERCEPTORS, useClass: OptimisticConcurrencyControlInterceptor, multi: true },
    { provide: HTTP_INTERCEPTORS, useClass: LogInterceptor, multi: true },
];

export const USE_INTERCEPTOR_HEADER = "Use-Interceptor";
export const AVOID_INTERCEPTOR_HEADER = "Avoid-Interceptor";

export type  DagInterceptor = 'LOG' | 'AUTH' | 'OOC';

const defaultHttpOptions = {
    headers: new HttpHeaders({
        'Content-Type': 'application/json',
        'Use-Interceptor': 'my-auth-token'
    })
};

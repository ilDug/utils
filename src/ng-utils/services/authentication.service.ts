import { IAuthentication } from "../interfaces";
import { Injectable } from "@angular/core";


@Injectable({
    providedIn:'root'
})
export class Authentication implements IAuthentication{
      
    jwt;
    
    uid(): string {
        throw new Error("Method not implemented.");
    }
    jti(): string {
        throw new Error("Method not implemented.");
    }
    email(): string {
        throw new Error("Method not implemented.");
    }
    authenticated(): boolean {
        throw new Error("Method not implemented.");
    }
    logout(): void {
        throw new Error("Method not implemented.");
    }


}
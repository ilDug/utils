import { Injectable } from '@angular/core';





@Injectable({
  providedIn: "root"
})
export class OptimisticConcurrencyControlService  {
    private hash: string;
    public url: string;
    public id: number;

    constructor() {}


    set etag(hash:string){ 
        this.hash = hash 
        console.log('OCC token ' , this.hash)
    }



    get etag():string { 
        return this.hash
    }



    /**
     * salva il token 
     * @param hash token ETag
     */
    public setETag(hash: string) { 
        this.hash = hash
        console.log('OCC token ', this.hash)
        return this 
    }


    /**
     * restituisce ETag salvato e lo cancella
     */
    public getETagAndClear():string{
        const tag = this.hash;

        /** azzera la proprietà una volta che è stata richista   */
        this.hash = null;
        return tag

    }


    public setOrigin(url: string){
        this.url = url;
         return this;
    }



    public setId(id: number){
        this.id = id;
         return this;
    }



    public hasToken():boolean{
        if(this.hash === null || this.hash === undefined || this.hash ==="") return false;
        else return true;
    }


}

/**
 * operazioni di decodifica e controllo delle informazioni di un token JWT
 * verione aggiornata a settembre 2019
 * durante l'istanza dell'oggetto viene passato il token grezzo
 */
export declare class Jwt {
    /** il contenuto del token */
    claims: any;
    /** il token grezzo string */
    private token;
    /** controlla se il token è scaduto */
    get expired(): boolean;
    /** restituisce la data di scadenza el token icome oggetto Date */
    get expirationDate(): Date;
    constructor(jwt: string);
    private urlBase64Decode;
    private b64decode;
    private b64DecodeUnicode;
    /**
    * restituisce il token decodificato (tipo any) o null
    */
    private decodeToken;
    /**
     * resituisce un ofggetto DATE contenenta la data di scadenza del token ('exp')
     */
    private getTokenExpirationDate;
    /**
     * @return boolean
     * verifica che il token non sia scaduto
     */
    private isTokenExpired;
}

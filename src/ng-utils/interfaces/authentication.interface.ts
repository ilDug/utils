
export interface IAuthenticationI {
    /**
     * GETTER method for JWT 
     * read from LocalStorage 
     * @return the raw token
     */
    jwt(): string;

    /**
     * SETTER
     * salva il jwt grezzo ottenuto dal server nel Local Storage
     */
    jwt(t: string): void;


    /**
     * GETTER
     * restituisce il codice IDPcode dell'utente.
     * @return uid
     */
    uid(): string;


    /**
     * GETTER
     * @return id del JWT
     */
    jti(): string


    /**
     * GETTER
     * @return username
     */
    username(): string



    /**
    * verifica se l'utente è authenticato tramite JWT
    */
    authenticated(): boolean;


    /**
     * logout
     */
    logout(): void;
}
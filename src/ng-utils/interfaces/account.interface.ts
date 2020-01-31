import { Observable } from "rxjs";


export interface IAccountService {
    /**
     * esegue il login con email e password 
     * salva il jwt in Local sotrage con la classe authentication
     * @return il JWT grezzo
     */
    login(email: string, password: string): Observable<string>

    /**
     * registra un nuovo utente con email e password
     * gli invia la mail di attivazione
     * @return il JWT grezzo
     */
    register(email: string, password: string): Observable<string>

    /**
     * invia una nuova email per l'attivazione
     */
    resendActivation(email: string): Observable<boolean>

    /**
     * attiva l'accout
     */
    activate(activationKey: string): Observable<boolean>

    /**
     * recupera la password dimenticata a partire dalla email
     * invia una mail per la procedura di ripristino
     */
    recoverPassword(email: string): Observable<boolean>

    /**
     * esegue i controlli per la reimpostazione della password utente
     * @return il codie uid dell'utente
     */
    restoreInit(restoreKey:string):Observable<string>

    /**
     * ripristina una nuova password
     */
    restoreSet(restoreKey: string, newPassword:string): Observable<boolean>
}
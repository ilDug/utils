import { Observable } from 'rxjs';
interface YearChange {
    prev: number;
    next: number;
}
export default class Years {
    constructor(base?: number);
    private BASE;
    private change;
    /** emette uno stream ogni volta che l'anno viene cambiato { prev: number, next: number } */
    get change$(): Observable<YearChange>;
    /** l'anno impostato come attivo */
    private _activeYear;
    get activeYear(): number;
    set activeYear(y: number);
    /** la lista degli anni da quello attuale fino al numero di base */
    get years(): number[];
    /** l'anno in corso */
    get currentYear(): number;
}
export {};

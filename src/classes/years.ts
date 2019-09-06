import { Subject, Observable } from 'rxjs'


interface YearChange { prev: number, next: number }


export default class Years {

    constructor(base?: number) {
        this.BASE = base || this.BASE;
        this._activeYear = this.currentYear
    }

    private BASE: number = 2016;


    private change: Subject<{ prev: number, next: number }> = new Subject();

    /** emette uno stream ogni volta che l'anno viene cambiato { prev: number, next: number } */
    get change$(): Observable<YearChange> { return this.change.asObservable() }


    /** l'anno impostato come attivo */
    private _activeYear: number;
    get activeYear(): number { return this._activeYear }
    set activeYear(y: number) {
        this.change.next({ prev: this._activeYear, next: y });
        this._activeYear = y
    }


    /** la lista degli anni da quello attuale fino al numero di base */
    get years(): number[] {
        let current: number = new Date().getFullYear();
        let years: number[] = [];
        for (let i = this.BASE; i <= current; i++) { years.push(i); }
        years.reverse();
        return years;
    }

    /** l'anno in corso */
    get currentYear(): number { return new Date().getFullYear() }
}
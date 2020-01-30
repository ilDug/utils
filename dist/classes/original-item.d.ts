export default class OriginalItem<T> {
    original: T;
    current: T;
    constructor(item: T);
    get equal(): boolean;
    get changed(): boolean;
    /**
     * ritorna una copia clonata del contenuto
     * @param type sceglere  se ritornale "original" oppure "current"
     */
    clone(type: string): any;
}

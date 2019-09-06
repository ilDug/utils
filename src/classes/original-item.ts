import * as _ from 'lodash'

export default class OriginalItem<T>{
    public original: T;
    public current: T;

    
    constructor(item:T){
        this.current = item;
        this.original = _.cloneDeep(item)
    }



    get equal():boolean{
        if (!this.original) return false;
        return _.isEqual(this.original, this.current)
    }



    get changed():boolean{
        return ! this.equal
    }


    /**
     * ritorna una copia clonata del contenuto
     * @param type sceglere  se ritornale "original" oppure "current"
     */
    clone(type:string){
        switch (type) {
            case 'original':
                return _.cloneDeep(this.original);
                break;
        
            case 'current':
                return _.cloneDeep(this.current);
                break;
        
            default:
                break;
        }
    }

}
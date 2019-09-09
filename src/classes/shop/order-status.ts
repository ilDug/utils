

/**
 * classe che descrive lo stato dell'oridine
 * definisce se è pagato, spedito, consegnato ,  reso, accettato, in fase di...

 * metodi che posrtano alle rispettive fasi dell'ordine
 * open --> Shopping
 * buy  --> Working
 * pack --> Shipping
 * ship  --> Delivering
 * close --> Closing (positive)
 *
 * reject  --> Returning
 * retake --> Refounding
 * refound --> close --> Closing (negative)
 */
export class OrderStatus{
    /** VERO durante lo shopping, e l'ordine non è ancora stato confermato. FALSO quando l'ordine è confermato o pagato */
    public opened: boolean;

    /** indica se è avvenuto il pagamento */
    public payed: boolean;

    /** indica se l'ordine è passato dal checkout ed è stato confermato dall'utente (dopo il pagamento) */
    public confirmed : boolean;

    /** VERO quando l'ordine è stato elaborato (stampa + confezionamento) */
    public worked: boolean;

    /** VERO quando l'ordine viene spedito */
    public shipped: boolean;

    /** indica se è stata emessa fattura ufficiale */
    public invoiced: boolean;

    /** quando è stato consengato e sono passati 30gg enza reclami */
    public accepted: boolean;

    /** quando il cliente lamenta una non conformità */
    public rejected: boolean;

    /**  VERO quando viene consegnato in sede il reso  */
    public returned: boolean;

    /** indica se il pagamento è stato stornato */
    public refounded : boolean;

    /** l'ordine diventa chiuso nel momento cui è stato consegnato e accettato dopo 30 gg */
    public closed: boolean;



    /** data di creazione dell'ordine */
    public creationDate:number;

    /** data del pagamento */
    public paymentDate:number;

    /** data dell'invio del pacco al corriere */
    public shippingDate:number;

    /** data stimata o registrata della consegna */
    public deliveringDate:number;

    /** data di chiusura del'ordine */
    public closingDate:number;


    constructor(s:any){
        this.opened = s.open ? s.open : undefined;
        this.payed = s.payed ? s.payed : undefined;
        this.confirmed = s.confirmed ? s.confirmed : undefined;
        this.worked = s.working ? s.working : undefined;
        this.shipped = s.shipped ? s.shipped : undefined;
        this.invoiced = s.invoiced ? s.invoiced : undefined;
        this.accepted = s.accepted ? s.accepted : undefined;
        this.rejected = s.rejected ? s.rejected : undefined;
        this.returned = s.returned ? s.returned : undefined;
        this.refounded = s.refounded ? s.refounded : undefined;
        this.closed = s.closed ? s.closed : undefined;

        this.creationDate = s.creationDate ? s.creationDate : null;
        this.paymentDate = s.paymentDate ? s.paymentDate : null;
        this.shippingDate = s.shippingDate ? s.shippingDate : null;
        this.deliveringDate = s.deliveringDate ? s.deliveringDate : null;
        this.closingDate = s.closingDate ? s.closingDate : null;
    }


    get status():string{
        let status : string;
        // if(this.opened) status.push('OPEN');
        // if(this.closed) status.push('CLOSED');
        // if(this.worked) status.push('WORKING');
        // if(this.shipped) status.push('SHIPPED');
        // if(this.rejected) {
        //     if(this.returned) status.push('RETURNED');
        //     else if (this.refounded) status.push('REFOUNDED');
        //     else status.push('REJECTED');
        // }
        return status;
    }




    /**
     * SHOPPING - inizializza l'ordine in fase di apertura
     */
    public open():OrderStatus{
        this.opened  = true;
        this.payed  = false;
        this.confirmed  = false;
        this.worked  = false;
        this.shipped  = false;
        this.invoiced  = false;
        this.accepted  = undefined;
        this.rejected  = undefined;
        this.returned  = undefined;
        this.refounded  = undefined;
        this.closed  = false;

        this.creationDate = new Date().getTime();
        return this;
    }




    /**
     * WORKING - imposta come pagato e confermato l'ordine ,  in attesa di conclusione
     * inoltre salvare l'ordine sul server e impostare il finalPrice
     */
    public buy():OrderStatus{
        this.opened  = false;
        this.payed  = true;
        this.confirmed  = true;
        this.worked  = false;
        this.shipped  = false;
        this.closed  = false;

        this.paymentDate = new Date().getTime();
        return this;
    }




    /**
     * SHIPPING - segna la conclusione della fase di preparazione dell'ordine,  in attesa di spedizione
     */
    public pack():OrderStatus{
        this.opened  = false;
        this.payed  = true;
        this.confirmed  = true;
        this.worked  = true;
        this.shipped  = false;
        this.closed  = false;

        return this;
    }




    /**
     * DELIVERING - pacco in consegna
     */
    public ship():OrderStatus{
        this.opened  = false;
        this.payed  = true;
        this.confirmed  = true;
        this.worked  = true;
        this.shipped  = true;
        this.closed  = false;

        this.shippingDate = new Date().getTime();
        return this;
    }




    /**
     *
     */
    public close(positive:boolean):OrderStatus{
        this.opened  = false;
        this.payed  = true;
        this.confirmed  = true;
        this.worked  = true;
        this.shipped  = true;
        this.closed  = true;

        this.accepted = positive ;
        this.rejected = !positive ;

        this.closingDate = new Date().getTime();
        return this;
    }




    /**
     * da definire
     */
    public reject():OrderStatus{

        return this;
    }




    /**
     * da definire
     */
    public retake():OrderStatus{

        return this;
    }




    /**
     * da definire
     */
    public refound():OrderStatus{

        return this;
    }



}
